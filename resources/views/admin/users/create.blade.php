<x-app-layout>
    <x-slot name="header"><h1 class="text-base font-semibold text-slate-800">Créer un utilisateur</h1></x-slot>
    <x-alert />
    <div class="max-w-lg">
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                @csrf
                <div><x-input-label value="Nom complet" /><x-text-input name="name" class="mt-1 block w-full" :value="old('name')" required /><x-input-error :messages="$errors->get('name')" class="mt-2" /></div>
                <div><x-input-label value="Email" /><x-text-input type="email" name="email" class="mt-1 block w-full" :value="old('email')" required /><x-input-error :messages="$errors->get('email')" class="mt-2" /></div>
                <div>
                    <x-input-label value="Rôle" />
                    <select name="role" class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="enseignant">Enseignant</option>
                        <option value="responsable">Responsable</option>
                        <option value="admin">Administrateur</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                </div>
                <div><x-input-label value="Mot de passe" /><x-text-input type="password" name="password" class="mt-1 block w-full" required /><x-input-error :messages="$errors->get('password')" class="mt-2" /></div>
                <div><x-input-label value="Confirmer le mot de passe" /><x-text-input type="password" name="password_confirmation" class="mt-1 block w-full" required /></div>
                <div class="flex items-center gap-3 pt-2"><x-primary-button>Enregistrer</x-primary-button><a href="{{ route('admin.users.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Annuler</a></div>
            </form>
        </div>
    </div>
</x-app-layout>
