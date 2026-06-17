<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-950">Reset Password</h2>
        <p class="text-sm text-slate-500 mt-1">Forgot your password? No problem. Enter your email and we will send you a password reset link.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div class="space-y-1.5">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center">
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>

        <div class="text-center text-sm text-slate-600 pt-4 border-t border-slate-200/60 mt-5">
            Remember your password? 
            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 font-bold hover:underline">
                Sign in
            </a>
        </div>
    </form>
</x-guest-layout>

