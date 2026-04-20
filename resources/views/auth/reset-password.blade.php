<x-guest-layout>
    <h2 class="text-xl font-semibold text-slate-800 mb-6">Réinitialiser le mot de passe</h2>
    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email', $request->email)" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="password" value="Nouveau mot de passe" />
            <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="password_confirmation" value="Confirmer le mot de passe" />
            <x-text-input id="password_confirmation" class="mt-1 block w-full" type="password" name="password_confirmation" required />
        </div>
        <x-primary-button class="w-full justify-center">Réinitialiser</x-primary-button>
    </form>
</x-guest-layout>
