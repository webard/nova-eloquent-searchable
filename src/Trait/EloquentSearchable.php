<?php

namespace Webard\NovaEloquentSearchable\Trait;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Query\Search;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait EloquentSearchable
{
    public function scopeSearchInDatabase(Builder $query, string $search)
    {
        $search = new Search($query, $search);

        if (method_exists($this, 'searchableColumns')) {
            return $search->handle('', $this->searchableColumns());
        }

        return $search->handle('', static::$search ?? [(new static)->getKeyName()]);
    }
}
