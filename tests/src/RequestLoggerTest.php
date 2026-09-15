<?php

use Illuminate\Support\Facades\Route;
use TomatoPHP\FilamentLogger\Facades\FilamentLogger;
use TomatoPHP\FilamentLogger\Models\Activity;
use TomatoPHP\FilamentLogger\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    Route::middleware('web')->get('/logged-page', fn () => 'ok');
    Route::middleware('web')->get('/ignored/page', fn () => 'ok');
    Route::get('/no-guard-page', fn () => 'ok');
});

it('stores a request that passes through a tracked guard', function () {
    $user = User::factory()->create();

    actingAs($user)->get('/logged-page?foo=bar')->assertOk();

    $activity = Activity::query()->latest('id')->first();

    expect($activity)->not->toBeNull()
        ->and($activity->method)->toBe('GET')
        ->and($activity->status)->toBe(200)
        ->and($activity->url)->toContain('/logged-page')
        ->and($activity->model_id)->toBe($user->id)
        ->and($activity->model_type)->toBe($user::class);
});

it('does not store requests outside the tracked guards', function () {
    get('/no-guard-page')->assertOk();

    expect(Activity::query()->count())->toBe(0);
});

it('does not store excluded paths', function () {
    config()->set('filament-logger.request.excluded-paths', ['ignored/*']);

    get('/ignored/page')->assertOk();

    expect(Activity::query()->count())->toBe(0);
});

it('does not store Livewire 4 update requests unless livewire logging is enabled', function () {
    Route::middleware('web')->post('/livewire-abc123/update', fn () => 'ok');

    $this->withHeader('X-Livewire', 'true')->post('/livewire-abc123/update')->assertOk();

    expect(Activity::query()->count())->toBe(0);

    config()->set('filament-logger.request.livewire', true);

    $this->withHeader('X-Livewire', 'true')->post('/livewire-abc123/update')->assertOk();

    expect(Activity::query()->count())->toBe(1);
});

it('does not store anything when the request logger is disabled', function () {
    config()->set('filament-logger.request.enabled', false);

    get('/logged-page')->assertOk();

    expect(Activity::query()->count())->toBe(0);
});

it('stores a custom log message through the facade', function () {
    FilamentLogger::log(message: 'Something happened', level: 'warning');

    $activity = Activity::query()->latest('id')->first();

    expect($activity->log)->toBe('Something happened')
        ->and($activity->level)->toBe('warning');
});
