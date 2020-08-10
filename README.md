# Laravel Eloquent Filter

A simple Laravel, and Lumen package to filter models, and relations

## Installation

#### Laravel

via composer

```bash
composer require abdallahmohammed/laravel-eloquent-filter:dev-master
```

Edit config/app.php (Skip this step if you are using laravel 5.5+)

```php
LaravelEloquentFilter\Providers\LaravelServiceProvider::class,
```

Copy the package config to your local config

```bash
php artisan vendor:publish --provider="LaravelEloquentFilter\Providers\LaravelServiceProvider"
```

In the `config/laravel-eloquent-filter.php` config file.  Set the namespace your model filters will reside in

```php
'namespace' => "App\\Http\\Filters\\",
```

#### Lumen

>This is only required if you want to use the `php artisan make:filter` command.

In `bootstrap/app.php`

```php
$app->register(LaravelEloquentFilter\Providers\LumenServiceProvider::class);
```

##### Change The Default Namespace

In `bootstrap/app.php`

```php
config(['laravel-eloquent-filter.namespace' => "App\\Http\\Filters\\"]);
```

## Usage

#### Generating the filter

You can create a model filter with the following artisan command

```bash
php artisan make:filter User
```

Where `User` is the Eloquent Model name you are creating the filter for.  This will create `app/Http/Filters/UserFilter.php`

>The command also supports psr-4 namespacing for creating filters.

#### Defining filters

After generating the filter for an Eloquent Model you will find something like that in the filters directory

```php
<?php 

namespace App\Http\Filters;

use LaravelEloquentFilter\BaseFilter;

class UserFilter extends BaseFilter
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = [
        //
    ];
}
```

As you can see there is an protected property called `$filters` there you can define all filters for the Model.

For example:

```php
protected $filters = [
    'first_name',
    'email',
];
```

>Bear in mind that all methods filter must be based on camel case

Now after defining filters, let's define the methods that will filter the query

```php
/**
 * @return \Illuminate\Eloquent\Builder
 */
public function firstName($value) {
    return $this->builder->where('first_name', $value);
}

/**
 * @return \Illuminate\Eloquent\Builder
 */
public function name($value) {
    return $this->builder->where('name', 'like', "%$value%");
}
```

>Side note: the empty values are not ignored by default so if you want to filter only for non-empty values you have to check if the value is not empty, or you can simply change it for the package config file

Now let's imagine that you want to show a user called John even if the filter is not exists you have to do something like that.

```php
public function defaultName($value)
```

This method will be called if the filter called `name` does not exists

#### Applying The Filter To A Model

Implement the `LaravelEloquentFilter\Filterable` trait on any Eloquent Model:

```php
<?php

namespace App;

use LaravelEloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use Filterable;

    ...
}
```

This gives you access to the `filter()` method that accepts a BaseFilter instance:

```php
namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Filters\UserFilter;

class UserController extends Controller
{
    public function index(UserFilter $filter)
    {
        return User::filter($filter)->get();
    }

    ...
}
```

Another example:

```php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Filters\UserFilter;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return User::filter(new UserFilter($request->query()))->get();
    }

    ...
}
```

## Contributing
Any contributions are welcome !!
