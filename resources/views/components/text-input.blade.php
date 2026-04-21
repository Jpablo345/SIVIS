@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-red-200 bg-white text-zinc-900 focus:border-red-600 focus:ring-red-600 rounded-md shadow-sm transition-colors']) }}>