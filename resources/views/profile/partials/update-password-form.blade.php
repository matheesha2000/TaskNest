<section class="space-y-6">
    <header class="border-b border-gray-100 pb-4">
        <h2 class="text-xl font-bold text-gray-950">
            {{ __('Update Password') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')

        <div class="space-y-1.5">
            <label for="update_password_current_password" class="text-sm font-semibold text-gray-700">{{ __('Current Password') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" 
                   class="block w-full rounded-xl border-gray-200 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500/20 text-sm shadow-sm transition-all py-2.5 px-3.5" 
                   autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="space-y-1.5">
            <label for="update_password_password" class="text-sm font-semibold text-gray-700">{{ __('New Password') }}</label>
            <input id="update_password_password" name="password" type="password" 
                   class="block w-full rounded-xl border-gray-200 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500/20 text-sm shadow-sm transition-all py-2.5 px-3.5" 
                   autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div class="space-y-1.5">
            <label for="update_password_password_confirmation" class="text-sm font-semibold text-gray-700">{{ __('Confirm Password') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                   class="block w-full rounded-xl border-gray-200 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500/20 text-sm shadow-sm transition-all py-2.5 px-3.5" 
                   autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="inline-flex justify-center items-center px-4 py-2.5 bg-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all hover:scale-[1.02] active:scale-100">
                {{ __('Update Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <div x-data="{ show: true }"
                     x-show="show"
                     x-transition
                     x-init="setTimeout(() => show = false, 2500)"
                     class="flex items-center gap-1.5 text-sm text-green-600 font-semibold bg-green-50 px-3 py-1.5 rounded-lg border border-green-100">
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ __('Password updated successfully') }}</span>
                </div>
            @endif
        </div>
    </form>
</section>
