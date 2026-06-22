<?php

use App\Models\User;
use App\Models\Subscription;
use App\Models\Payment;
use Carbon\Carbon;

afterEach(function () {
    Mockery::close();
});

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

test('checkout redirects to Stripe checkout session URL', function () {
    $user = User::factory()->create();
    $plan = Subscription::factory()->create([
        'name' => 'Pro',
        'price' => 9.99,
    ]);

    // Mock Stripe Session create method
    $sessionMock = Mockery::mock('alias:Stripe\Checkout\Session');
    $sessionMock->shouldReceive('create')
        ->once()
        ->andReturn((object)[
            'url' => 'https://checkout.stripe.com/pay/cs_test_mock',
            'id' => 'cs_test_mock'
        ]);

    $response = $this->actingAs($user)->post("/subscription/checkout/{$plan->id}");

    $response->assertRedirect('https://checkout.stripe.com/pay/cs_test_mock');
});

test('checkout success page registers payment and updates user plan to Pro', function () {
    $user = User::factory()->create();
    $plan = Subscription::factory()->create([
        'name' => 'Pro',
        'price' => 9.99,
        'duration' => 30,
    ]);

    // Mock Stripe Session retrieve method
    $sessionMock = Mockery::mock('alias:Stripe\Checkout\Session');
    $sessionMock->shouldReceive('retrieve')
        ->once()
        ->with('cs_test_mock')
        ->andReturn((object)[
            'payment_status' => 'paid',
            'payment_intent' => 'pi_test_success_123',
        ]);

    $response = $this->actingAs($user)->get("/subscription/success?session_id=cs_test_mock&plan={$plan->id}");

    $response->assertRedirect('/dashboard');
    $response->assertSessionHas('success');

    // Verify database updates
    $this->assertDatabaseHas('payments', [
        'user_id' => $user->id,
        'subscription_id' => $plan->id,
        'amount' => 9.99,
        'payment_status' => 'completed',
        'transaction_id' => 'pi_test_success_123',
    ]);

    $user->refresh();
    expect($user->subscription_id)->toBe($plan->id);
    expect($user->subscription_expires_at->isFuture())->toBeTrue();
});

test('stripe webhook checkout.session.completed updates user subscription and stores payment', function () {
    $user = User::factory()->create();
    $plan = Subscription::factory()->create([
        'name' => 'Pro',
        'price' => 9.99,
        'duration' => 30,
    ]);

    // Mock Stripe Webhook event construction
    $webhookMock = Mockery::mock('alias:Stripe\Webhook');
    $webhookMock->shouldReceive('constructEvent')
        ->once()
        ->andReturn((object)[
            'type' => 'checkout.session.completed',
            'data' => (object)[
                'object' => (object)[
                    'metadata' => (object)[
                        'user_id' => $user->id,
                        'subscription_id' => $plan->id,
                    ],
                    'payment_intent' => 'pi_webhook_success_123',
                    'amount_total' => 999, // Stripe stores in cents
                ]
            ]
        ]);

    $response = $this->postJson('/stripe/webhook', [], [
        'Stripe-Signature' => 'test-sig',
    ]);

    $response->assertStatus(200);
    $response->assertJson(['status' => 'ok']);

    // Verify database updates
    $this->assertDatabaseHas('payments', [
        'user_id' => $user->id,
        'subscription_id' => $plan->id,
        'amount' => 9.99,
        'payment_status' => 'completed',
        'transaction_id' => 'pi_webhook_success_123',
    ]);

    $user->refresh();
    expect($user->subscription_id)->toBe($plan->id);
    expect($user->subscription_expires_at->isFuture())->toBeTrue();
});
