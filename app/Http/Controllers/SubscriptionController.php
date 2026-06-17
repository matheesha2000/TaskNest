<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Webhook;
use App\Models\User;

class SubscriptionController extends Controller
{
    public function index()
    {
        $plans = Subscription::all();
        if ($plans->count() < 2) {
            \Illuminate\Support\Facades\Artisan::call('db:seed');
            $plans = Subscription::all();
        }
        $currentSubscription = Auth::user()->subscription;

        // If the user doesn't have a subscription assigned yet (e.g. registered before seed), assign Free
        if (Auth::user() && !Auth::user()->subscription_id) {
            $freePlan = $plans->where('name', 'Free')->first();
            if ($freePlan) {
                Auth::user()->update(['subscription_id' => $freePlan->id]);
                $currentSubscription = $freePlan;
            }
        }

        // FIX: corrected view name (plural)
        return view('subscriptions.index', compact('plans', 'currentSubscription'));
    }

    public function checkout(Request $request, Subscription $subscription)
    {
        if (Auth::user()->subscription_id === $subscription->id) {
            return back()->with('info', 'You are already on this plan.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $subscription->name . ' Plan',
                    ],
                    'unit_amount' => (int) ($subscription->price * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('subscription.success')
                . '?session_id={CHECKOUT_SESSION_ID}&plan='
                . $subscription->id,
            'cancel_url' => route('subscription.index'),
            'metadata' => [
                'user_id' => Auth::id(),
                'subscription_id' => $subscription->id,
            ],
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $sessionId = $request->query('session_id');
        $subscriptionId = $request->query('plan');

        if (!$sessionId) {
            return redirect()
                ->route('subscription.index')
                ->with('error', 'Invalid session.');
        }

        $stripeSession = StripeSession::retrieve($sessionId);

        if (($stripeSession->payment_status ?? null) !== 'paid') {
            return redirect()
                ->route('subscription.index')
                ->with('error', 'Payment was not successful.');
        }

        $user = Auth::user();
        $subscription = Subscription::findOrFail($subscriptionId);

        $exists = Payment::where('transaction_id', $stripeSession->payment_intent)->exists();

        if (!$exists) {
            Payment::create([
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'amount' => $subscription->price,
                'payment_method' => 'stripe',
                'payment_status' => 'completed',
                'transaction_id' => $stripeSession->payment_intent,
                'paid_at' => now(),
            ]);

            $user->update([
                'subscription_id' => $subscription->id,
                'subscription_expires_at' => $subscription->isFree() ? null : now()->addDays($subscription->duration ?? 30),
            ]);
        }

        return redirect()
            ->route('dashboard')
            ->with('success', '🎉 You are now on the ' . $subscription->name . ' plan!');
    }

    public function history()
    {
        $payments = Auth::user()
            ->payments()
            ->with('subscription')
            ->latest('paid_at')
            ->paginate(10);

        return view('payments.history', compact('payments'));
    }

    public function webhook(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $userId = $session->metadata->user_id;
            $subscriptionId = $session->metadata->subscription_id;

            $exists = Payment::where('transaction_id', $session->payment_intent)->exists();

            if (!$exists) {
                Payment::create([
                    'user_id' => $userId,
                    'subscription_id' => $subscriptionId,
                    'amount' => $session->amount_total / 100,
                    'payment_method' => 'stripe',
                    'payment_status' => 'completed',
                    'transaction_id' => $session->payment_intent,
                    'paid_at' => now(),
                ]);

                $subscription = Subscription::find($subscriptionId);
                User::where('id', $userId)
                    ->update([
                        'subscription_id' => $subscriptionId,
                        'subscription_expires_at' => ($subscription && !$subscription->isFree()) 
                            ? now()->addDays($subscription->duration ?? 30) 
                            : null,
                    ]);
            }
        }

        return response()->json(['status' => 'ok']);
    }
}