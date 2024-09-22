<?php

namespace TomatoPHP\FilamentLogger\Filament\Resources;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use TomatoPHP\FilamentLogger\Filament\Resources\ActivityResource\Pages;
use TomatoPHP\FilamentLogger\Filament\Resources\ActivityResource\RelationManagers;
use TomatoPHP\FilamentLogger\Models\Activity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
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
                Tables\Columns\TextColumn::make('method')
                    ->label(trans('filament-logger::messages.columns.method'))
                    ->icon('heroicon-o-link')
                    ->badge()
                    ->description(fn($record) => '('.$record->status.') '.str($record->url)->remove(url('/')))
                    ->searchable(),
                Tables\Columns\TextColumn::make('remote_address')
                    ->label(trans('filament-logger::messages.columns.remote_address'))
                    ->description(fn($record) => $record->model?->name)
                    ->icon('heroicon-o-globe-alt')
                    ->searchable(),
                Tables\Columns\TextColumn::make('response_time')
                    ->label(trans('filament-logger::messages.columns.response_time'))
                    ->icon('heroicon-o-clock')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(trans('filament-logger::messages.columns.created_at'))
                    ->description(fn($record) => $record->created_at->diffForHumans())
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(trans('filament-logger::messages.columns.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('method')
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
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageActivities::route('/'),
        ];
    }
}
