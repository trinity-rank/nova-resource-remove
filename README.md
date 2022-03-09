## Installation

### Step 1: Install package

To get started with Laravel Geo Location, use Composer command to add the package to your composer.json project's dependencies:

```shell
    composer require trinityrank/nova-resource-remove
```

### Step 2: Configuration

- You need to import class in Nova ressource

```shell
    use Trinityrank\LaravelNovaResourceRemove\NovaResourceRemove;
```

- And then you need add actions function in Nova ressource
- It is strongly recommended to use onlyOnTableRow()
- The first parameter is the path of the resource-related model
- The second is the name of foreign key column 
- 3rd parameter is the name of the foreign table to which the model is attached


```shell
    public function actions(Request $request)
    {
        return [
            (new NovaResourceRemove(
                ['\App\Models\Types\Category', 'category_id', ['categoriables']]
            ))->confirmButtonText('Remove Category')->onlyOnTableRow()
        ];
    }
```

- Define the columns you want to add copy 
- Columns slug and status have default copy values

```shell
    public function actions(Request $request)
    {
        return [
            new NovaResourceCopy([
                ['name', 'title']
            ]) 
        ];
    }
```

- If you want to copy relationships which are related to the model

```shell
    public function actions(Request $request)
    {
        return [
            new NovaResourceCopy([
                [],
                [['categoriables','categoriable']]
            ]) 
        ];
    }
```

- Example

```shell
    public function actions(Request $request)
    {
        return [
            new NovaResourceCopy([
                ['name', 'title'], 
                [['categoriables','categoriable'], ['seos','seoable'], ['job_tag', 'job']]
            ]) 
        ];
    }
```
