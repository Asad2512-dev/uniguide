<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

trait PaginatesCatalog
{
    private function catalogPage(Builder $query, int $perPage, int $page, callable $present): array
    {
        $perPage = max(1, min(500, $perPage));
        $total = (clone $query)->count();
        $pages = max(1, (int) ceil($total / $perPage));
        $page = max(1, min($page, $pages));
        $items = $query->forPage($page, $perPage)->get();

        return ['data' => $items->map($present)->all(), 'total' => $total, 'per_page' => $perPage,
            'current_page' => $page, 'total_pages' => $pages,
            'from' => $total ? ($page - 1) * $perPage + 1 : 0, 'to' => min($page * $perPage, $total)];
    }
}
