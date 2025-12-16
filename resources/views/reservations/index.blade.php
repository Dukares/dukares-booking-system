@extends('layouts.dukares-layout')

@section('content')
<div class="container mx-auto py-6">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">📅 Prenotazioni</h1>

        <button
            class="bg-gray-400 text-white px-4 py-2 rounded cursor-not-allowed"
            title="Funzione in arrivo">
            ➕ Nuova Prenotazione
        </button>
    </div>

    <div class="bg-white rounded shadow p-6">
        <p class="text-gray-500">
            Nessuna prenotazione registrata.
        </p>
    </div>

</div>
@endsection
