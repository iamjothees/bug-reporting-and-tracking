<?php

namespace App\Filament\Widgets;

use App\Models\Bug;
use App\Models\BugHistory;
use Filament\Widgets\ChartWidget;

class ReportedBugsChart extends ChartWidget
{
    protected static ?string $heading = 'Bugs Reported';

    protected function getData(): array
    {
        $reportedBugsData = Bug::whereDate('created_at', '>=', now()->subDays(30)->startOfDay())->get(['id', 'created_at'])->groupBy(function ($bug) {
            return (int) $bug->created_at->format('d');
        })->map->count();

        $resolvedBugsData = BugHistory::whereDate('created_at', '>=', now()->subDays(30)->startOfDay())
            ->where('description', 'LIKE', '%resolved%')
            ->get(['id', 'created_at'])->groupBy(function ($bug) {
                return (int) $bug->created_at->format('d');
            })->map->count();
        return [
            'datasets' => [
                [
                    'label' => 'Bugs Reported',
                    'data' => $reportedBugsData,
                    'borderColor' => '#F59E0B',
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
