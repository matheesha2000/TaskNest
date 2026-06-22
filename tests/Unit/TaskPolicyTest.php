<?php

use App\Models\User;
use App\Models\Task;
use App\Policies\TaskPolicy;

test('policy allows owner to view update and delete task', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create(['user_id' => $user->id]);
    $policy = new TaskPolicy();

    expect($policy->view($user, $task))->toBeTrue();
    expect($policy->update($user, $task))->toBeTrue();
    expect($policy->delete($user, $task))->toBeTrue();
});

test('policy prevents other user from viewing updating or deleting task', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $task = Task::factory()->create(['user_id' => $user->id]);
    $policy = new TaskPolicy();

    expect($policy->view($otherUser, $task))->toBeFalse();
    expect($policy->update($otherUser, $task))->toBeFalse();
    expect($policy->delete($otherUser, $task))->toBeFalse();
});

test('policy allows admin to view and delete task but not update it', function () {
    $user = User::factory()->create();
    $admin = User::factory()->create(['role' => 'admin']);
    $task = Task::factory()->create(['user_id' => $user->id]);
    $policy = new TaskPolicy();

    expect($policy->view($admin, $task))->toBeTrue();
    expect($policy->update($admin, $task))->toBeFalse();
    expect($policy->delete($admin, $task))->toBeTrue();
});
