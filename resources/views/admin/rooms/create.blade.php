<x-app-layout>
    <x-slot name="header"><h1 class="text-base font-semibold text-slate-800">Créer une salle</h1></x-slot>
    <x-alert />
    <div class="max-w-lg">
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
            <form method="POST" action="{{ route('admin.rooms.store') }}" class="space-y-4">
                @csrf
                <div><x-input-label value="Nom" /><x-text-input name="name" class="mt-1 block w-full" :value="old('name')" required /><x-input-error :messages="$errors->get('name')" class="mt-2" /></div>
                <div><x-input-label value="Capacité" /><x-text-input type="number" name="capacity" class="mt-1 block w-full" :value="old('capacity')" required /><x-input-error :messages="$errors->get('capacity')" class="mt-2" /></div>
                <div><x-input-label value="Bâtiment" /><x-text-input name="building" class="mt-1 block w-full" :value="old('building')" required /><x-input-error :messages="$errors->get('building')" class="mt-2" /></div>
                <label class="inline-flex items-center gap-2 text-sm text-slate-700 cursor-pointer"><input type="checkbox" name="is_available" value="1" @checked(old('is_available', 1)) class="rounded border-slate-300 text-blue-500"> Disponible</label>
                <div class="flex items-center gap-3 pt-2"><x-primary-button>Enregistrer</x-primary-button><a href="{{ route('admin.rooms.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Annuler</a></div>
            </form>
        </div>
    </div>
</x-app-layout>
