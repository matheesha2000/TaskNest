<section class="space-y-6">
    <header class="border-b border-rose-100 pb-4">
        <h2 class="text-xl font-bold text-rose-600">
            {{ __('Delete Account') }}
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            {{ __('Permanently delete your account and all associated data.') }}
        </p>
    </header>

    <div class="p-4 bg-rose-50 border border-rose-150 rounded-xl">
        <p class="text-xs text-rose-800 leading-relaxed font-semibold flex gap-2">
            <svg class="w-4 h-4 text-rose-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span>
                <strong>Warning:</strong> {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </span>
        </p>
    </div>

    <form method="post" action="{{ route('profile.destroy') }}" class="space-y-5">
        @csrf
        @method('delete')

        <div class="space-y-1.5">
            <label for="password" class="text-sm font-semibold text-slate-700">{{ __('Confirm Password') }}</label>
            <input
                id="password"
                name="password"
                type="password"
                class="block w-full rounded-xl border border-slate-200 bg-white text-slate-800 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/15 text-sm shadow-sm transition-all py-2.5 px-3.5"
                placeholder="{{ __('Enter password to confirm') }}"
                required
            />
            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
        </div>

        <div class="pt-2">
            <button type="submit" 
                    class="inline-flex justify-center items-center px-5 py-2.5 bg-rose-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-md hover:bg-rose-700 hover:shadow-lg transition-all hover:scale-[1.02] active:scale-100 cursor-pointer">
                {{ __('Confirm Deletion') }}
            </button>
        </div>
    </form>
</section>
