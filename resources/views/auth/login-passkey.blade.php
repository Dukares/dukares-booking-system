@extends('layouts.app')

@section('content')

<div class="max-w-md mx-auto mt-16 bg-white shadow-xl p-8 rounded-2xl">

    <h1 class="text-2xl font-bold text-center mb-6">🔐 Accedi con Passkey</h1>

    <p class="text-gray-600 text-center mb-8">
        Usa Face ID, impronta digitale o Windows Hello per accedere in modo sicuro.
    </p>

    <!-- BUTTON LOGIN WITH PASSKEY -->
    <button id="login-passkey-btn"
        class="w-full py-3 bg-blue-600 text-white rounded-xl shadow hover:bg-blue-700 transition">
        👉 Accedi con impronta digitale
    </button>

    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" class="text-blue-600 hover:underline">
            Torna al login con email/password
        </a>
    </div>

</div>

@endsection

@section('scripts')
<script type="module">

document.getElementById('login-passkey-btn').addEventListener('click', async () => {

    try {
        // 1) Richiesta delle opzioni dal server
        const optionsResponse = await fetch("{{ route('passkeys.login.options') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            }
        });

        const options = await optionsResponse.json();

        // 2) Utente esegue FaceID/TouchID/Windows Hello
        const assertion = await navigator.credentials.get(options);

        // 3) Mandiamo al server
        const verifyResponse = await fetch("{{ route('passkeys.login') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: JSON.stringify(assertion)
        });

        if (verifyResponse.ok) {
            window.location = "/dashboard";  // login OK = vai in dashboard
        } else {
            alert("Impossibile verificare la passkey.");
        }

    } catch (error) {
        console.error(error);
        alert("Errore: assicurati che il tuo dispositivo supporti FaceID o impronta.");
    }

});

</script>
@endsection
