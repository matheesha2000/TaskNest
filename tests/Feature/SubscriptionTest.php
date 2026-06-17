<?php

use App\Models\User;
use App\Models\Subscription;

test('subscription page auto-seeds plans and displays them', function () {
    // Start with empty subscriptions
    Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
    Subscription::truncate();
    Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
    expect(Subscription::count())->toBe(0);

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/subscription');

    $response->assertStatus(200);
    $response->assertSee('Free Plan');
    $response->assertSee('Pro Plan');

    // Verify they are now in the DB
    expect(Subscription::count())->toBe(2);
});
