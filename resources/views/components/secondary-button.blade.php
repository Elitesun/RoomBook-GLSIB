<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm ring-1 ring-slate-300 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors']) }}>
    {{ $slot }}
</button>
