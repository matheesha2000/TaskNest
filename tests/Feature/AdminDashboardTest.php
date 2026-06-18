<?php

use App\Models\User;

test('non-admin users cannot access the admin dashboard', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get('/admin/dashboard');

    $response->assertStatus(403);
});

test('admin users can access the admin dashboard', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get('/admin/dashboard');

    $response->assertStatus(200);
    $response->assertViewIs('admin.dashboard');
});

test('admin users automatically have pro status and bypass task limits', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    expect($admin->isPro())->toBeTrue();
    expect($admin->hasReachedTaskLimit())->toBeFalse();
});

