<x-app-layout>
    <x-slot name="header"><h1 class="text-base font-semibold text-slate-800">Modifier un utilisateur</h1></x-slot>
    <x-alert />
    <div class="max-w-lg">
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
                @csrf @method('PUT')
                <div><x-input-label value="Nom complet" /><x-text-input name="name" class="mt-1 block w-full" :value="old('name', $user->name)" required /><x-input-error :messages="$errors->get('name')" class="mt-2" /></div>
                <div><x-input-label value="Email" /><x-text-input type="email" name="email" class="mt-1 block w-full" :value="old('email', $user->email)" required /><x-input-error :messages="$errors->get('email')" class="mt-2" /></div>
                <div>
                    <x-input-label value="Rôle" />
                    <select name="role" class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="enseignant" @selected(old('role',$user->role)==='enseignant')>Enseignant</option>
                        <option value="responsable" @selected(old('role',$user->role)==='responsable')>Responsable</option>
                        <option value="admin" @selected(old('role',$user->role)==='admin')>Administrateur</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                </div>
                <div><x-input-label value="Nouveau mot de passe (optionnel)" /><x-text-input type="password" name="password" class="mt-1 block w-full" /><x-input-error :messages="$errors->get('password')" class="mt-2" /></div>
                <div><x-input-label value="Confirmer le mot de passe" /><x-text-input type="password" name="password_confirmation" class="mt-1 block w-full" /></div>
                <div class="flex items-center gap-3 pt-2"><x-primary-button>Mettre à jour</x-primary-button><a href="{{ route('admin.users.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Annuler</a></div>
            </form>
        </div>
    </div>
</x-app-layout>
