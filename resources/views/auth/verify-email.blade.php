<x-guest-layout>
    <h2 class="text-xl font-semibold text-slate-800 mb-2">Vérifiez votre email</h2>
    <p class="text-sm text-slate-500 mb-4">Un lien de vérification a été envoyé à votre adresse email.</p>
    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 p-3 text-sm text-emerald-700">Un nouveau lien a été envoyé.</div>
    @endif
    <div class="flex flex-col gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button class="w-full justify-center">Renvoyer le lien</x-primary-button>
        </form>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full text-sm text-slate-500 hover:text-slate-700 text-center">Se déconnecter</button>
        </form>
    </div>
</x-guest-layout>
