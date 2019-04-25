# laravel-query-filters

A library for applying filters & sorts to a query builder.

*This library is in early release and is pending unit tests.*

## Use Case

A typical use case is that you have a table with form fields which are used to filter or sort
the table data.  The filter & sort values are passed as query string parameters, parsed, and
are then applied to a database query or eloquent model.

## Installation

```bash
composer require balfour/laravel-query-filters
```

## Usage

```php
use App\Models\User;
use Balfour\LaravelQueryFilters\FilterSet;
use Balfour\LaravelQueryFilters\Filters\GreaterThanFilter;
use Balfour\LaravelQueryFilters\Filters\MatchesFilter;
use Balfour\LaravelQueryFilters\Filters\RequiresPermission;
use Balfour\LaravelQueryFilters\Sort;

$filters = [
    // the model field defaults to the key if not specified
    new GreaterThanFilter('age'),
    
    // here, we expect an input parameter 'a' which filters against the 'age' field
    new GreaterThanFilter('a', 'age'),
    
    new MatchesFilter('fname', 'first_name'),
    
    // if the year input isn't present, or is empty, a default filter value of 1987 is used
    new MatchesFilter('year', 'year', 1987),
    
    new MatchesFilter('y', 'year', function () {
        // the default value can also be a closure or callable
        return 1987;
    }),
    
    // here we use a decorator to restrict this filter to users with the 'filter-by-email' permission
    new RequiresPermission(
        new MatchesFilter('email'),
        'filter-by-email',
    ),
    
    // we can force a default value if the user doesn't have permission
    // the user must have the 'filter-by-sales-consultant' permission to filter on this
    // value; otherwise, we force it to their user_id
    new RequiresPermission(
        new MatchesFilter('sales_consultant_id'),
        'filter-by-sales-consultant',
        auth()->user()->id,
    ),
];

$sorts = [
    new Sort('fname', 'first_name'),
    new Sort('fname_desc', 'first_name', 'desc'),
    new Sort('last_name'),
    new Sort('name_asc', ['first_name', 'last_name'], 'asc'),
    new Sort('name_desc', ['first_name', 'last_name'], 'desc'),
];

$set = new FilterSet($filters, $sorts, 'fname');

// the default sort can be the key of a valid sort, an instance of a sort or a callable
// which resolves to an instance of a sort
$defaultSort = new Sort('age');

$set = new FilterSet($filters, $sorts, $defaultSort);
// or
$set = new FilterSet($filters, $sorts, function () {
    return new Sort('age');
});

// you can also apply a query callback to the set
// this callback can be used to manipulate the query after all filters and sorts are applied
$set = new FilterSet($filters, $sorts, 'fname', function ($query) {
    $query->where('is_admin', true);
});

// now let's apply a set of filter params
$params = [
    'age' => 13,
    'fname' => 'Matthew',
    'sales_consultant_id' => 123,
    'sort' => 'last_name',
    'sort_dir' => 'desc',
];
$query = User::query();
$set->apply($query, $params);
$results = $query->get();
// or
$results = $query->paginate();

// we can take the query string parameters directly from a request
$query = User::query();
$set->apply($query, request()->all());
$results = $query->all();
```
