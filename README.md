Filter eloquent model by array query strings

**Introduction**

Laravel Filterable Trait is a powerful package that provides a reusable trait for easily implementing filter functionality in your Laravel Eloquent models. It allows you to filter query results based on multiple parameters, making it easier to create dynamic and flexible APIs or data filtering mechanisms in your applications.

**Features**

Easily implement filtering on Eloquent models.
Filter data based on various query parameters.
Support for multiple filter types, such as exact matches, partial matches, ranges, etc.
Customizable and extensible for adding new filter types.
Improves code readability and maintainability by separating filtering logic from the main model.

**Installation**

You can install the package via Composer by running the following command:

`composer require mehdirajabi/filterable`

**Usage**

**1**. **Apply the Filterable Trait to your Eloquent Model**


   Open the Eloquent model where you want to apply the filtering functionality and add the Filterable trait:

```php
use  Mehdirajabi\Filterable\Filterable;;

class User extends Model
{
    use Filterable;

    
}
```

```php
class UserController extends Controller
{
    public function index()
    {
        return User::filter()->actived()->get();
    }
}
```

**2**. **How to Filterable work ?**

We have two queries string, filterColumns and filterValues both are array.
As default operator for all columns is like operator

`http://test.com/api/user/users?filterColumns[0]=columnName&filterValues[0]=Value`

**For example**:

`http://test.com/api/user/users?filterColumns[0]=name&filterValues[0]=Alex`

**3. Relational filter**

To filter on relation you must add name column as nested for example user has relation with profile and profile has sex column for do this use below convention:
`http://test.com/api/user/users?filterColumns[0]=profile.sex&filterValues[0]=male`

**4. Change filter mode operator**

To change operator of status column you must define filterMode property: 
```php
use Mehdirajabi\Filterable\Operators\FilterEqual;

class User extends Model
{
        protected $filterMode = [
        'status' => FilterEqual::class
    ];
}
```


**5.Change operator in controller**

```php
use Mehdirajabi\Filterable\Operators\FilterBetween;

class UserController extends Controller
{
    public function index()
    {
        return User::filter(['id' => FilterBetween::class])->paginate(100);
    }
```

**Between Operator**

```php

use Mehdirajabi\Filterable\Operators\FilterBetween;

class User extends Model
{
        protected $filterMode = [
        'created_at' => FilterBetween::class
    ];
}
```


`http://test.com/users?filterColumns[0]=created_at&filterValues[0]=2023-06-23,2023-06-24,23:59:59
`



