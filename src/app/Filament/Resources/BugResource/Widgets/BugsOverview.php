<?php

namespace App\Filament\Resources\BugResource\Widgets;

use App\BugSeverity;
use App\BugStatus;
use App\Models\Bug;
use App\Models\BugHistory;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BugsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Open Critical Bugs', Bug::toBase()
                ->where('severity', BugSeverity::CRITICAL)
                ->where('status', BugStatus::OPEN)
                ->count()
            ),
            Stat::make('Bugs reported today', 
                Bug::toBase()
                    ->whereDate('created_at', now()->format('Y-m-d'))
                    ->count()
            ),
            Stat::make('Bugs resolved today', 
                BugHistory::toBase()
                    ->where('description', 'LIKE', '%resolved%')
                    ->whereDate('created_at', now()->format('Y-m-d'))
                    ->count()
            ),
        ];
    }
}
