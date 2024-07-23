<?php

namespace Webard\NovaEloquentSearchable\Query\Search;

use Closure;
use Laravel\Nova\Query\Search\Column as NovaColumn;

abstract class Column extends NovaColumn
{
    protected ?Closure $validator = null;

    public function validate(Closure $validator)
    {
        $this->validator = $validator;

        return $this;
    }

    abstract protected function apply($query, $search, string $connectionType, string $whereOperator = 'orWhere');

    public function __invoke($query, $search, string $connectionType, string $whereOperator = 'orWhere')
    {
        if (is_callable($this->validator) && ($this->validator)($search) === false) {
            return $query;
        }

        return $this->apply($query, $search, $connectionType, $whereOperator);
    }
}
