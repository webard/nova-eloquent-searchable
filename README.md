# Nova Eloquent Searchable

This simple package comes with two solutions:

1. Make ability to search Eloquent models by Laravel Nova way, using their search configuration
2. Introduce additional search classes with optional value validating

## Installation

```sh
composer require webard/nova-eloquent-searchable
```

## Search Eloquent models like Nova resources

1. Move `public static $search` or `public static searchableColumns()` definition from Nova Resource to Model.
2. Add trait to your Nova Resource

```php
use Webard\NovaEloquentSearchable\Trait\NovaEloquentSearch;

class User extends Resource
{
    use NovaEloquentSearch;
}
```

3. Add trait to your model:

```php
use Webard\NovaEloquentSearchable\Trait\EloquentSearchable;

class User extends Authenticatable
{
    use EloquentSearchable;
}
```

Now, you can search your models and return values the same way like in Laravel Nova panel:

```php
$user = User::searchInDatabase('test@example.com')->first();

dump($user->name);
```

To not conflict with Laravel Scout, scope was called `searchInDatabase`, but if you want use other name, e.g. `search`, just rename Trait method:

```php
class User extends Authenticatable
{
    use EloquentSearchable {
        scopeSearchInDatabase as scopeSearch;
    }
}
```

Now, you can search by `search` method:

```php
$user = User::search('test@example.com')->first();

dump($user->name);
```

## Additional search classes

Laravel Nova comes with some [Searchable Columns Classes](https://nova.laravel.com/docs/search/), but they can search only via MySQL's LIKE or full-text search.

Full-text search is powerful, but "like" searches on large datasets are very inefficient and they cannot use indexes, so this package derives several additional classes along with value validation functionality to optimize searches.

### `EqualValue`

```php
use Webard\NovaEloquentSearchable\Query\Search\EqualValue;


public static function searchableColumns(): array
{
    return [
        'name',
        (new EqualValue('email'))
    ];
}
```

`EqualValue` is used to search for full values. This is a simple comparison in MySQL `WHERE column="value"`. This search method is useful, for example, for searching by e-mail address or telephone number.

Additionally, you can add value validation, so that if the passed value does not meet the condition, it will not be added to the search query.

```php
use Webard\NovaEloquentSearchable\Query\Search\EqualValue;

public static function searchableColumns(): array
    {
        return [
            'name',
            (new EqualValue('email'))
                ->validate(fn($value) => Validator::make(
                    [
                        'value' => $value
                    ],
                    [
                        'value' => 'email'
                    ]
                )
                ->passes()
            ),
        ];
    }
```

### `EqualRelation`

`EqualRelation` class do the same thing as `EqualValue`, but basing on relation instead of column.

Example:

```php
use Webard\NovaEloquentSearchable\Query\Search\EqualValue;

public static function searchableColumns(): array
    {
        return [
            'name',
            (new EqualRelation('additionalEmails', 'email'))
                ->validate(fn($value) => Validator::make(
                    [
                        'value' => $value
                    ],
                    [
                        'value' => 'email'
                    ]
                )
                ->passes()
            ),
        ];
    }
```

### `FullTextValue`

`FullTextValue` do the same thing as `SearchableText` class from Laravel Nova, but additionally adds double quote (") characters to search value. So if you search for `John Doe`, to SQL query there is sent `"John Doe"` value. This way, searching in full-text mode is more precise.

Of course value validation like in `EqualValue` is present too.

### `FullTextRelation`

As above, but works with relations instead of columns.

Of course value validation like in `EqualValue` is present too.

## TODO

I'm are actively seeking contributions to enhance this package. Here are some features I would love to see implemented:

- [ ] tests
- [ ] add `validate` method to Laravel Nova searchable classes
- [ ] more searchable classes?

## Contributing

We welcome contributions to improve this plugin! Please follow these steps to contribute:

1. Fork the repository.
2. Create a new branch for your feature or bug fix.
3. Make your changes and commit them with descriptive messages.
4. Push your changes to your forked repository.
5. Open a pull request to the main repository.

## License

This project is licensed under the MIT License. See the [LICENSE.md](LICENSE.md) file for more details.

## Contact

For questions or support, please open an issue on GitHub.
