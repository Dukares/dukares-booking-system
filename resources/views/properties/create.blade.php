@extends('layouts.dukares-layout')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Nuova Struttura</h1>
    <a href="{{ route('properties.index') }}" class="text-blue-600 font-medium">← Torna</a>
</div>

@if ($errors->any())
    <div class="bg-red-100 border border-red-300 text-red-800 p-3 rounded mb-4">
        <ul class="list-disc ml-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('properties.store') }}"
      class="bg-white p-6 rounded-lg shadow-md max-w-xl">

    @csrf

    {{-- Proprietario --}}
    <label class="block font-semibold mb-1">Proprietario *</label>
    <select name="owner_id"
            class="w-full bg-white border border-gray-400 rounded-md px-3 py-2 mb-4
                   focus:outline-none focus:ring-2 focus:ring-blue-500"
            required>
        <option value="">-- Seleziona proprietario --</option>
        @foreach($owners as $owner)
            <option value="{{ $owner->id }}" @selected(old('owner_id') == $owner->id)>
                {{ $owner->nome }} {{ $owner->cognome }}
            </option>
        @endforeach
    </select>

    {{-- Titolo --}}
    <label class="block font-semibold mb-1">Titolo struttura *</label>
    <input type="text"
           name="title"
           placeholder="Es. Hotel Centrale Tirana"
           value="{{ old('title') }}"
           class="w-full bg-white border border-gray-400 rounded-md px-3 py-2 mb-4
                  focus:outline-none focus:ring-2 focus:ring-blue-500"
           required>

    {{-- Città --}}
    <label class="block font-semibold mb-1">Città</label>
    <input type="text"
           name="city"
           placeholder="Es. Tirana"
           value="{{ old('city') }}"
           class="w-full bg-white border border-gray-400 rounded-md px-3 py-2 mb-4
                  focus:outline-none focus:ring-2 focus:ring-blue-500">

    {{-- Prezzo --}}
    <label class="block font-semibold mb-1">Prezzo per notte (€)</label>
    <input type="number"
           step="0.01"
           name="price_per_night"
           value="{{ old('price_per_night', 0) }}"
           class="w-full bg-white border border-gray-400 rounded-md px-3 py-2 mb-4
                  focus:outline-none focus:ring-2 focus:ring-blue-500">

    {{-- ICS --}}
    <label class="block font-semibold mb-1">ICS URL (facoltativo)</label>
    <input type="url"
           name="ics_url"
           placeholder="https://booking.com/ical/..."
           value="{{ old('ics_url') }}"
           class="w-full bg-white border border-gray-400 rounded-md px-3 py-2 mb-4
                  focus:outline-none focus:ring-2 focus:ring-blue-500">

    {{-- Attiva --}}
    <label class="flex items-center gap-2 mb-6 font-medium">
        <input type="checkbox" name="active" value="1" @checked(old('active', 1))>
        Attiva
    </label>

    <button type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold
                   px-6 py-2 rounded-md shadow">
        Salva struttura
    </button>
</form>
@endsection
