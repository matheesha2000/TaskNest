<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-950">Verify Email</h2>
        <p class="text-sm text-slate-500 mt-1">Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-5 font-semibold text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl p-3.5">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="flex flex-col gap-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button class="w-full justify-center">
                {{ __('Resend Verification Email') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center pt-2">
            @csrf
            <button type="submit" class="underline text-sm text-slate-500 hover:text-slate-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>

