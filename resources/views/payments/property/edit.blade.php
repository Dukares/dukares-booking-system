@extends('layouts.dukares-layout')

@section('content')

<h1 class="text-3xl font-bold mb-6">
    Pagamenti – {{ $property->nome }}
</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<form method="POST" action="{{ route('property.payments.update', $property->id) }}"
      class="bg-white shadow-lg rounded p-6 w-full max-w-2xl">
    @csrf

    <h2 class="text-xl font-bold mb-4">Metodi di pagamento in struttura</h2>

    <label class="flex items-center mb-2">
        <input type="checkbox" name="accetta_contanti" value="1"
               {{ $settings->accetta_contanti ? 'checked' : '' }}
               class="mr-2">
        Contanti
    </label>

    <label class="flex items-center mb-2">
        <input type="checkbox" name="accetta_pos" value="1"
               {{ $settings->accetta_pos ? 'checked' : '' }}
               class="mr-2">
        POS / Carte in struttura
    </label>

    <label class="flex items-center mb-4">
        <input type="checkbox" name="accetta_bonifico" value="1"
               {{ $settings->accetta_bonifico ? 'checked' : '' }}
               class="mr-2">
        Bonifico bancario
    </label>


    <hr class="my-6">


    <h2 class="text-xl font-bold mb-4">Pagamenti Online</h2>

    <label class="flex items-center mb-4">
        <input type="checkbox" name="online_enabled" value="1"
               {{ $settings->online_enabled ? 'checked' : '' }}
               class="mr-2">
        Attiva pagamento online
    </label>

    <div class="mb-4">
        <label class="font-semibold">Gateway</label>
        <select name="gateway" class="w-full border p-2 rounded">
            <option value="">— Seleziona —</option>
            <option value="stripe" {{ $settings->gateway == 'stripe' ? 'selected' : '' }}>Stripe</option>
            <option value="paypal" {{ $settings->gateway == 'paypal' ? 'selected' : '' }}>PayPal</option>
            <option value="revolut" {{ $settings->gateway == 'revolut' ? 'selected' : '' }}>Revolut</option>
        </select>
    </div>

    <div class="mb-4">
        <label class="font-semibold">API Key Public</label>
        <input type="text" name="api_key_public" class="w-full border p-2 rounded"
               value="{{ $settings->api_key_public }}">
    </div>

    <div class="mb-4">
        <label class="font-semibold">API Key Secret</label>
        <input type="text" name="api_key_secret" class="w-full border p-2 rounded"
               value="{{ $settings->api_key_secret }}">
    </div>


    <hr class="my-6">

    <h2 class="text-xl font-bold mb-4">Anticipo</h2>

    <div class="mb-4">
        <label class="font-semibold">Percentuale anticipo (%)</label>
        <input type="number" name="anticipo_percentuale"
               class="w-full border p-2 rounded"
               value="{{ $settings->anticipo_percentuale }}">
    </div>


    <button class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded w-full">
        Salva impostazioni
    </button>

</form>

@endsection
