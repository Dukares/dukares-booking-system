@extends('layouts.dukares-layout')

@section('content')

<h1 class="text-3xl font-bold mb-6 text-gray-800">Nuova Prenotazione</h1>

@if ($errors->any())
<div class="bg-red-200 text-red-800 p-3 rounded mb-4">
    <strong>Errore:</strong> verifica i dati inseriti.
</div>
@endif

<form method="POST" action="{{ route('reservations.store') }}"
      class="bg-white p-6 shadow rounded-lg space-y-6">
    @csrf

    <div>
        <label class="font-semibold">Nome Ospite *</label>
        <input type="text" name="guest_name" class="w-full border p-2 rounded" required>
    </div>

    <div>
        <label class="font-semibold">Struttura *</label>
        <select name="property_id" class="w-full border p-2 rounded" required>
            <option value="">Seleziona struttura</option>
            @foreach($properties as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="font-semibold">Check-in *</label>
            <input type="date" name="checkin" class="w-full border p-2 rounded" required>
        </div>

        <div>
            <label class="font-semibold">Check-out *</label>
            <input type="date" name="checkout" class="w-full border p-2 rounded" required>
        </div>
    </div>

    <div>
        <label class="font-semibold">Prezzo totale (€)</label>
        <input type="number" name="price" step="0.01" class="w-full border p-2 rounded" required>
    </div>

    <div>
        <label class="font-semibold">Stato prenotazione</label>
        <select name="status" class="w-full border p-2 rounded">
            <option value="confermato">Confermato</option>
            <option value="in_attesa">In attesa</option>
            <option value="annullato">Annullato</option>
        </select>
    </div>

    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        Salva Prenotazione
    </button>

</form>

@endsection
