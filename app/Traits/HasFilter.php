<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasFilter
{
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query->when(
            $filters['search'] ?? null,
            function (Builder $query, $search) {
                $query->where(function (Builder $query) use ($search) {
                    foreach ($this->searchable ?? [] as $column) {
                        $query->orWhere(
                            $column,
                            'ILIKE',
                            "%{$search}%"
                        );
                    }
                });
            }
        );
    }
}
