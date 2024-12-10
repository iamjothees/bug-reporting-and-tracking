<?php

namespace App\Filament\Widgets;

use App\Models\Bug;
use App\Models\BugHistory;
use Filament\Widgets\ChartWidget;

class ResolvedBugsChart extends ChartWidget
{
    protected static ?string $heading = 'Bugs Resolved';

    protected function getData(): array
    {

        $resolvedBugsData = BugHistory::whereDate('created_at', '>=', now()->subDays(30)->startOfDay())
            ->where('description', 'LIKE', '%resolved%')
            ->get(['id', 'created_at'])
            ->groupBy(function ($bug) {
                return (int) $bug->created_at->format('d');
            })
            ->sortKeys()
            ->map->count();
        return [
            'datasets' => [
                [
                    'label' => 'Bugs Resolved',
                    'data' => $resolvedBugsData,
                    'borderColor' => 'green',
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
