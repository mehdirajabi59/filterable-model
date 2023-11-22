# Filter Eloquent Models by Array Query Strings

## Introduction

The Laravel Filterable Trait is a powerful package providing a reusable trait for easily implementing filter functionality in your Laravel Eloquent models. It enables filtering query results based on multiple parameters, making it simpler to create dynamic and flexible APIs or data filtering mechanisms in your applications.

## Features

- Easily implement filtering on Eloquent models.
- Filter data based on various query parameters.
- Support for multiple filter types, such as exact matches, partial matches, ranges, etc.
- Customizable and extensible for adding new filter types.
- Improves code readability and maintainability by separating filtering logic from the main model.

## Installation

You can install the package via Composer by running the following command:

```bash
composer require mehdirajabi/filterable
```

## Usage
## 1. Apply the Filterable Trait to Your Eloquent Model

Open the Eloquent model where you want to apply the filtering functionality and add the Filterable trait:

```php
use Mehdirajabi\Filterable\Filterable;

class User extends Model
{
    use Filterable;
}
```

In your controller:

```php
class UserController extends Controller
{
    public function index()
    {
        return User::filter()->actived()->get();
    }
}
```

## 2. How Does Filterable Work?

Two query strings, filterColumns and filterValues, are used, both being arrays. The default operator for all columns is the "like" operator.

For example:

```php
http://test.com/api/user/users?filterColumns[0]=name&filterValues[0]=Alex
```

## 3. Relational Filter

To filter on a relation, add the column name as nested. For example, if a user has a relation with a profile and the profile has a 'sex' column, use the following convention:

```php
use Mehdirajabi\Filterable\Filterable;

class User extends Model
{
    use Filterable;

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }
}
```

```php
http://test.com/api/user/users?filterColumns[0]=profile.sex&filterValues[0]=male
```

## 4. Change Filter Mode Operator

To change the operator of the 'status' column, define the filterMode property:

```php
use Mehdirajabi\Filterable\Operators\FilterEqual;

class User extends Model
{
    protected $filterMode = [
        'status' => FilterEqual::class
    ];
}
```

## 5. Change Operator in Controller

```php
use Mehdirajabi\Filterable\Operators\FilterBetween;

class UserController extends Controller
{
    public function index()
    {
        return User::filter(['id' => FilterBetween::class])->paginate(100);
    }
}
```

## Between Operator

```php
use Mehdirajabi\Filterable\Operators\FilterBetween;

class User extends Model
{
    protected $filterMode = [
        'created_at' => FilterBetween::class
    ];
}
```

```php
http://test.com/users?filterColumns[0]=created_at&filterValues[0]=2023-06-23 00:00:00, 2023-06-24 23:59:59
```
