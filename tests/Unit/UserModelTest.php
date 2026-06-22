<?php

use App\Models\User;
use App\Models\Subscription;
use App\Models\Task;
use Carbon\Carbon;

test('isAdmin returns true for admin role, false otherwise', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['role' => 'user']);

    expect($admin->isAdmin())->toBeTrue();
    expect($user->isAdmin())->toBeFalse();
});

test('isPro returns true for admin users', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    expect($admin->isPro())->toBeTrue();
});

test('isPro returns true for users with active Pro subscription', function () {
    $proSubscription = Subscription::factory()->create(['name' => 'Pro']);
    $user = User::factory()->create([
        'role' => 'user',
        'subscription_id' => $proSubscription->id,
        'subscription_expires_at' => Carbon::now()->addDays(30),
    ]);

    expect($user->isPro())->toBeTrue();
});

test('isPro returns false for users with expired Pro subscription', function () {
    $proSubscription = Subscription::factory()->create(['name' => 'Pro']);
    $user = User::factory()->create([
        'role' => 'user',
        'subscription_id' => $proSubscription->id,
        'subscription_expires_at' => Carbon::now()->subDay(),
    ]);

    expect($user->isPro())->toBeFalse();
});

test('isPro returns false for users with active Free subscription', function () {
    $freeSubscription = Subscription::factory()->create(['name' => 'Free']);
    $user = User::factory()->create([
        'role' => 'user',
        'subscription_id' => $freeSubscription->id,
        'subscription_expires_at' => Carbon::now()->addDays(30),
    ]);

    expect($user->isPro())->toBeFalse();
});

test('taskCount returns correct count of tasks', function () {
    $user = User::factory()->create();
    expect($user->taskCount())->toBe(0);

    Task::factory()->count(3)->create(['user_id' => $user->id]);

    expect($user->fresh()->taskCount())->toBe(3);
});

test('hasReachedTaskLimit returns true for free users with 10 or more tasks', function () {
    $freeSubscription = Subscription::factory()->create(['name' => 'Free']);
    $user = User::factory()->create([
        'role' => 'user',
        'subscription_id' => $freeSubscription->id,
    ]);

    Task::factory()->count(10)->create(['user_id' => $user->id]);

    expect($user->fresh()->hasReachedTaskLimit())->toBeTrue();
});

test('hasReachedTaskLimit returns false for free users with less than 10 tasks', function () {
    $freeSubscription = Subscription::factory()->create(['name' => 'Free']);
    $user = User::factory()->create([
        'role' => 'user',
        'subscription_id' => $freeSubscription->id,
    ]);

    Task::factory()->count(9)->create(['user_id' => $user->id]);

    expect($user->fresh()->hasReachedTaskLimit())->toBeFalse();
});

test('hasReachedTaskLimit returns false for Pro users regardless of task count', function () {
    $proSubscription = Subscription::factory()->create(['name' => 'Pro']);
    $user = User::factory()->create([
        'role' => 'user',
        'subscription_id' => $proSubscription->id,
        'subscription_expires_at' => Carbon::now()->addDays(30),
    ]);

    Task::factory()->count(15)->create(['user_id' => $user->id]);

    expect($user->fresh()->hasReachedTaskLimit())->toBeFalse();
});

test('canCreateTask returns true when user has not reached task limit', function () {
    $freeSubscription = Subscription::factory()->create(['name' => 'Free']);
    $user = User::factory()->create([
        'role' => 'user',
        'subscription_id' => $freeSubscription->id,
    ]);

    Task::factory()->count(5)->create(['user_id' => $user->id]);

    expect($user->fresh()->canCreateTask())->toBeTrue();
});

test('canCreateTask returns false when user has reached task limit', function () {
    $freeSubscription = Subscription::factory()->create(['name' => 'Free']);
    $user = User::factory()->create([
        'role' => 'user',
        'subscription_id' => $freeSubscription->id,
    ]);

    Task::factory()->count(10)->create(['user_id' => $user->id]);

    expect($user->fresh()->canCreateTask())->toBeFalse();
});
