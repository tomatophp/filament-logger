<?php

namespace TomatoPHP\FilamentLogger\Filament\Resources\ActivityResource\Pages;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use TomatoPHP\FilamentLogger\Filament\Resources\ActivityResource;
use TomatoPHP\FilamentLogger\Models\Activity;

class ManageActivities extends ManageRecords
{
    protected static string $resource = ActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clear')
                ->requiresConfirmation()
                ->label(trans('filament-logger::messages.actions.clear.label'))
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->action(function () {
                    Activity::query()->delete();

                    Notification::make()
                        ->title(trans('filament-logger::messages.actions.clear.success.title'))
                        ->body(trans('filament-logger::messages.actions.clear.success.body'))
                        ->success()
                        ->send();
                }),
            Action::make('poll')
                ->requiresConfirmation()
                ->label(trans('filament-logger::messages.actions.poll.label'))
                ->color('primary')
                ->icon(fn () => session()->has('activity_poll') ? 'heroicon-o-x-circle' : 'heroicon-o-check')
                ->action(function () {
                    if (! session()->has('activity_poll')) {
                        session()->put('activity_poll', 2000);

                        Notification::make()
                            ->title(trans('filament-logger::messages.actions.poll.enabled.title'))
                            ->body(trans('filament-logger::messages.actions.poll.enabled.body'))
                            ->success()
                            ->send();
                    } else {
                        session()->forget('activity_poll');

                        Notification::make()
                            ->title(trans('filament-logger::messages.actions.poll.disabled.title'))
                            ->body(trans('filament-logger::messages.actions.poll.disabled.body'))
                            ->success()
                            ->send();
                    }

                    return redirect()->back();
                }),
        ];
    }
}
