@extends('layouts.dukares-layout')

@section('content')
<div class="container mx-auto py-6">

    <h1 class="text-3xl font-bold mb-6">✏️ Modifica Struttura</h1>

    <form method="POST" action="{{ route('properties.update', $property) }}" 
          class="bg-white p-6 rounded shadow max-w-xl">
        @csrf
        @method('PUT')

        {{-- PROPRIETARIO --}}
        <div class="mb-4">
            <label class="block mb-2 font-semibold">Proprietario *</label>
            <select name="owner_id" class="w-full border rounded p-2" required>
                @foreach($owners as $owner)
                    <option value="{{ $owner->id }}" 
                        {{ $property->owner_id == $owner->id ? 'selected' : '' }}>
                        {{ $owner->nome }} {{ $owner->cognome }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- NOME STRUTTURA --}}
        <div class="mb-4">
            <label class="block mb-2 font-semibold">Nome struttura *</label>
            <input type="text" name="name" 
                   class="w-full border rounded p-2"
                   value="{{ old('name', $property->name) }}" required>
        </div>

        {{-- INDIRIZZO --}}
        <div class="mb-4">
            <label class="block mb-2 font-semibold">Indirizzo *</label>
            <input type="text" name="address" 
                   class="w-full border rounded p-2"
                   value="{{ old('address', $property->address) }}" required>
        </div>

        {{-- CITTA --}}
        <div class="mb-4">
            <label class="block mb-2 font-semibold">Città</label>
            <input type="text" name="city"
                   class="w-full border rounded p-2"
                   value="{{ old('city', $property->city) }}">
        </div>

        {{-- NAZIONE --}}
        <div class="mb-4">
            <label class="block mb-2 font-semibold">Nazione</label>
            <input type="text" name="country"
                   class="w-full border rounded p-2"
                   value="{{ old('country', $property->country) }}">
        </div>

        {{-- STELLE --}}
        <div class="mb-4">
            <label class="block mb-2 font-semibold">Stelle</label>
            <input type="number" name="stars" min="0" max="5"
                   class="w-full border rounded p-2"
                   value="{{ old('stars', $property->stars) }}">
        </div>

        {{-- TIPOLOGIA --}}
        <div class="mb-4">
            <label class="block mb-2 font-semibold">Tipo struttura</label>
            <input type="text" name="property_type"
                   class="w-full border rounded p-2"
                   value="{{ old('property_type', $property->property_type) }}">
        </div>

        {{-- DESCRIZIONE --}}
        <div class="mb-4">
            <label class="block mb-2 font-semibold">Descrizione</label>
            <textarea name="description" rows="5"
                      class="w-full border rounded p-2">{{ old('description', $property->description) }}</textarea>
        </div>

        {{-- ⭐ ICS URL --}}
        <div class="mb-6">
            <label class="block mb-2 font-semibold">ICS URL (Airbnb / Booking.com)</label>
            <input type="text" name="ics_url"
                   class="w-full border rounded p-2"
                   value="{{ old('ics_url', $property->ics_url) }}"
                   placeholder="https://www.airbnb.com/calendar/ical/123456.ics">
        </div>

        {{-- BOTTONI --}}
        <div class="flex justify-between items-center">
            <a href="{{ route('properties.index') }}" 
               class="text-blue-600 hover:underline">
                ‹ Torna alla lista
            </a>

            <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                💾 Salva
            </button>
        </div>

    </form>

</div>
@endsection
