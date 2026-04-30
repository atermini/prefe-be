<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prefe</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-neutral-950 font-sans antialiased">

    <header class="bg-neutral-900 border-b border-neutral-800">
        <div class="max-w-2xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" wire:navigate class="text-xl font-bold text-violet-400 tracking-tight">
                prefe
            </a>
            <nav class="flex items-center gap-3">
                @auth
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="text-sm text-neutral-500 hover:text-neutral-200 transition-colors">
                            Esci
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" wire:navigate
                       class="text-sm text-neutral-400 hover:text-white transition-colors">
                        Accedi
                    </a>
                    <a href="{{ route('register') }}" wire:navigate
                       class="text-sm bg-violet-600 text-white px-4 py-1.5 rounded-full hover:bg-violet-500 transition-colors">
                        Registrati
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="max-w-2xl mx-auto px-4 py-10">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
