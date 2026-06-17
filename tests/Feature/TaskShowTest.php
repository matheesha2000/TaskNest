<?php

use App\Models\User;
use App\Models\Task;

test('user can view task details page', function () {
    $user = User::factory()->create();
    $task = Task::create([
        'user_id' => $user->id,
        'title' => 'Important Meeting',
        'description' => 'Discuss project architecture details',
        'status' => 'pending',
    ]);

    $response = $this->actingAs($user)->get("/tasks/{$task->id}");

    $response->assertStatus(200);
    $response->assertSee('Important Meeting');
    $response->assertSee('Discuss project architecture details');
});
