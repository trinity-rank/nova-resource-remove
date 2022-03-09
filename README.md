## Installation

### Step 1: Install package

To get started with Nova Resource Remove, use Composer command to add the package to your composer.json project's dependencies:

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

- If you want to authorize an action not to appear for a particular resource

```shell
    public function actions(Request $request)
    {
        return [
            (new  NovaResourceRemove(
                ['\App\Models\User', 'user_id', ['pages','articles']]
            ))->canSee(function ($request) {
                return !$this->model()->hasRole(['Super Admin']);
            })->confirmButtonText('Remove User')->onlyOnTableRow()
        ];
    }
```

### Step 3: Additional Configuration

- When a action is added to a resource that has an associated authorization policy, the policy's delete method must return false.

```shell
    <?php

	namespace App\Policies;

	use App\Models\Types\Category;
	use App\Models\User;
	use Illuminate\Auth\Access\HandlesAuthorization;

	class CategoryPolicy
	{
	    use HandlesAuthorization;

	    /**
	     * Determine whether the user can view any models.
	     *
	     * @param  \App\Models\User  $user
	     * @return \Illuminate\Auth\Access\Response|bool
	     */
	    public function viewAny(User $user)
	    {
	        return true;
	    }

	    /**
	     * Determine whether the user can view the model.
	     *
	     * @param  \App\Models\User  $user
	     * @param  \App\Models\Category  $category
	     * @return \Illuminate\Auth\Access\Response|bool
	     */
	    public function view(User $user, Category $category)
	    {
	        return true;
	    }

	    /**
	     * Determine whether the user can create models.
	     *
	     * @param  \App\Models\User  $user
	     * @return \Illuminate\Auth\Access\Response|bool
	     */
	    public function create(User $user)
	    {
	        return true;
	    }

	    /**
	     * Determine whether the user can update the model.
	     *
	     * @param  \App\Models\User  $user
	     * @param  \App\Models\Category  $category
	     * @return \Illuminate\Auth\Access\Response|bool
	     */
	    public function update(User $user, Category $category)
	    {
	        return true;
	    }

	    /**
	     * Determine whether the user can delete the model.
	     *
	     * @param  \App\Models\User  $user
	     * @param  \App\Models\Category  $category
	     * @return \Illuminate\Auth\Access\Response|bool
	     */
	    public function delete(User $user, Category $category)
	    {
	        return false;
	    }
	}
```


