@extends('layouts.dukares-layout')

@section('content')
<div class="container mx-auto py-6">

    <h1 class="text-3xl font-bold mb-6">Modifica Struttura</h1>

    <form method="POST" action="{{ route('properties.update', $property) }}" class="bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')

        <label class="block mb-2 font-semibold">Nome struttura *</label>
        <input type="text" name="name" class="w-full border rounded p-2 mb-4"
               value="{{ old('name', $property->name) }}">

        <label class="block mb-2 font-semibold">Indirizzo *</label>
        <input type="text" name="address" class="w-full border rounded p-2 mb-4"
               value="{{ old('address', $property->address) }}">

        <label class="block mb-2 font-semibold">Descrizione</label>
        <textarea name="description" rows="5" class="w-full border rounded p-2 mb-4">
            {{ old('description', $property->description) }}
        </textarea>

        <button class="px-4 py-2 bg-blue-600 text-white rounded">
            💾 Salva
        </button>

    </form>

</div>
@endsection
