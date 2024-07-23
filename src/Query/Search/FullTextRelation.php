<?php

declare(strict_types=1);

namespace Webard\NovaEloquentSearchable\Query\Search;

class FullTextRelation extends Column
{
    /**
     * The relationship name.
     *
     * @var string
     */
    public $relation;

    /**
     * Construct a new search.
     *
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @return void
     */
    public function __construct(string $relation, $column)
    {
        $this->relation = $relation;

        parent::__construct($column);
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
        return $query->{$whereOperator.'Has'}(
            $this->relation,
            function ($query) use ($search) {
                $query->whereFullText(
                    $this->columnName($query),
                    "\"{$search}\""
                );
            }
        );
    }
}
