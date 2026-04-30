<div class="max-w-md mx-auto">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-white">Bentornato</h1>
        <p class="mt-2 text-neutral-400">Accedi per rispondere alla domanda di oggi</p>
    </div>

    <div class="bg-neutral-900 rounded-2xl border border-neutral-800 p-8">
        <form wire:submit="login" class="space-y-5">
            <div>
                <label for="email" class="block text-sm font-medium text-neutral-300 mb-1.5">Email</label>
                <input type="email" id="email" wire:model="email" autocomplete="email"
                       class="w-full rounded-xl bg-neutral-800 border border-neutral-700 px-4 py-2.5 text-white placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-neutral-300 mb-1.5">Password</label>
                <input type="password" id="password" wire:model="password" autocomplete="current-password"
                       class="w-full rounded-xl bg-neutral-800 border border-neutral-700 px-4 py-2.5 text-white placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" id="remember" wire:model="remember"
                       class="rounded border-neutral-600 bg-neutral-800 text-violet-600 focus:ring-violet-500 focus:ring-offset-neutral-900">
                <label for="remember" class="text-sm text-neutral-400">Ricordami</label>
            </div>

            <button type="submit"
                    class="w-full bg-violet-600 text-white font-semibold py-2.5 rounded-xl hover:bg-violet-500 transition-colors focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 focus:ring-offset-neutral-900">
                <span wire:loading.remove>Accedi</span>
                <span wire:loading>Caricamento...</span>
            </button>
        </form>
    </div>

    <p class="text-center mt-6 text-sm text-neutral-500">
        Non hai un account?
        <a href="{{ route('register') }}" wire:navigate class="text-violet-400 font-medium hover:text-violet-300">Registrati</a>
    </p>
</div>
