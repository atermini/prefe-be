<div class="max-w-md mx-auto">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-white">Crea un account</h1>
        <p class="mt-2 text-neutral-400">Ti serve un codice invito per registrarti</p>
    </div>

    <div class="bg-neutral-900 rounded-2xl border border-neutral-800 p-8">
        <form wire:submit="register" class="space-y-5">
            <div>
                <label for="inviteCode" class="block text-sm font-medium text-neutral-300 mb-1.5">Codice invito</label>
                <input type="text" id="inviteCode" wire:model="inviteCode"
                       placeholder="XXXX-XXXX"
                       class="w-full rounded-xl bg-neutral-800 border border-neutral-700 px-4 py-2.5 text-white placeholder-neutral-500 uppercase tracking-widest font-mono focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent @error('inviteCode') border-red-500 @enderror">
                @error('inviteCode')
                    <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name" class="block text-sm font-medium text-neutral-300 mb-1.5">Username</label>
                <input type="text" id="name" wire:model="name" autocomplete="username"
                       class="w-full rounded-xl bg-neutral-800 border border-neutral-700 px-4 py-2.5 text-white placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-neutral-300 mb-1.5">Password</label>
                <input type="password" id="password" wire:model="password" autocomplete="new-password"
                       class="w-full rounded-xl bg-neutral-800 border border-neutral-700 px-4 py-2.5 text-white placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-neutral-300 mb-1.5">Conferma password</label>
                <input type="password" id="password_confirmation" wire:model="password_confirmation" autocomplete="new-password"
                       class="w-full rounded-xl bg-neutral-800 border border-neutral-700 px-4 py-2.5 text-white placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent">
            </div>

            <button type="submit"
                    class="w-full bg-violet-600 text-white font-semibold py-2.5 rounded-xl hover:bg-violet-500 transition-colors focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 focus:ring-offset-neutral-900">
                <span wire:loading.remove>Registrati</span>
                <span wire:loading>Caricamento...</span>
            </button>
        </form>
    </div>

    <p class="text-center mt-6 text-sm text-neutral-500">
        Hai già un account?
        <a href="{{ route('login') }}" wire:navigate class="text-violet-400 font-medium hover:text-violet-300">Accedi</a>
    </p>
</div>
