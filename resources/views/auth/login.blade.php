<x-guest-layout>
    <h2 class="text-xl font-semibold text-slate-800 mb-1">Connexion</h2>
    <p class="text-sm text-slate-500 mb-6">Accédez à votre espace RoomBook.</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="password" :value="__('Mot de passe')" />
            <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-blue-500 shadow-sm focus:ring-blue-500" name="remember">
                Se souvenir de moi
            </label>
            @if (Route::has('password.request'))
                <a class="text-sm text-blue-600 hover:text-blue-800" href="{{ route('password.request') }}">Mot de passe oublié ?</a>
            @endif
        </div>
        <x-primary-button class="w-full justify-center">Se connecter</x-primary-button>
    </form>
</x-guest-layout>
