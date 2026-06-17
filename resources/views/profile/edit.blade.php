@extends('layouts.app')
@section('title', 'Profile Settings')

@section('content')
<div class="max-w-5xl mx-auto py-6">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Left Column: Forms --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Update Profile Info --}}
            <div class="p-6 sm:p-8 bg-white shadow-sm rounded-2xl border border-gray-200/80">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Update Password --}}
            <div class="p-6 sm:p-8 bg-white shadow-sm rounded-2xl border border-gray-200/80">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Delete Account --}}
            <div class="p-6 sm:p-8 bg-white shadow-sm rounded-2xl border border-gray-200/80">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>

        {{-- Right Column: Subscription Status Card --}}
        <div class="space-y-6">
            @php
                $user = auth()->user();
                $sub = $user->subscription;
                $isPro = $user->isPro();
            @endphp

            <div class="p-6 bg-white shadow-sm rounded-2xl border border-gray-200/80 relative overflow-hidden">
                {{-- Decorative pattern --}}
                <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50 rounded-full -mr-8 -mt-8 -z-10"></div>
                
                <h3 class="text-lg font-bold text-gray-900 mb-4">Subscription Plan</h3>
                
                @if($sub)
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <span class="text-2xl font-extrabold text-gray-900">{{ $sub->name }}</span>
                            <span class="text-xs text-gray-500 block mt-0.5">Active Plan</span>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                            {{ $isPro ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $isPro ? 'PRO ACTIVE' : 'FREE ACCOUNT' }}
                        </span>
                    </div>

                    <div class="border-t border-gray-100 pt-4 mb-4 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Price</span>
                            <span class="font-semibold text-gray-800">${{ number_format($sub->price, 2) }}</span>
                        </div>
                        
                        @if($isPro)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Expires At</span>
                                <span class="font-semibold text-gray-800">{{ $user->subscription_expires_at->format('M d, Y') }}</span>
                            </div>
                        @else
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Status</span>
                                <span class="font-semibold text-gray-800">No expiration (Free forever)</span>
                            </div>
                        @endif
                    </div>

                    {{-- Features List --}}
                    @if(is_array($sub->features) && count($sub->features) > 0)
                        <div class="bg-gray-50 rounded-xl p-4 mb-4">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Included Features</h4>
                            <ul class="space-y-2">
                                @foreach($sub->features as $feature)
                                    <li class="flex items-center gap-2 text-xs text-gray-600">
                                        <svg class="w-3.5 h-3.5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @else
                    <p class="text-sm text-gray-500 italic mb-4">No active subscription plan.</p>
                @endif

                @if(!$isPro)
                    <a href="{{ route('subscription.index') }}"
                       class="block w-full py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm text-center shadow-md hover:shadow-lg transition-all hover:scale-[1.02] active:scale-100">
                        Upgrade to Pro Plan ⭐
                    </a>
                @else
                    <div class="text-center text-xs text-green-600 font-semibold bg-green-50 rounded-lg py-2">
                        🎉 Enjoying premium features!
                    </div>
                @endif
            </div>

            {{-- Task Usage card --}}
            <div class="p-6 bg-white shadow-sm rounded-2xl border border-gray-200/80">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Task Limitations</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-gray-700">Tasks Created</span>
                        <span class="text-gray-900">{{ $user->taskCount() }} {{ !$isPro ? '/ 10' : '' }}</span>
                    </div>
                    
                    @if(!$isPro)
                        @php
                            $percentage = min(100, ($user->taskCount() / 10) * 100);
                        @endphp
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            Free accounts are limited to 10 tasks. Upgrade to write unlimited tasks.
                        </p>
                    @else
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: 100%"></div>
                        </div>
                        <p class="text-xs text-green-600 mt-2 font-medium">
                            ✓ Unlimited tasks enabled on Pro plan.
                        </p>
                    @endif
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
