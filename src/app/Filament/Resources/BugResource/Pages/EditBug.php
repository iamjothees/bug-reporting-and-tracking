<?php

namespace App\Filament\Resources\BugResource\Pages;

use App\Filament\Resources\BugResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBug extends EditRecord
{
    protected static string $resource = BugResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }


 
    protected function afterSave(): void
    {
        collect($this->record->getChanges())->each(function ($value, $key) {
            if ($key === 'updated_at' ) return;
            $this->record->histories()->create([
                'description' => "changed $key to $value",
                'updater_id' => auth()->id(),
            ]);
        });
    }
}
