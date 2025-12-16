@extends('layouts.dukares-layout')

@section('content')

<h1 class="text-3xl font-bold mb-6">➕ Nuova Struttura</h1>

<form method="POST" action="{{ route('properties.store') }}"
      class="bg-white p-6 rounded shadow max-w-xl">
    @csrf

    <div class="mb-4">
        <label class="block font-semibold mb-1">Nome *</label>
        <input type="text" name="name" required
               class="w-full border rounded p-2">
    </div>

    <div class="mb-4">
        <label class="block font-semibold mb-1">Città</label>
        <input type="text" name="city"
               class="w-full border rounded p-2">
    </div>

    <button type="submit"
            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
        Salva Struttura
    </button>

    <a href="{{ route('properties.index') }}"
       class="ml-4 text-gray-600 hover:underline">
        Annulla
    </a>
</form>

@endsection
