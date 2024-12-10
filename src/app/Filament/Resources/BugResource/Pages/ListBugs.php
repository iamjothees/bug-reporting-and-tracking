<?php

namespace App\Filament\Resources\BugResource\Pages;

use App\BugStatus;
use App\Filament\Resources\BugResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Htmlable;

class ListBugs extends ListRecords
{
    protected static string $resource = BugResource::class;

    public function getTitle(): string | Htmlable{
        return request()->has('severity') ? (str(request('severity'))->title. ' Priority Bugs') : "Bugs";
    }

    // protected static ?string $title = 

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return 
            [
                'all' => Tab::make('All'),
                
                ...collect(BugStatus::cases())
                    ->flatMap(fn ($status) => [
                        $status->label() => 
                            Tab::make($status->label())
                                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', $status)),
                    ])
                    ->toArray(),
            ];
    }
}
