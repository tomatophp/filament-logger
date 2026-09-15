<?php

use Illuminate\Support\Facades\Schema;

use function Pest\Laravel\artisan;

it('runs the install command and creates the activities table', function () {
    artisan('filament-logger:install')->assertSuccessful();

    expect(Schema::hasTable('activities'))->toBeTrue();
});
