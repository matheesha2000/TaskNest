<?php

use App\Models\User;
use App\Models\Task;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Subscription;

/*
|--------------------------------------------------------------------------
| Access Security
|--------------------------------------------------------------------------
*/

test('non-admin users cannot access admin management routes', function () {
    $user = User::factory()->create(['role' => 'user']);

    $this->actingAs($user)->get('/admin/users')->assertStatus(403);
    $this->actingAs($user)->get('/admin/tasks')->assertStatus(403);
    $this->actingAs($user)->get('/admin/payments')->assertStatus(403);
    $this->actingAs($user)->get('/admin/reviews')->assertStatus(403);
});

/*
|--------------------------------------------------------------------------
| AdminUserController
|--------------------------------------------------------------------------
*/

test('admin can view user list, search and filter users', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $proPlan = Subscription::factory()->create(['name' => 'Pro', 'price' => 9.99]);
    $freePlan = Subscription::factory()->create(['name' => 'Free', 'price' => 0]);

    $userPro = User::factory()->create([
        'name' => 'Pro User',
        'email' => 'pro@example.com',
        'role' => 'user',
        'subscription_id' => $proPlan->id,
    ]);
    $userFree = User::factory()->create([
        'name' => 'Free User',
        'email' => 'free@example.com',
        'role' => 'user',
        'subscription_id' => $freePlan->id,
    ]);

    // Check basic list
    $response = $this->actingAs($admin)->get('/admin/users');
    $response->assertStatus(200);
    $response->assertSee('Pro User');
    $response->assertSee('Free User');

    // Search filter
    $responseSearch = $this->actingAs($admin)->get('/admin/users?search=Pro');
    $responseSearch->assertSee('Pro User');
    $responseSearch->assertDontSee('Free User');

    // Plan filter
    $responsePlan = $this->actingAs($admin)->get('/admin/users?plan=free');
    $responsePlan->assertDontSee('Pro User');
    $responsePlan->assertSee('Free User');
});

test('admin can update user role', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($admin)->patch("/admin/users/{$user->id}/role", [
        'role' => 'admin',
    ]);

    $response->assertRedirect();
    expect($user->fresh()->role)->toBe('admin');
});

test('admin cannot update their own role', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->patch("/admin/users/{$admin->id}/role", [
        'role' => 'user',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error', 'You cannot change your own role.');
    expect($admin->fresh()->role)->toBe('admin');
});

test('admin can delete a user', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($admin)->delete("/admin/users/{$user->id}");

    $response->assertRedirect();
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});

test('admin cannot delete themselves', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->delete("/admin/users/{$admin->id}");

    $response->assertRedirect();
    $response->assertSessionHas('error', 'You cannot delete your own account.');
    $this->assertDatabaseHas('users', ['id' => $admin->id]);
});

test('admin cannot delete another admin', function () {
    $admin1 = User::factory()->create(['role' => 'admin']);
    $admin2 = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin1)->delete("/admin/users/{$admin2->id}");

    $response->assertRedirect();
    $response->assertSessionHas('error', 'Cannot delete an admin account.');
    $this->assertDatabaseHas('users', ['id' => $admin2->id]);
});

/*
|--------------------------------------------------------------------------
| AdminTaskController
|--------------------------------------------------------------------------
*/

test('admin can view and filter tasks', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create();

    $workTask = Task::factory()->create([
        'user_id' => $user->id,
        'title' => 'Project Kickoff',
        'status' => 'pending',
        'priority' => 'high',
    ]);
    $personalTask = Task::factory()->create([
        'user_id' => $user->id,
        'title' => 'Buy Milk',
        'status' => 'completed',
        'priority' => 'low',
    ]);

    // Check basic list
    $response = $this->actingAs($admin)->get('/admin/tasks');
    $response->assertStatus(200);
    $response->assertSee('Project Kickoff');
    $response->assertSee('Buy Milk');

    // Search title or user name
    $responseSearch = $this->actingAs($admin)->get('/admin/tasks?search=Kickoff');
    $responseSearch->assertSee('Project Kickoff');
    $responseSearch->assertDontSee('Buy Milk');

    // Filter by status
    $responseStatus = $this->actingAs($admin)->get('/admin/tasks?status=completed');
    $responseStatus->assertDontSee('Project Kickoff');
    $responseStatus->assertSee('Buy Milk');
});

/*
|--------------------------------------------------------------------------
| AdminPaymentController
|--------------------------------------------------------------------------
*/

test('admin can view and filter payments', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user1 = User::factory()->create(['name' => 'John Doe']);
    $user2 = User::factory()->create(['name' => 'Jane Smith']);
    $plan = Subscription::factory()->create();

    $payment1 = Payment::create([
        'user_id' => $user1->id,
        'subscription_id' => $plan->id,
        'amount' => 9.99,
        'payment_method' => 'stripe',
        'payment_status' => 'completed',
        'transaction_id' => 'tx_123',
        'paid_at' => now(),
    ]);

    $payment2 = Payment::create([
        'user_id' => $user2->id,
        'subscription_id' => $plan->id,
        'amount' => 19.99,
        'payment_method' => 'stripe',
        'payment_status' => 'pending',
        'transaction_id' => 'tx_456',
        'paid_at' => now(),
    ]);

    $response = $this->actingAs($admin)->get('/admin/payments');
    $response->assertStatus(200);
    $response->assertSee('John Doe');
    $response->assertSee('Jane Smith');

    // Filter by completed status
    $responseCompleted = $this->actingAs($admin)->get('/admin/payments?status=completed');
    $responseCompleted->assertSee('John Doe');
    $responseCompleted->assertDontSee('Jane Smith');
});

/*
|--------------------------------------------------------------------------
| AdminReviewController
|--------------------------------------------------------------------------
*/

test('admin can view, filter and delete reviews', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user1 = User::factory()->create(['name' => 'User One']);
    $user2 = User::factory()->create(['name' => 'User Two']);

    $review1 = Review::create([
        'user_id' => $user1->id,
        'rating' => 5,
        'comment' => 'Perfect app!',
    ]);

    $review2 = Review::create([
        'user_id' => $user2->id,
        'rating' => 2,
        'comment' => 'A bit slow.',
    ]);

    $response = $this->actingAs($admin)->get('/admin/reviews');
    $response->assertStatus(200);
    $response->assertSee('Perfect app!');
    $response->assertSee('A bit slow.');

    // Filter by rating
    $responseFilter = $this->actingAs($admin)->get('/admin/reviews?rating=5');
    $responseFilter->assertSee('Perfect app!');
    $responseFilter->assertDontSee('A bit slow.');

    // Delete review
    $responseDelete = $this->actingAs($admin)->delete("/admin/reviews/{$review2->id}");
    $responseDelete->assertRedirect();
    $this->assertDatabaseMissing('reviews', ['id' => $review2->id]);
});
