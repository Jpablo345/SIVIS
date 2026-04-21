<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <div class="min-h-screen bg-[var(--sivis-cream)] text-[var(--sivis-ink)]">
            <div class="flex min-h-screen">
                <livewire:layout.navigation />

                <div class="flex min-h-screen flex-1 flex-col">
                    <!-- Page Heading -->
                    @if (isset($header))
                        <header class="sticky top-0 z-30 border-b border-[#e7cfcf] bg-[var(--sivis-cream)]/80 backdrop-blur">
                            <div class="mx-auto w-full max-w-6xl px-6 py-4 lg:px-10">
                                {{ $header }}
                            </div>
                        </header>
                    @endif

                    <!-- Page Content -->
                    <main class="flex-1 px-6 py-6 lg:px-10">
                        <div class="mx-auto w-full max-w-6xl">
                            {{ $slot }}
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
