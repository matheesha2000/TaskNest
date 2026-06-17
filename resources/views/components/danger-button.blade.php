<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2.5 bg-rose-50 border border-rose-200 hover:bg-rose-600 hover:border-transparent text-rose-700 hover:text-white rounded-xl font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-rose-500/30 transition duration-150 ease-in-out cursor-pointer']) }}>
    {{ $slot }}
</button>

