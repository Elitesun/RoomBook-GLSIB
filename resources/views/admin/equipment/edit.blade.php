<x-app-layout>
    <x-slot name="header"><h1 class="text-base font-semibold text-slate-800">Modifier un matériel</h1></x-slot>
    <x-alert />
    <div class="max-w-lg">
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.equipment.update', $equipment) }}" class="space-y-4">
                @csrf @method('PUT')
                <div><x-input-label value="Nom" /><x-text-input name="name" class="mt-1 block w-full" :value="old('name', $equipment->name)" required /><x-input-error :messages="$errors->get('name')" class="mt-2" /></div>
                <div><x-input-label value="Quantité" /><x-text-input type="number" name="quantity" class="mt-1 block w-full" :value="old('quantity', $equipment->quantity)" required /><x-input-error :messages="$errors->get('quantity')" class="mt-2" /></div>
                <label class="inline-flex items-center gap-2 text-sm text-slate-700 cursor-pointer"><input type="checkbox" name="is_available" value="1" @checked(old('is_available', $equipment->is_available)) class="rounded border-slate-300 text-blue-500"> Disponible</label>
                <div class="flex items-center gap-3 pt-2"><x-primary-button>Mettre à jour</x-primary-button><a href="{{ route('admin.equipment.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Annuler</a></div>
            </form>
        </div>
    </div>
</x-app-layout>
