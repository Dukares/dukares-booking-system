@extends('layouts.dukares-layout')

@section('content')

<div class="max-w-md mx-auto bg-white shadow rounded-lg p-6 text-center">

    <h1 class="text-2xl font-bold mb-3 text-red-600">
        ⚠️ Accesso da Rete ad Alto Rischio
    </h1>

    <p class="text-gray-700 mb-4">
        Abbiamo rilevato un accesso da una connessione con rischio elevato.
        Per continuare devi verificare la tua identità usando la tua <strong>Passkey</strong>.
    </p>

    <div id="errorBox" class="hidden bg-red-100 text-red-700 px-3 py-2 rounded mb-4"></div>

    <button id="passkeyLoginBtn"
            class="w-full py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Autenticati con Passkey
    </button>

</div>

@endsection

@section('scripts')
<script>
document.getElementById('passkeyLoginBtn').addEventListener('click', async () => {
    try {
        const begin = await fetch("{{ route('passkey.login.begin') }}", {
            method: "POST",
            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
        });

        const options = await begin.json();

        const credential = await navigator.credentials.get({
            publicKey: options
        });

        const finish = await fetch("{{ route('passkey.login.finish') }}", {
            method: "POST",
            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: JSON.stringify(credential)
        });

        if (finish.status === 200) {
            window.location.href = "/dashboard";
        } else {
            showError("Verifica passkey fallita.");
        }

    } catch (err) {
        showError("Errore: " + err);
    }
});

function showError(msg) {
    const box = document.getElementById("errorBox");
    box.innerText = msg;
    box.classList.remove("hidden");
}
</script>
@endsection
