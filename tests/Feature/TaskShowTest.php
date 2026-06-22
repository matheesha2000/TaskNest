<?php

use App\Models\User;
use App\Models\Task;
use App\Models\Subscription;
use Carbon\Carbon;

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

test('unauthorized user cannot view other users task details page', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $task = Task::factory()->create(['user_id' => $user1->id]);

    $response = $this->actingAs($user2)->get("/tasks/{$task->id}");

    $response->assertStatus(403);
});

test('free users see task list but search and filters are ignored', function () {
    $freeSubscription = Subscription::factory()->create(['name' => 'Free', 'price' => 0]);
    $user = User::factory()->create([
        'role' => 'user',
        'subscription_id' => $freeSubscription->id,
    ]);

    $task1 = Task::factory()->create([
        'user_id' => $user->id,
        'title' => 'First Task',
        'status' => 'completed',
        'priority' => 'medium',
    ]);
    $task2 = Task::factory()->create([
        'user_id' => $user->id,
        'title' => 'Second Task',
        'status' => 'pending',
        'priority' => 'medium',
    ]);

    // Send status filter. It should be ignored and both tasks returned.
    $response = $this->actingAs($user)->get('/tasks?status=completed');

    $response->assertStatus(200);
    $response->assertSee('First Task');
    $response->assertSee('Second Task');
});

test('Pro users can search and filter tasks', function () {
    $proSubscription = Subscription::factory()->create(['name' => 'Pro', 'price' => 9.99]);
    $user = User::factory()->create([
        'role' => 'user',
        'subscription_id' => $proSubscription->id,
        'subscription_expires_at' => Carbon::now()->addDays(30),
    ]);

    $task1 = Task::factory()->create([
        'user_id' => $user->id,
        'title' => 'Meeting with Client',
        'status' => 'completed',
        'priority' => 'high',
        'category' => 'work',
    ]);
    $task2 = Task::factory()->create([
        'user_id' => $user->id,
        'title' => 'Buy groceries',
        'status' => 'pending',
        'priority' => 'low',
        'category' => 'personal',
    ]);

    // Search filter
    $response = $this->actingAs($user)->get('/tasks?search=Meeting');
    $response->assertStatus(200);
    $response->assertSee('Meeting with Client');
    $response->assertDontSee('Buy groceries');

    // Status filter
    $response = $this->actingAs($user)->get('/tasks?status=pending');
    $response->assertStatus(200);
    $response->assertDontSee('Meeting with Client');
    $response->assertSee('Buy groceries');

    // Priority filter
    $response = $this->actingAs($user)->get('/tasks?priority=high');
    $response->assertStatus(200);
    $response->assertSee('Meeting with Client');
    $response->assertDontSee('Buy groceries');

    // Category filter
    $response = $this->actingAs($user)->get('/tasks?category=personal');
    $response->assertStatus(200);
    $response->assertDontSee('Meeting with Client');
    $response->assertSee('Buy groceries');
});

test('free users cannot access create form if they reached limit', function () {
    $freeSubscription = Subscription::factory()->create(['name' => 'Free', 'price' => 0]);
    $user = User::factory()->create([
        'role' => 'user',
        'subscription_id' => $freeSubscription->id,
    ]);

    Task::factory()->count(10)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get('/tasks/create');

    $response->assertRedirect('/tasks');
    $response->assertSessionHas('warning', 'Free plan limit reached. Upgrade to Pro for more tasks.');
});

test('Pro users can access create form even with more than 10 tasks', function () {
    $proSubscription = Subscription::factory()->create(['name' => 'Pro', 'price' => 9.99]);
    $user = User::factory()->create([
        'role' => 'user',
        'subscription_id' => $proSubscription->id,
        'subscription_expires_at' => Carbon::now()->addDays(30),
    ]);

    Task::factory()->count(11)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get('/tasks/create');

    $response->assertStatus(200);
});

