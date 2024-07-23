<?php

declare(strict_types=1);

namespace Webard\NovaEloquentSearchable\Query\Search;

use Closure;

class EqualValue extends Column
{
    public ?Closure $tester;

    public function __construct($column, ?Closure $tester = null)
    {
        $this->column = $column;
        $this->tester = $tester;
    }

    /**
     * Apply the search.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($query, $search, string $connectionType, string $whereOperator = 'orWhere')
    {
        // @phpstan-ignore-next-line
        $query->{$whereOperator}(
            $this->columnName($query),
            '=',
            $search
        );

    }
}
