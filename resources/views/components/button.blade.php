<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2.5 border-transparent rounded-md font-semibold text-xs text-white font-bold uppercase tracking-widest bg-gradient-to-r from-blue-400 to-blue-500 hover:from-blue-300 hover:to-blue-400 focus:outline-none disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
