<section class="space-y-6">
    <header class="border-b border-slate-200/60 pb-4">
        <h2 class="text-xl font-bold text-rose-600">
            {{ __('Delete Account') }}
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex justify-center items-center px-5 py-2.5 bg-rose-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-md hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 transition-all hover:scale-[1.02] active:scale-100 cursor-pointer"
    >
        {{ __('Delete Account') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 text-slate-800">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-slate-900">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-5 space-y-1.5">
                <label for="password" class="text-sm font-semibold text-slate-700">{{ __('Password') }}</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-full rounded-xl border border-slate-200 bg-white text-slate-800 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/15 text-sm shadow-sm transition-all py-2.5 px-3.5"
                    placeholder="{{ __('Password') }}"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3 border-t border-slate-200/60 pt-4">
                <button type="button" x-on:click="$dispatch('close')" 
                        class="px-4 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl text-sm font-semibold hover:bg-slate-50 hover:text-slate-900 focus:outline-none transition-all cursor-pointer">
                    {{ __('Cancel') }}
                </button>

                <button type="submit" 
                        class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-bold focus:outline-none transition-all cursor-pointer">
                    {{ __('Confirm Deletion') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
