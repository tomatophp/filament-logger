<?php

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as FoundationEventServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use TomatoPHP\FilamentLogger\Filament\Resources\ActivityResource\Pages\ManageActivities;
use TomatoPHP\FilamentLogger\Models\Activity;
use TomatoPHP\FilamentLogger\Tests\Database\Factories\ActivityFactory;
use TomatoPHP\FilamentLogger\Tests\Models\User;
use TomatoPHP\FilamentLogger\Tests\Models\VerifiableUser;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

it('eager loads the activity causer instead of querying it once per row (#1)', function () {
    actingAs(User::factory()->create());

    User::factory()->count(10)->create()->each(function (User $user): void {
        ActivityFactory::new()->create([
            'model_id' => $user->id,
            'model_type' => $user::class,
        ]);
    });

    $userQueries = 0;

    DB::listen(function ($query) use (&$userQueries): void {
        if (str_contains($query->sql, 'from "users"')) {
            $userQueries++;
        }
    });

    livewire(ManageActivities::class)
        ->loadTable()
        ->assertCanSeeTableRecords(Activity::query()->get());

    expect($userQueries)->toBeLessThan(5);
});

it('clears activities with DELETE, not TRUNCATE, so it works inside a database transaction (#3)', function () {
    actingAs(User::factory()->create());

    ActivityFactory::new()->count(3)->create();

    $statements = [];

    DB::listen(function ($query) use (&$statements): void {
        $statements[] = strtolower($query->sql);
    });

    livewire(ManageActivities::class)
        ->callAction('clear')
        ->assertNotified();

    // MySQL commits implicitly on TRUNCATE, which breaks the transaction Filament wraps actions in
    // ("There is no active transaction"). SQLite compiles truncate() to a sqlite_sequence reset.
    $truncateStatements = collect($statements)
        ->filter(fn (string $sql): bool => str_contains($sql, 'truncate') || str_contains($sql, 'sqlite_sequence'));

    expect(Activity::query()->count())->toBe(0)
        ->and($truncateStatements)->toBeEmpty();
});

it('does not boot a second framework event service provider that re-registers email verification (#5)', function () {
    $packageEventProviders = collect(app()->getProviders(FoundationEventServiceProvider::class))
        ->filter(fn (object $provider): bool => str_starts_with($provider::class, 'TomatoPHP\\FilamentLogger\\'));

    expect($packageEventProviders)->toBeEmpty();

    Notification::fake();

    $user = VerifiableUser::query()->create([
        'name' => 'New Person',
        'email' => 'new.person@example.com',
        'password' => 'secret-password',
    ]);

    event(new Registered($user));

    expect(Notification::sent($user, VerifyEmail::class)->count())->toBeLessThanOrEqual(1);
});
