<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Modifier un utilisateur</h2></x-slot>
    <div class="py-8"><div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8"><x-alert />
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4 rounded bg-white p-6 shadow">@csrf @method('PUT')
            <div><x-input-label value="Nom" /><x-text-input name="name" class="mt-1 block w-full" :value="old('name', $user->name)" required /><x-input-error :messages="$errors->get('name')" class="mt-2" /></div>
            <div><x-input-label value="Email" /><x-text-input type="email" name="email" class="mt-1 block w-full" :value="old('email', $user->email)" required /><x-input-error :messages="$errors->get('email')" class="mt-2" /></div>
            <div><x-input-label value="Rôle" /><select name="role" class="mt-1 block w-full rounded border-gray-300"><option value="enseignant" @selected(old('role', $user->role)==='enseignant')>enseignant</option><option value="responsable" @selected(old('role', $user->role)==='responsable')>responsable</option><option value="admin" @selected(old('role', $user->role)==='admin')>admin</option></select><x-input-error :messages="$errors->get('role')" class="mt-2" /></div>
            <div><x-input-label value="Nouveau mot de passe (optionnel)" /><x-text-input type="password" name="password" class="mt-1 block w-full" /><x-input-error :messages="$errors->get('password')" class="mt-2" /></div>
            <div><x-input-label value="Confirmer mot de passe" /><x-text-input type="password" name="password_confirmation" class="mt-1 block w-full" /></div>
            <div><x-primary-button>Mettre à jour</x-primary-button></div>
        </form>
    </div></div>
</x-app-layout>
