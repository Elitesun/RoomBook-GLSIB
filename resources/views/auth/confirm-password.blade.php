<x-guest-layout>
    <h2 class="text-xl font-semibold text-slate-800 mb-1">Confirmation requise</h2>
    <p class="text-sm text-slate-500 mb-6">Veuillez confirmer votre mot de passe avant de continuer.</p>
    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf
        <div>
            <x-input-label for="password" value="Mot de passe" />
            <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <x-primary-button class="w-full justify-center">Confirmer</x-primary-button>
    </form>
</x-guest-layout>
