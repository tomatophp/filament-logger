<?php

namespace TomatoPHP\FilamentLogger\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use TomatoPHP\FilamentLogger\Models\Activity;

/**
 * @extends Factory<Activity>
 */
class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition(): array
    {
        return [
            'request_hash' => Str::random(10),
            'http_version' => '1.1',
            'response_time' => $this->faker->randomFloat(3, 0, 2),
            'status' => 200,
            'method' => $this->faker->randomElement(['GET', 'POST', 'PUT', 'PATCH', 'DELETE']),
            'url' => $this->faker->url(),
            'referer' => $this->faker->url(),
            'query' => [],
            'remote_address' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
            'level' => 'info',
        ];
    }
}
