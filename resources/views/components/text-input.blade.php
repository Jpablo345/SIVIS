@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-zinc-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200 focus:border-red-600 focus:ring-red-600 rounded-md shadow-sm transition-colors']) }}>