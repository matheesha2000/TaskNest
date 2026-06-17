<section class="space-y-6">
    <header class="border-b border-gray-100 pb-4">
        <h2 class="text-xl font-bold text-gray-950">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div class="space-y-1.5">
            <label for="name" class="text-sm font-semibold text-gray-700">{{ __('Name') }}</label>
            <input id="name" name="name" type="text" 
                   class="block w-full rounded-xl border-gray-200 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500/20 text-sm shadow-sm transition-all py-2.5 px-3.5" 
                   value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="space-y-1.5">
            <label for="email" class="text-sm font-semibold text-gray-700">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" 
                   class="block w-full rounded-xl border-gray-200 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500/20 text-sm shadow-sm transition-all py-2.5 px-3.5" 
                   value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3 bg-amber-50 rounded-xl border border-amber-200">
                    <p class="text-xs text-amber-800 flex items-center gap-1.5 font-medium">
                        <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification" class="underline hover:text-amber-950 font-bold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                            {{ __('Click here to re-send.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1.5 text-xs text-green-700 font-semibold">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="inline-flex justify-center items-center px-4 py-2.5 bg-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all hover:scale-[1.02] active:scale-100">
                {{ __('Save Details') }}
            </button>

            @if (session('status') === 'profile-updated')
                <div x-data="{ show: true }"
                     x-show="show"
                     x-transition
                     x-init="setTimeout(() => show = false, 2500)"
                     class="flex items-center gap-1.5 text-sm text-green-600 font-semibold bg-green-50 px-3 py-1.5 rounded-lg border border-green-100">
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ __('Saved successfully') }}</span>
                </div>
            @endif
        </div>
    </form>
</section>
