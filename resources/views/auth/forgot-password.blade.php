<x-guest-layout>
    <h2 class="text-xl font-semibold text-slate-800 mb-1">Mot de passe oublié</h2>
    <p class="text-sm text-slate-500 mb-6">Entrez votre email et nous vous enverrons un lien de réinitialisation.</p>
    @if (session('status'))
        <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 p-3 text-sm text-emerald-700">{{ session('status') }}</div>
    @endif
    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf
        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <x-primary-button class="w-full justify-center">Envoyer le lien</x-primary-button>
        <a href="{{ route('login') }}" class="block text-center text-sm text-blue-600 hover:underline">Retour à la connexion</a>
    </form>
</x-guest-layout>
