<?php

namespace Webard\NovaEloquentSearchable\Trait;

use Laravel\Nova\Resource;

/**
 * @mixin Resource
 */
trait NovaEloquentSearch
{
    public static function searchableColumns()
    {
        if (method_exists(static::$model, 'searchableColumns')) {
            return static::$model::searchableColumns();
        }

        return static::$search ?? [static::newModel()->getKeyName()];
    }
}
