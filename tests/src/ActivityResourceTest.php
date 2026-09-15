<?php

use Filament\Actions\DeleteAction;
use Filament\Actions\Testing\TestAction;
use Filament\Actions\ViewAction;
use TomatoPHP\FilamentLogger\Filament\Resources\ActivityResource;
use TomatoPHP\FilamentLogger\Filament\Resources\ActivityResource\Pages\ManageActivities;
use TomatoPHP\FilamentLogger\Models\Activity;
use TomatoPHP\FilamentLogger\Tests\Database\Factories\ActivityFactory;
use TomatoPHP\FilamentLogger\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('renders the activity index page', function () {
    get(ActivityResource::getUrl('index'))->assertSuccessful();
});

it('lists activities in the table', function () {
    $activities = ActivityFactory::new()->count(3)->create();

    livewire(ManageActivities::class)
        ->loadTable()
        ->assertCanSeeTableRecords($activities)
        ->assertCountTableRecords(3);
});

it('filters activities by method', function () {
    $get = ActivityFactory::new()->create(['method' => 'GET']);
    $post = ActivityFactory::new()->create(['method' => 'POST']);

    livewire(ManageActivities::class)
        ->loadTable()
        ->filterTable('method', 'POST')
        ->assertCanSeeTableRecords([$post])
        ->assertCanNotSeeTableRecords([$get]);
});

it('shows an activity in the view modal', function () {
    $user = User::factory()->create(['name' => 'Logged Person']);
    $activity = ActivityFactory::new()->create([
        'model_id' => $user->id,
        'model_type' => $user::class,
        'url' => 'http://localhost/admin/visited-page',
    ]);

    livewire(ManageActivities::class)
        ->mountAction(TestAction::make(ViewAction::getDefaultName())->table($activity))
        ->assertMountedActionModalSee(['Logged Person', 'http://localhost/admin/visited-page']);
});

it('deletes an activity from the table', function () {
    $activity = ActivityFactory::new()->create();

    livewire(ManageActivities::class)
        ->callAction(TestAction::make(DeleteAction::getDefaultName())->table($activity));

    assertDatabaseMissing('activities', ['id' => $activity->id]);
});

it('bulk deletes activities', function () {
    $activities = ActivityFactory::new()->count(3)->create();

    livewire(ManageActivities::class)
        ->selectTableRecords($activities)
        ->callAction(TestAction::make('delete')->table()->bulk());

    assertDatabaseCount('activities', 0);
});

it('clears all activities from the header action', function () {
    ActivityFactory::new()->count(5)->create();

    livewire(ManageActivities::class)
        ->callAction('clear')
        ->assertNotified();

    expect(Activity::query()->count())->toBe(0);
});

it('toggles realtime polling from the header action', function () {
    livewire(ManageActivities::class)->callAction('poll');

    expect(session('activity_poll'))->toBe(2000);

    livewire(ManageActivities::class)->callAction('poll');

    expect(session()->has('activity_poll'))->toBeFalse();
});
