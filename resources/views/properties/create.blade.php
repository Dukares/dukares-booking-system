@extends('layouts.dukares-layout')

@section('content')

<div class="container mx-auto py-6">

    <h1 class="text-3xl font-bold mb-6">➕ Aggiungi Struttura</h1>

    {{-- Se non esistono proprietari --}}
    @if($owners->isEmpty())
        <div class="p-4 bg-yellow-200 border-l-4 border-yellow-600 mb-6">
            ⚠️ Prima devi creare almeno un <strong>proprietario</strong>.
        </div>

        <a href="{{ route('owners.create') }}" 
            class="px-4 py-2 bg-blue-600 text-white rounded">
            ➕ Crea Proprietario
        </a>

        @return
    @endif

    <form method="POST" action="{{ route('properties.store') }}" 
          class="bg-white p-6 rounded shadow w-full max-w-xl">
        @csrf

        {{-- PROPRIETARIO --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Proprietario *</label>
            <select name="owner_id" class="w-full border rounded p-2" required>
                <option value="">Seleziona proprietario</option>

                @foreach($owners as $owner)
                    <option value="{{ $owner->id }}">
                        {{ $owner->nome }} {{ $owner->cognome }}
                    </option>
                @endforeach

            </select>
        </div>

        {{-- NOME STRUTTURA --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Nome struttura *</label>
            <input type="text" name="name" 
                   class="w-full border rounded p-2" required>
        </div>

        {{-- INDIRIZZO --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Indirizzo *</label>
            <input type="text" name="address" 
                   class="w-full border rounded p-2" required>
        </div>

        {{-- DESCRIZIONE --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Descrizione</label>
            <textarea name="description" rows="4" 
                      class="w-full border rounded p-2"></textarea>
        </div>

        {{-- BOTTONI --}}
        <div class="flex justify-between items-center mt-6">
            <a href="{{ route('properties.index') }}" 
               class="text-blue-600 hover:underline">
                ‹ Torna alla lista
            </a>

            <button type="submit" 
                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                💾 Salva Struttura
            </button>
        </div>
    </form>

</div>

@endsection

