<?php

namespace Trinityrank\LaravelNovaResourceRemove;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\Select;

class NovaResourceRemove extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;
 
    protected $data;

    public $name = 'Remove';

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle(ActionFields $fields, Collection $models)
    {
        $models_ids_array = $models->pluck('id')->toArray();

        if ( in_array($fields->accredit, $models_ids_array)) {
            return Action::danger('You cannot assign posts to the resource which you want to delete!');
        }

        $name = $this->data[1]; 

        foreach ($models as $model) {
            $relation_exists = collect($this->data[2])->contains(function ($item) use ($name, $model) {
                if (Schema::hasColumn($item, $name)) {
                    return DB::table($item)->where($name, $model->id)->exists();
                }
            });

            $column_not_exists = collect($this->data[2])->contains(function ($item) use ($name, $model, $fields) {
                return !Schema::hasColumn($item, $name);
            });

            if ($column_not_exists) {
                return Action::danger('The entered column or table does not exist!');
            }

            if ($relation_exists) {
                if ($fields->accredit === null) {
                    return Action::danger('This row resource has posts, You must assigne another resource!');
                }

                collect($this->data[2])->each(function ($item) use ($name, $model, $fields) {
                    if (Schema::hasColumn($item, $name)) {
                        $posts = DB::table($item)->where($name, $model->id)->update([$name => $fields->accredit]);
                    }
                });

                $model->delete();
            } else {
                $model->delete();
            }
        }

        return Action::message('You succesfully remove resource');
    }

    public function fields()
    {
        if (class_exists($this->data[0])) {
            $resources = $this->data[0]::all()->pluck('name', 'id')->toArray();
        } else {
            $resources = [];
        }

        return [
            Select::make('Accredit')->options($resources),
        ];
    }

    public function actionClass()
    {
        return 'h-8 text-red-400 shadow-none';
    }
}