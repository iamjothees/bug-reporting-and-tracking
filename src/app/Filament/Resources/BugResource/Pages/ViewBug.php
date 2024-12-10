<?php

namespace App\Filament\Resources\BugResource\Pages;

use App\Filament\Resources\BugResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBug extends ViewRecord
{
    protected static string $resource = BugResource::class;
    protected static string $view = 'filament.resources.bugs.pages.view-bug';

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
