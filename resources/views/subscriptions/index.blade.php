@extends('layouts.app')
@section('title', 'Subscription Plans')

@section('content')
<div class="max-w-5xl mx-auto py-6">

    {{-- Header --}}
    <div class="text-center mb-12">
        <span class="inline-block bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1 rounded-full mb-3 tracking-wider uppercase">Pricing</span>
        <h2 class="text-4xl font-extrabold text-gray-900">Choose Your Plan</h2>
        <p class="text-gray-550 mt-3 text-lg max-w-xl mx-auto">Start free, upgrade when you're ready. No hidden fees.</p>
    </div>

    {{-- Plans --}}
    @if($plans->isEmpty())
        <div class="glass-card text-center py-20 rounded-2xl border border-slate-200/60 shadow-sm">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-200">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-700 mb-1">No Plans Available</h3>
            <p class="text-slate-500 text-sm">Subscription plans haven't been configured yet.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start max-w-4xl mx-auto pt-6">
            @foreach($plans as $plan)
                @php
                    $isCurrent = $currentSubscription && $currentSubscription->id === $plan->id;
                    $isPro     = ! $plan->isFree();
                @endphp

                <div class="relative flex flex-col rounded-3xl p-8 h-full transition-all duration-300
                    {{ $isPro
                        ? 'bg-gradient-to-b from-white to-indigo-50/20 border-2 border-indigo-500 shadow-xl shadow-indigo-500/10 scale-105 md:translate-y-[-10px] text-slate-800'
                        : 'glass-card text-slate-850 shadow-sm border border-slate-200/60' }}">

                    {{-- Popular Badge --}}
                    @if($isPro)
                        <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                            <span class="bg-gradient-to-r from-amber-400 to-amber-500 text-amber-950 text-[10px] font-extrabold px-4.5 py-1.5 rounded-full shadow-lg tracking-wider">
                                ⭐ MOST POPULAR
                            </span>
                        </div>
                    @endif

                    {{-- Current Plan Badge --}}
                    @if($isCurrent)
                        <div class="absolute top-5 right-5">
                            <span class="{{ $isPro ? 'bg-indigo-50 text-indigo-600 border border-indigo-150' : 'bg-green-50 text-green-700 border border-green-200/60' }} text-[10px] uppercase tracking-wide font-extrabold px-3 py-1 rounded-full">
                                Active Plan
                            </span>
                        </div>
                    @endif

                    {{-- Plan Icon --}}
                    <div class="mb-6">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center
                            {{ $isPro ? 'bg-indigo-50 border border-indigo-100 text-indigo-650' : 'bg-slate-100 border border-slate-200/80 text-slate-500' }}">
                            @if($isPro)
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>
                            @endif
                        </div>
                    </div>

                    {{-- Plan Name & Price --}}
                    <h3 class="text-xl font-bold mb-1 text-slate-800">
                        {{ $plan->name }} Plan
                    </h3>

                    <div class="flex items-baseline gap-1.5 mb-6">
                        @if($plan->isFree())
                            <span class="text-5xl font-extrabold text-slate-900 tracking-tight">Free</span>
                            <span class="text-slate-500 text-sm font-medium">forever</span>
                        @else
                            <span class="text-5xl font-extrabold text-slate-900 tracking-tight">${{ number_format($plan->price, 2) }}</span>
                            <span class="text-slate-500 text-sm font-medium">/ {{ $plan->duration }} days</span>
                        @endif
                    </div>

                    {{-- Divider --}}
                    <div class="border-t {{ $isPro ? 'border-indigo-100' : 'border-slate-100' }} mb-6"></div>

                    {{-- Features --}}
                    <ul class="space-y-4 mb-8 flex-1">
                        @if(is_array($plan->features) && count($plan->features) > 0)
                            @foreach($plan->features as $feature)
                                <li class="flex items-center gap-3 text-sm text-slate-700">
                                    <span class="flex-shrink-0 w-5 h-5 rounded-full flex items-center justify-center
                                        {{ $isPro ? 'bg-indigo-50 text-indigo-600 border border-indigo-100' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </span>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        @else
                            <li class="text-sm text-slate-500 italic">No features listed.</li>
                        @endif
                    </ul>

                    {{-- CTA Button --}}
                    @if($isCurrent)
                        <button disabled
                            class="w-full py-3.5 rounded-2xl font-bold text-sm cursor-not-allowed border
                                {{ $isPro ? 'bg-indigo-50 border-indigo-200 text-indigo-600' : 'bg-slate-50 border-slate-200 text-slate-450' }}">
                            ✓ Your Current Plan
                        </button>
                    @elseif($plan->isFree())
                        <button disabled
                            class="w-full py-3.5 rounded-2xl font-bold text-sm bg-slate-50 border border-slate-200 text-slate-450 cursor-not-allowed">
                            Default Plan
                        </button>
                    @else
                        <form action="{{ route('subscription.checkout', $plan) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit"
                               class="w-full py-3.5 rounded-2xl font-extrabold text-sm text-center transition-all duration-150
                                   bg-indigo-600 text-white hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-500/15 hover:scale-[1.02] active:scale-100 shadow-md cursor-pointer">
                                Upgrade to {{ $plan->name }} →
                            </button>
                        </form>
                        <p class="text-[10px] text-indigo-600 text-center mt-2.5 font-semibold">
                            🔒 Secure payment via Stripe
                        </p>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Bottom note --}}
        <p class="text-center text-slate-550 text-sm mt-12">
            All plans include a 30-day billing cycle. Cancel or downgrade anytime.
        </p>
    @endif

</div>
@endsection