<?php

namespace App\Filament\Resources\BugResource\Pages;

use App\Filament\Resources\BugResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBug extends CreateRecord
{
    protected static string $resource = BugResource::class;

    protected function afterCreate(): void
    {
        $this->record->histories()->create([
            'description' => 'created this bug',
            'updater_id' => auth()->id(),
        ]);
    }
}
