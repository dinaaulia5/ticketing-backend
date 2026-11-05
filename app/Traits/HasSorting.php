<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasSorting
{
    public function scopeSorting(Builder $query, array $sorts)
    {
        $query->when(
            ($sorts['field'] ?? null) &&
                ($sorts['direction'] ?? null),

            function ($query) use ($sorts) {
                $query->orderBy(
                    $sorts['field'],
                    $sorts['direction']
                );
            }
        );
    }
}
