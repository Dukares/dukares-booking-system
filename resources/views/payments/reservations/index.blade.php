@extends('layouts.dukares-layout')

@section('content')

<h1 class="text-3xl font-bold mb-6">Registra Pagamento Manuale</h1>

<form method="POST" action="{{ route('payments.reservations.store') }}"
      class="bg-white shadow rounded p-6 w-1/2">
    @csrf

    <div class="mb-4">
        <label class="font-semibold">Prenotazione</label>
        <select name="reservation_id" class="w-full border p-2 rounded">
            @foreach($reservations as $r)
                <option value="{{ $r->id }}">{{ $r->id }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label class="font-semibold">Struttura</label>
        <select name="property_id" class="w-full border p-2 rounded">
            @foreach($properties as $p)
                <option value="{{ $p->id }}">{{ $p->nome }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label class="font-semibold">Metodo di pagamento</label>
        <select name="payment_method_id" class="w-full border p-2 rounded">
            <option value="">— Nessuno —</option>
            @foreach($methods as $m)
                <option value="{{ $m->id }}">{{ $m->nome }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label class="font-semibold">Importo (€)</label>
        <input type="number" step="0.01" name="importo" class="w-full border p-2 rounded">
    </div>

    <button class="bg-blue-600 text-white py-2 px-4 rounded">
        Registra Pagamento
    </button>
</form>

@endsection
