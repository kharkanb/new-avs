<?php

namespace App\Traits;

trait SoftDeleteUnique
{
    public static function uniqueSoftDelete($column, $value, $ignoreId = null)
    {
        $query = static::where($column, $value)->whereNull('deleted_at');
        
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }
        
        return $query->doesntExist();
    }
}