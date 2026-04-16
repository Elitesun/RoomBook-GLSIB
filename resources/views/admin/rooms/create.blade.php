<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Créer une salle</h2></x-slot>
    <div class="py-8"><div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8"><x-alert />
        <form method="POST" action="{{ route('admin.rooms.store') }}" class="space-y-4 rounded bg-white p-6 shadow">@csrf
            <div><x-input-label value="Nom" /><x-text-input name="name" class="mt-1 block w-full" :value="old('name')" required /><x-input-error :messages="$errors->get('name')" class="mt-2" /></div>
            <div><x-input-label value="Capacité" /><x-text-input type="number" name="capacity" class="mt-1 block w-full" :value="old('capacity')" required /><x-input-error :messages="$errors->get('capacity')" class="mt-2" /></div>
            <div><x-input-label value="Bâtiment" /><x-text-input name="building" class="mt-1 block w-full" :value="old('building')" required /><x-input-error :messages="$errors->get('building')" class="mt-2" /></div>
            <label class="inline-flex items-center gap-2"><input type="checkbox" name="is_available" value="1" @checked(old('is_available', 1))> Disponible</label>
            <div><x-primary-button>Enregistrer</x-primary-button></div>
        </form>
    </div></div>
</x-app-layout>
