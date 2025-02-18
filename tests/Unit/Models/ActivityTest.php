<?php

declare(strict_types=1);

use App\Models\Activity;
use App\Models\Project;
use Pest\Expectation;

test('to array', function () {
    $activity = Activity::factory()->create()->refresh();

    expect(array_keys($activity->toArray()))
        ->toBe([
            'id',
            'project_id',
            'events',
            'created_at',
            'updated_at',
        ]);
});

it('belongs to a project', function () {
    $activity = Activity::factory()->create();

    expect($activity->project)->toBeInstanceOf(Project::class);
});

it('casts events to array', function () {
    $activity = Activity::factory()->create();

    expect($activity->events)->toBeArray()->toHaveCount(2)
        ->sequence(
            fn (Expectation $event) => $event->toBe([
                'type' => 'view',
                'payload' => [
                    'url' => '/about',
                ],
            ]),
            fn (Expectation $event) => $event->toBe([
                'type' => 'view_duration',
                'payload' => [
                    'url' => '/about',
                    'seconds' => '2',
                ],
            ]),
        );
});
