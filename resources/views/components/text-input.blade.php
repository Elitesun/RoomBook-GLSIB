@props(['disabled' => false])
<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-lg border-slate-300 bg-white px-3 py-2 text-sm text-slate-800 shadow-sm placeholder-slate-400 focus:border-blue-500 focus:ring-blue-500 transition-colors']) }}>
