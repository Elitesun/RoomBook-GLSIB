<x-app-layout>
    <x-slot name="header"><h1 class="text-base font-semibold text-slate-800">Utilisateurs</h1></x-slot>
    <x-slot name="headerActions">
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-amber-500 px-3 py-1.5 text-sm font-medium text-white shadow-sm hover:bg-amber-600 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>
            Ajouter un utilisateur
        </a>
    </x-slot>
    <x-alert />
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Utilisateur</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Rôle</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($users as $user)
                    @php
                        $rolePill = match($user->role) {
                            'admin'       => 'bg-amber-100 text-amber-800 ring-amber-200',
                            'responsable' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
                            default       => 'bg-blue-100 text-blue-800 ring-blue-200',
                        };
                        $roleLabel = match($user->role) {
                            'admin' => 'Administrateur', 'responsable' => 'Responsable', default => 'Enseignant'
                        };
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-slate-200 text-xs font-semibold text-slate-600">
                                    {{ strtoupper(substr($user->name,0,1)) }}{{ strtoupper(substr(strstr($user->name,' '),1,1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-slate-800">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 {{ $rolePill }}">{{ $roleLabel }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-2.5 py-1 text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors">Modifier</a>
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}">@csrf @method('DELETE')
                                    <button onclick="return confirm('Supprimer cet utilisateur ?')" class="inline-flex items-center rounded-md bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700 ring-1 ring-red-200 hover:bg-red-100 transition-colors">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $users->links() }}</div>
</x-app-layout>
