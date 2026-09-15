<?php

namespace TomatoPHP\FilamentLogger\Filament\Resources;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use TomatoPHP\FilamentLogger\Filament\Resources\ActivityResource\Pages\ManageActivities;
use TomatoPHP\FilamentLogger\Models\Activity;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-exclamation-triangle';

    public static function getNavigationGroup(): ?string
    {
        return trans('filament-logger::messages.group');
    }

    public static function getNavigationLabel(): string
    {
        return trans('filament-logger::messages.title');
    }

    public static function getPluralLabel(): ?string
    {
        return trans('filament-logger::messages.title');
    }

    public static function getLabel(): ?string
    {
        return trans('filament-logger::messages.single');
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('model.name')
                ->label(trans('filament-logger::messages.columns.model')),
            TextEntry::make('response_time')
                ->numeric()
                ->label(trans('filament-logger::messages.columns.response_time')),
            TextEntry::make('status')
                ->label(trans('filament-logger::messages.columns.status'))
                ->numeric(),
            TextEntry::make('method')
                ->label(trans('filament-logger::messages.columns.method')),
            TextEntry::make('url')
                ->label(trans('filament-logger::messages.columns.url')),
            TextEntry::make('referer')
                ->label(trans('filament-logger::messages.columns.referer')),
            TextEntry::make('query')
                ->label(trans('filament-logger::messages.columns.query')),
            TextEntry::make('remote_address')
                ->label(trans('filament-logger::messages.columns.remote_address')),
            TextEntry::make('user_agent')
                ->label(trans('filament-logger::messages.columns.user_agent'))
                ->columnSpanFull(),
            TextEntry::make('response')
                ->label(trans('filament-logger::messages.columns.response')),
            TextEntry::make('level')
                ->default('info')
                ->label(trans('filament-logger::messages.columns.level')),
            TextEntry::make('user')
                ->label(trans('filament-logger::messages.columns.user')),
            TextEntry::make('log')
                ->label(trans('filament-logger::messages.columns.log')),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->poll(session()->has('activity_poll') ? session('activity_poll') : null)
            ->columns([
                TextColumn::make('method')
                    ->label(trans('filament-logger::messages.columns.method'))
                    ->icon('heroicon-o-link')
                    ->badge()
                    ->description(fn ($record) => '('.$record->status.') '.str($record->url)->remove(url('/')))
                    ->searchable(),
                TextColumn::make('remote_address')
                    ->label(trans('filament-logger::messages.columns.remote_address'))
                    ->description(fn ($record) => $record->model?->name)
                    ->icon('heroicon-o-globe-alt')
                    ->searchable(),
                TextColumn::make('response_time')
                    ->label(trans('filament-logger::messages.columns.response_time'))
                    ->icon('heroicon-o-clock')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(trans('filament-logger::messages.columns.created_at'))
                    ->description(fn ($record) => $record->created_at->diffForHumans())
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label(trans('filament-logger::messages.columns.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('method')
                    ->label(trans('filament-logger::messages.columns.method'))
                    ->searchable()
                    ->options([
                        'GET' => 'GET',
                        'POST' => 'POST',
                        'PUT' => 'PUT',
                        'PATCH' => 'PATCH',
                        'DELETE' => 'DELETE',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('model');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageActivities::route('/'),
        ];
    }
}
