<x-app-layout>
    <x-slot name="header">
        <h1 class="text-base font-semibold text-slate-800">Mon profil</h1>
    </x-slot>

    <div class="max-w-xl space-y-5">

        {{-- Informations personnelles --}}
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-sm font-semibold text-slate-700">Informations personnelles</h2>
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf @method('PATCH')
                <div>
                    <x-input-label for="name" value="Nom complet" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                @if (session('status') === 'profile-updated')
                    <p class="text-sm text-emerald-600" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">Profil mis à jour.</p>
                @endif
                <x-primary-button>Enregistrer</x-primary-button>
            </form>
        </div>

        {{-- Mot de passe --}}
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-sm font-semibold text-slate-700">Modifier le mot de passe</h2>
            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <x-input-label for="current_password" value="Mot de passe actuel" />
                    <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="password" value="Nouveau mot de passe" />
                    <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="password_confirmation" value="Confirmer le nouveau mot de passe" />
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                </div>
                @if (session('status') === 'password-updated')
                    <p class="text-sm text-emerald-600" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">Mot de passe mis à jour.</p>
                @endif
                <x-primary-button>Mettre à jour</x-primary-button>
            </form>
        </div>

        {{-- Suppression de compte --}}
        <div class="rounded-xl border border-red-200 bg-white p-6 shadow-sm">
            <h2 class="mb-1 text-sm font-semibold text-red-700">Zone de danger</h2>
            <p class="mb-4 text-xs text-slate-500">La suppression du compte est définitive et irréversible.</p>
            <x-danger-button x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
                Supprimer mon compte
            </x-danger-button>
        </div>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="POST" action="{{ route('profile.destroy') }}" class="p-6 space-y-4">
            @csrf @method('DELETE')
            <h2 class="text-sm font-semibold text-slate-800">Supprimer le compte</h2>
            <p class="text-sm text-slate-600">Confirmez votre mot de passe pour supprimer votre compte.</p>
            <div>
                <x-input-label for="password_del" value="Mot de passe" class="sr-only" />
                <x-text-input id="password_del" name="password" type="password" class="block w-full" placeholder="Mot de passe" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>
            <div class="flex gap-3">
                <x-danger-button>Confirmer la suppression</x-danger-button>
                <x-secondary-button x-on:click="$dispatch('close')">Annuler</x-secondary-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
