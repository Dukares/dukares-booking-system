@extends('layouts.dukares-layout')

@section('content')

<h1 class="text-3xl font-bold mb-6">➕ Nuovo Proprietario</h1>

<form method="POST" action="{{ route('owners.store') }}"
      class="bg-white p-6 rounded shadow max-w-xl">
    @csrf

    {{-- NOME --}}
    <div class="mb-4">
        <label class="block font-semibold mb-1">Nome *</label>
        <input
            type="text"
            name="nome"
            value="{{ old('nome') }}"
            required
            class="w-full border rounded p-2"
        >
    </div>

    {{-- EMAIL --}}
    <div class="mb-4">
        <label class="block font-semibold mb-1">Email</label>
        <input
            type="email"
            name="email"
            value="{{ old('email') }}"
            class="w-full border rounded p-2"
        >
    </div>

    {{-- TELEFONO --}}
    <div class="mb-4">
        <label class="block font-semibold mb-1">Telefono</label>
        <input
            type="text"
            name="phone"
            value="{{ old('phone') }}"
            class="w-full border rounded p-2"
        >
    </div>

    <div class="flex items-center">
        <button
            type="submit"
            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
        >
            Salva Proprietario
        </button>

        <a
            href="{{ route('owners.index') }}"
            class="ml-4 text-gray-600 hover:underline"
        >
            Annulla
        </a>
    </div>
</form>

@endsection
