<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-violet-600 hover:from-indigo-600 hover:to-violet-700 text-white font-semibold text-xs uppercase tracking-widest rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:ring-offset-2 focus:ring-offset-white shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/30 transition-all duration-150 ease-in-out cursor-pointer']) }}>
    {{ $slot }}
</button>

