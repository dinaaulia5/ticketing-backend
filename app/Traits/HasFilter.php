<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasFilter
{

    public function scopeFilter(Builder $query, array $filters)
    {
        // dd($filters);
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                foreach ($this->searchable ?? [] as $column) {
                    $query->orWhere($column, 'ilike', "%{$search}%");
                }
            });
        });
    }
}
