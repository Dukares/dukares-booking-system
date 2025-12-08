@extends('layouts.dukares-layout')

@section('content')

<h1 class="text-3xl font-bold mb-6">Impostazioni DukaRes – Proprietario del Portale</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<form method="POST" action="{{ route('admin.owner.settings.update') }}"
      class="bg-white shadow-lg rounded p-6 w-full max-w-3xl">
    @csrf

    <h2 class="text-xl font-bold mb-4">Dati Bancari</h2>

    <div class="mb-4">
        <label class="font-semibold">IBAN</label>
        <input type="text" name="iban" class="w-full border p-2 rounded"
               value="{{ $settings->iban }}">
    </div>

    <div class="mb-4">
        <label class="font-semibold">Intestatario Conto</label>
        <input type="text" name="intestatario_conto" class="w-full border p-2 rounded"
               value="{{ $settings->intestatario_conto }}">
    </div>

    <div class="mb-4">
        <label class="font-semibold">SWIFT / BIC</label>
        <input type="text" name="swift" class="w-full border p-2 rounded"
               value="{{ $settings->swift }}">
    </div>


    <hr class="my-6">


    <h2 class="text-xl font-bold mb-4">Metodi Digitali</h2>

    <div class="mb-4">
        <label class="font-semibold">PayPal Email</label>
        <input type="email" name="paypal_email" class="w-full border p-2 rounded"
               value="{{ $settings->paypal_email }}">
    </div>

    <div class="mb-4">
        <label class="font-semibold">Revolut</label>
        <input type="text" name="revolut" class="w-full border p-2 rounded"
               value="{{ $settings->revolut }}">
    </div>

    <div class="mb-4">
        <label class="font-semibold">Wise</label>
        <input type="text" name="wise" class="w-full border p-2 rounded"
               value="{{ $settings->wise }}">
    </div>


    <hr class="my-6">


    <h2 class="text-xl font-bold mb-4">Commissioni DukaRes</h2>

    <div class="mb-4">
        <label class="font-semibold">Commissione (%)</label>
        <input type="number" step="0.01" name="commissione_percentuale"
               class="w-full border p-2 rounded"
               value="{{ $settings->commissione_percentuale }}">
    </div>

    <div class="mb-4">
        <label class="font-semibold">Commissione Minima (€)</label>
        <input type="number" step="0.01" name="commissione_minima"
               class="w-full border p-2 rounded"
               value="{{ $settings->commissione_minima }}">
    </div>

    <div class="mb-4">
        <label class="font-semibold">Commissione Massima (€)</label>
        <input type="number" step="0.01" name="commissione_massima"
               class="w-full border p-2 rounded"
               value="{{ $settings->commissione_massima }}">
    </div>


    <div class="mb-4">
        <label class="font-semibold">Ciclo di Fatturazione</label>
        <select name="ciclo_fatturazione" class="w-full border p-2 rounded">
            <option value="settimanale" {{ $settings->ciclo_fatturazione == 'settimanale' ? 'selected' : '' }}>Settimanale</option>
            <option value="quindicinale" {{ $settings->ciclo_fatturazione == 'quindicinale' ? 'selected' : '' }}>Quindicinale</option>
            <option value="mensile" {{ $settings->ciclo_fatturazione == 'mensile' ? 'selected' : '' }}>Mensile</option>
        </select>
    </div>


    <button class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded w-full">
        Salva Impostazioni
    </button>

</form>

@endsection
