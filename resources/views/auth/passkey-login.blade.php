@extends('layouts.dukares-layout')

@section('content')
<div class="max-w-lg mx-auto mt-10 bg-white shadow-md p-6 rounded-lg">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">
        Accedi con Passkey
    </h2>

    <p class="text-gray-600 mb-4">
        Inserisci la tua email e usa FaceID / impronta digitale per accedere in sicurezza.
    </p>

    <input id="email" type="email"
        class="w-full border border-gray-300 p-2 rounded-lg mb-4"
        placeholder="La tua email per il login"
    >

    <button id="loginPasskey"
        class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition">
        Accedi con Passkey
    </button>
</div>

<script src="/js/passkey.js"></script>
@endsection
