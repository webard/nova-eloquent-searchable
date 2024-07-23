<?php

declare(strict_types=1);

namespace Webard\NovaEloquentSearchable\Query\Search;

class FullTextValue extends Column
{
    /**
     * Apply the search.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($query, $search, string $connectionType, string $whereOperator = 'orWhere')
    {
        if (in_array($connectionType, ['mysql', 'pgsql'])) {
            $query->{$whereOperator.'FullText'}(
                $this->columnName($query),
                "\"{$search}\""
            );
        }

        return $query;
    }
}