test('free users are blocked from storing task when limit reached', function () {
    $freeSubscription = Subscription::factory()->create(['name' => 'Free', 'price' => 0]);
    $user = User::factory()->create([
        'role' => 'user',
        'subscription_id' => $freeSubscription->id,
    ]);

    Task::factory()->count(10)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->post('/tasks', [
        'title' => 'New Task',
        'status' => 'pending',
    ]);

    $response->assertRedirect('/subscription');
    $response->assertSessionHas('warning', 'Upgrade to Pro for unlimited tasks.');
});

test('free users tasks have priority forced to medium and category forced to null', function () {
    $freeSubscription = Subscription::factory()->create(['name' => 'Free', 'price' => 0]);
    $user = User::factory()->create([
        'role' => 'user',
        'subscription_id' => $freeSubscription->id,
    ]);

    $response = $this->actingAs($user)->post('/tasks', [
        'title' => 'Free User Task',
        'status' => 'pending',
        'priority' => 'high',      // Attempt to set high
        'category' => 'shopping',  // Attempt to set category
    ]);

    $response->assertRedirect('/tasks');
    $this->assertDatabaseHas('tasks', [
        'user_id' => $user->id,
        'title' => 'Free User Task',
        'priority' => 'medium',
        'category' => null,
    ]);
});

test('Pro users can save custom priority and category', function () {
    $proSubscription = Subscription::factory()->create(['name' => 'Pro', 'price' => 9.99]);
    $user = User::factory()->create([
        'role' => 'user',
        'subscription_id' => $proSubscription->id,
        'subscription_expires_at' => Carbon::now()->addDays(30),
    ]);

    $response = $this->actingAs($user)->post('/tasks', [
        'title' => 'Pro User Task',
        'status' => 'pending',
        'priority' => 'high',
        'category' => 'work',
    ]);

    $response->assertRedirect('/tasks');
    $this->assertDatabaseHas('tasks', [
        'user_id' => $user->id,
        'title' => 'Pro User Task',
        'priority' => 'high',
        'category' => 'work',
    ]);
});

test('user cannot edit or update other user task', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $task = Task::factory()->create(['user_id' => $user1->id]);

    $responseEdit = $this->actingAs($user2)->get("/tasks/{$task->id}/edit");
    $responseEdit->assertStatus(403);

    $responseUpdate = $this->actingAs($user2)->put("/tasks/{$task->id}", [
        'title' => 'Updated Title',
        'status' => 'pending',
    ]);
    $responseUpdate->assertStatus(403);
});

test('user can update own task details', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create(['user_id' => $user->id, 'title' => 'Old Title']);

    $response = $this->actingAs($user)->put("/tasks/{$task->id}", [
        'title' => 'Updated Title',
        'status' => 'in_progress',
    ]);

    $response->assertRedirect('/tasks');
    expect($task->fresh()->title)->toBe('Updated Title');
    expect($task->fresh()->status)->toBe('in_progress');
});

test('user cannot delete other user task but owner and admin can', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $admin = User::factory()->create(['role' => 'admin']);
    $task = Task::factory()->create(['user_id' => $user->id]);

    // Unauthorized delete
    $this->actingAs($other)->delete("/tasks/{$task->id}")->assertStatus(403);

    // Admin delete
    $this->actingAs($admin)->delete("/tasks/{$task->id}")->assertRedirect('/tasks');
    $this->assertDatabaseMissing('tasks', ['id' => $task->id]);

    // Owner delete
    $task2 = Task::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user)->delete("/tasks/{$task2->id}")->assertRedirect('/tasks');
    $this->assertDatabaseMissing('tasks', ['id' => $task2->id]);
});

test('user can update status via updateStatus endpoint', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

    $response = $this->actingAs($user)->patch("/tasks/{$task->id}/status", [
        'status' => 'completed',
    ]);

    $response->assertStatus(302);
    expect($task->fresh()->status)->toBe('completed');
});

test('updateStatus rejects invalid status', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

    $response = $this->actingAs($user)->patch("/tasks/{$task->id}/status", [
        'status' => 'invalid-status',
    ]);

    $response->assertSessionHasErrors('status');
    expect($task->fresh()->status)->toBe('pending');
});
