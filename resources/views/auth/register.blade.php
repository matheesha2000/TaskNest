<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">Create your account</h2>
        <p class="text-sm text-gray-500 mt-1">Join TaskNest today to manage your daily tasks.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div class="space-y-1">
            <label for="name" class="text-sm font-semibold text-gray-700">Full Name</label>
            <input id="name" type="text" name="name" 
                   class="block w-full rounded-xl border border-gray-200 bg-white text-gray-950 focus:border-indigo-500 focus:ring focus:ring-indigo-500/20 text-sm py-2.5 px-3.5 shadow-sm transition-all" 
                   value="{{ old('name') }}" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div class="space-y-1">
            <label for="email" class="text-sm font-semibold text-gray-700">Email Address</label>
            <input id="email" type="email" name="email" 
                   class="block w-full rounded-xl border border-gray-200 bg-white text-gray-950 focus:border-indigo-500 focus:ring focus:ring-indigo-500/20 text-sm py-2.5 px-3.5 shadow-sm transition-all" 
                   value="{{ old('email') }}" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="space-y-1">
            <label for="password" class="text-sm font-semibold text-gray-700">Password</label>
            <div class="relative" x-data="{ showPassword: false }">
                <input id="password" :type="showPassword ? 'text' : 'password'" name="password" 
                       class="block w-full rounded-xl border border-gray-200 bg-white text-gray-950 focus:border-indigo-500 focus:ring focus:ring-indigo-500/20 text-sm py-2.5 pl-3.5 pr-10 shadow-sm transition-all" 
                       required autocomplete="new-password" />
                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center focus:outline-none text-gray-400 hover:text-indigo-600 transition-colors">
                    <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-indigo-600 hover:text-indigo-800" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.822 7.822L21 21m-2.228-2.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-1">
            <label for="password_confirmation" class="text-sm font-semibold text-gray-700">Confirm Password</label>
            <div class="relative" x-data="{ showPassword: false }">
                <input id="password_confirmation" :type="showPassword ? 'text' : 'password'" name="password_confirmation" 
                       class="block w-full rounded-xl border border-gray-200 bg-white text-gray-950 focus:border-indigo-500 focus:ring focus:ring-indigo-500/20 text-sm py-2.5 pl-3.5 pr-10 shadow-sm transition-all" 
                       required autocomplete="new-password" />
                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center focus:outline-none text-gray-400 hover:text-indigo-600 transition-colors">
                    <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-indigo-600 hover:text-indigo-800" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.822 7.822L21 21m-2.228-2.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="pt-2">
            <button type="submit" 
                    class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-indigo-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all hover:scale-[1.02] active:scale-100">
                Register
            </button>
        </div>

        <div class="text-center text-sm text-gray-500 pt-2 border-t border-gray-100 mt-4">
            Already registered? 
            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 font-bold hover:underline">
                Sign in instead
            </a>
        </div>
    </form>
</x-guest-layout>
