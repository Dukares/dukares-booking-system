@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10">

    <!-- Title -->
    <h1 class="text-3xl font-bold text-gray-800 mb-6">🔐 Sicurezza Account — Passkey</h1>

    <!-- Description -->
    <p class="text-gray-600 mb-6">
        Le passkey ti permettono di accedere al tuo account usando
        <strong>impronta digitale, Face ID, Windows Hello o PIN biometrico</strong>.
        Sono più sicure e più rapide delle password.
    </p>

    <!-- ADD PASSKEY BUTTON -->
    <div class="mb-8">
        <button id="add-passkey-btn"
            class="px-5 py-3 bg-blue-600 text-white rounded-xl shadow hover:bg-blue-700 transition">
            ➕ Aggiungi una nuova Passkey
        </button>
    </div>

    <!-- EXISTING PASSKEYS -->
    <div class="bg-white shadow rounded-xl p-6">
        <h2 class="text-xl font-semibold mb-4">Le tue passkey registrate</h2>

        @if($passkeys->isEmpty())
            <p class="text-gray-500">Nessuna passkey registrata.</p>
        @else
            <ul class="divide-y divide-gray-200">
                @foreach($passkeys as $key)
                    <li class="py-3 flex items-center justify-between">
                        <div>
                            <strong>{{ $key->name }}</strong>
                            <p class="text-sm text-gray-500">Registrata il {{ $key->created_at->format('d/m/Y') }}</p>
                        </div>
                        <form method="POST" action="{{ route('passkeys.destroy', $key->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline">
                                Elimina
                            </button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>

@endsection

@section('scripts')
<script type="module">

// Quando clicchi "Aggiungi Passkey"
document.getElementById('add-passkey-btn').addEventListener('click', async () => {

    try {
        // 1) Richiedi opzioni dal server
        const optionsResponse = await fetch("{{ route('passkeys.register.options') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            }
        });

        const options = await optionsResponse.json();

        // 2) Avvia creazione passkey nativa del browser
        const credential = await navigator.credentials.create(options);

        // 3) Invia la passkey registrata al backend
        const verifyResponse = await fetch("{{ route('passkeys.register.store') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: JSON.stringify(credential)
        });

        if (verifyResponse.ok) {
            alert("Passkey registrata con successo!");
            window.location.reload();
        } else {
            alert("Errore nella verifica della passkey.");
        }

    } catch (error) {
        console.error(error);
        alert("Errore: assicurati che il dispositivo supporti FaceID / biometria.");
    }

});

</script>
@endsection
