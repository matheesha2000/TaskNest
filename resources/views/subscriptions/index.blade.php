@extends('layouts.app')
@section('title', 'Subscription Plans')

@section('content')
<div class="max-w-5xl mx-auto py-6">

    {{-- Header --}}
    <div class="text-center mb-12">
        <span class="inline-block bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1 rounded-full mb-3 tracking-wider uppercase">Pricing</span>
        <h2 class="text-4xl font-extrabold text-gray-900">Choose Your Plan</h2>
        <p class="text-gray-500 mt-3 text-lg max-w-xl mx-auto">Start free, upgrade when you're ready. No hidden fees.</p>
    </div>

    {{-- Plans --}}
    @if($plans->isEmpty())
        <div class="text-center py-20">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-700 mb-1">No Plans Available</h3>
            <p class="text-gray-400 text-sm">Subscription plans haven't been configured yet.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
            @foreach($plans as $plan)
                @php
                    $isCurrent = $currentSubscription && $currentSubscription->id === $plan->id;
                    $isPro     = ! $plan->isFree();
                @endphp

                <div class="relative flex flex-col rounded-3xl p-8
                    {{ $isPro
                        ? 'bg-gradient-to-br from-indigo-600 to-indigo-800 text-white shadow-2xl shadow-indigo-300 scale-105'
                        : 'bg-white border border-gray-200 text-gray-800 shadow-md' }}">

                    {{-- Popular Badge --}}
                    @if($isPro)
                        <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                            <span class="bg-amber-400 text-amber-900 text-xs font-bold px-4 py-1.5 rounded-full shadow-md tracking-wide">
                                ⭐ MOST POPULAR
                            </span>
                        </div>
                    @endif

                    {{-- Current Plan Badge --}}
                    @if($isCurrent)
                        <div class="absolute top-5 right-5">
                            <span class="{{ $isPro ? 'bg-white/20 text-white' : 'bg-green-100 text-green-700' }} text-xs font-semibold px-3 py-1 rounded-full">
                                ✓ Current Plan
                            </span>
                        </div>
                    @endif

                    {{-- Plan Icon --}}
                    <div class="mb-5">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center
                            {{ $isPro ? 'bg-white/20' : 'bg-indigo-50' }}">
                            @if($isPro)
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>
                            @endif
                        </div>
                    </div>

                    {{-- Plan Name & Price --}}
                    <h3 class="text-xl font-bold mb-1 {{ $isPro ? 'text-white' : 'text-gray-900' }}">
                        {{ $plan->name }} Plan
                    </h3>

                    <div class="flex items-baseline gap-1 mb-6">
                        @if($plan->isFree())
                            <span class="text-5xl font-extrabold {{ $isPro ? 'text-white' : 'text-gray-900' }}">Free</span>
                            <span class="{{ $isPro ? 'text-indigo-200' : 'text-gray-400' }} text-sm">forever</span>
                        @else
                            <span class="text-5xl font-extrabold text-white">${{ number_format($plan->price, 2) }}</span>
                            <span class="text-indigo-200 text-sm">/ {{ $plan->duration }} days</span>
                        @endif
                    </div>

                    {{-- Divider --}}
                    <div class="border-t {{ $isPro ? 'border-white/20' : 'border-gray-100' }} mb-6"></div>

                    {{-- Features --}}
                    <ul class="space-y-3 mb-8 flex-1">
                        @if(is_array($plan->features) && count($plan->features) > 0)
                            @foreach($plan->features as $feature)
                                <li class="flex items-center gap-3 text-sm {{ $isPro ? 'text-indigo-100' : 'text-gray-600' }}">
                                    <span class="flex-shrink-0 w-5 h-5 rounded-full flex items-center justify-center
                                        {{ $isPro ? 'bg-white/20' : 'bg-green-100' }}">
                                        <svg class="w-3 h-3 {{ $isPro ? 'text-white' : 'text-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </span>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        @else
                            <li class="text-sm {{ $isPro ? 'text-indigo-200' : 'text-gray-400' }} italic">No features listed.</li>
                        @endif
                    </ul>

                    {{-- CTA Button --}}
                    @if($isCurrent)
                        <button disabled
                            class="w-full py-3.5 rounded-2xl font-semibold text-sm cursor-not-allowed
                                {{ $isPro ? 'bg-white/20 text-white/60' : 'bg-gray-100 text-gray-400' }}">
                            ✓ Your Current Plan
                        </button>
                    @elseif($plan->isFree())
                        <button disabled
                            class="w-full py-3.5 rounded-2xl font-semibold text-sm bg-gray-100 text-gray-400 cursor-not-allowed">
                            Default Plan
                        </button>
                    @else
                        <form action="{{ route('subscription.checkout', $plan) }}" method="POST">
                            @csrf
                            <button type="submit"
                               class="w-full py-3.5 rounded-2xl font-bold text-sm text-center transition-all
                                   bg-white text-indigo-700 hover:bg-indigo-50 hover:shadow-lg hover:scale-[1.02] active:scale-100 shadow-md">
                                Upgrade to {{ $plan->name }} →
                            </button>
                        </form>
                        <p class="text-xs text-indigo-200 text-center mt-2">
                            🔒 Secure payment via Stripe
                        </p>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Bottom note --}}
        <p class="text-center text-gray-400 text-sm mt-10">
            All plans include a 30-day billing cycle. Cancel anytime.
        </p>
    @endif

</div>
@endsection