<?php

namespace App\Filament\Resources;

use App\BugSeverity;
use App\BugStatus;
use App\Filament\Resources\BugResource\Pages;
use App\Filament\Resources\BugResource\RelationManagers;
use App\Models\Bug;
use App\Models\User;
use App\UserRole;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class BugResource extends Resource
{
    protected static ?string $model = Bug::class;

    protected static ?string $navigationLabel = 'All Bugs';

    protected static ?string $navigationGroup = 'Bugs';

    public static function form(Form $form): Form
    {
        $users = User::all();
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull()
                    ->rows(7),
                Forms\Components\Select::make('severity')
                    ->options(BugSeverity::class)
                    ->default(BugSeverity::MEDIUM)
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options(BugStatus::class)
                    ->default(BugStatus::OPEN)
                    ->required(),
                Forms\Components\Select::make('reporter_id')->label('Reporter')
                    ->visibleOn('create')
                    ->disabled(Auth::user()->role !== UserRole::ADMIN)
                    ->options($users->where('role', UserRole::REPORTER)->pluck('name', 'id'))
                    ->default(Auth::user()->id)
                    ->required(),
                Forms\Components\Select::make('assignee_id')->label('Assignee')
                    ->visibleOn('create')
                    ->options($users->where('role', UserRole::DEVELOPER)->pluck('name', 'id')),
                Forms\Components\Hidden::make('reporter_id')->visible(Auth::user()->role === UserRole::REPORTER)->default(Auth::user()->id),
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('severity')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('reporter.name')
                    ->label('Reported By')
                    ->searchable(),
                Tables\Columns\TextColumn::make('assignee.name')
                    ->label('Assigned To')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('severity')
                    ->options(BugSeverity::class),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        $assign = Action::make('Assign')
            ->label(fn (Bug $bug) => !$bug->assignee_id ? "Assign" : "Re-assign")
            ->form([
                Forms\Components\Select::make('assignee_id')
                    ->label(fn (Bug $bug) => !$bug->assignee_id ? "Assign to" : "Re-assign To")
                    ->options(User::all()->where('role', UserRole::DEVELOPER)->pluck('name', 'id'))
                    ->required(),
            ])
            ->action(function (array $data, Bug $bug) {
                $bug->update([
                    'assignee_id' => $data['assignee_id']
                ]);
                Notification::make('assignee-updated')->success()->title("Assignee updated")->send();
            })
            ->modalWidth('md');
        return $infolist
                ->schema([
                    Infolists\Components\TextEntry::make('reporter.name')
                        ->label('Reported By')
                        ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                        ->weight(FontWeight::Bold),
                    Infolists\Components\TextEntry::make('assignee.name')
                        ->hintAction($assign)
                        ->label('Assigned To')
                        ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                        ->weight(FontWeight::Bold),

                    
                    Infolists\Components\TextEntry::make('title')
                        ->label('Title')
                        ->columnSpanFull(),
                    Infolists\Components\Group::make([
                        Infolists\Components\TextEntry::make('severity')
                            ->badge()
                            ->label('')
                            ->formatStateUsing(fn ($state) => str($state->value)->title())
                            ->color(fn ($record) => match($record->severity) {
                                BugSeverity::LOW => 'success',
                                BugSeverity::MEDIUM => 'warning',
                                BugSeverity::HIGH => 'danger',
                                BugSeverity::CRITICAL => 'primary',
                            }),
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->label('')
                            ->color(fn ($record) => match($record->status) {
                                BugStatus::OPEN => 'danger',
                                BugStatus::IN_PROGRESS => 'warning',
                                BugStatus::RESOLVED => 'success',
                                BugStatus::CLOSED => 'secondary',
                                default => 'gray',
                            }),
                    ])->columns(12)->columnSpanFull(),
                    Infolists\Components\TextEntry::make('description')
                        ->label('Description')
                        ->columnSpanFull(),
                    
                ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBugs::route('/'),
            'create' => Pages\CreateBug::route('/create'),
            'view' => Pages\ViewBug::route('/{record}'),
            'edit' => Pages\EditBug::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->when(request()->has('severity'), function (Builder $query) {
                return $query->where('severity', request('severity'));
            })
            ->when(
                Auth::user()->role === UserRole::DEVELOPER,
                fn (Builder $query) => 
                    $query->where(
                        fn ($q) => $q->where('assignee_id', Auth::user()->id)->orWhereNull('assignee_id')
                    )
            )
            ->when(
                Auth::user()->role === UserRole::REPORTER,
                fn (Builder $query) => $query->where('reporter_id', Auth::user()->id)
            )
            ;
    }
}
