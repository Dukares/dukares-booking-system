@extends('layouts.dukares-layout')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Modifica Proprietario</h1>
</div>

<div class="bg-white shadow rounded-lg p-6">

    <form method="POST" action="{{ route('owners.update', $owner) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-semibold mb-1">Nome *</label>
            <input type="text" name="nome" value="{{ old('nome', $owner->nome) }}" required
                   class="w-full border-gray-300 rounded-lg shadow-sm p-2">
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Cognome</label>
            <input type="text" name="cognome" value="{{ old('cognome', $owner->cognome) }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm p-2">
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $owner->email) }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm p-2">
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Telefono</label>
            <input type="text" name="telefono" value="{{ old('telefono', $owner->telefono) }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm p-2">
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ route('owners.index') }}" 
               class="text-gray-600 hover:underline">
               ← Torna alla lista
            </a>

            <button type="submit"
                class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">
                Aggiorna
            </button>
        </div>

    </form>

</div>

@endsection


