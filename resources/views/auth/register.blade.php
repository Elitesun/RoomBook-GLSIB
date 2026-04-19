<x-guest-layout>
    <h2 class="text-xl font-semibold text-slate-800 mb-1">Créer un compte</h2>
    <p class="text-sm text-slate-500 mb-6">Rejoignez RoomBook.</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <div>
            <x-input-label for="name" :value="__('Nom complet')" />
            <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="password" :value="__('Mot de passe')" />
            <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" />
            <x-text-input id="password_confirmation" class="mt-1 block w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        <x-primary-button class="w-full justify-center">Créer mon compte</x-primary-button>
        <p class="text-center text-sm text-slate-500">
            Déjà inscrit ? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Se connecter</a>
        </p>
    </form>
</x-guest-layout>
