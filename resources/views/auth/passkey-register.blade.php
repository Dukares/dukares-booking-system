@extends('layouts.dukares-layout')

@section('content')
<div class="max-w-lg mx-auto mt-10 bg-white shadow-md p-6 rounded-lg">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">
        Registra una Passkey
    </h2>

    <p class="text-gray-600 mb-4">
        Registra una passkey per accedere a DukaRes con FaceID, impronta digitale o codice biometrico.
    </p>

    <button id="registerPasskey" 
        class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
        Registra Passkey
    </button>
</div>

<script src="/js/passkey.js"></script>
@endsection
