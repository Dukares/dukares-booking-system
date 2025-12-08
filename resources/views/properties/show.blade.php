@extends('layouts.dukares-layout')

@section('content')
<div class="container mx-auto py-6">

    <h1 class="text-3xl font-bold mb-4">
        Dettagli Struttura – {{ $property->name }}
    </h1>

    <div class="bg-white shadow rounded p-6">

        <p><strong>Nome:</strong> {{ $property->name }}</p>
        <p><strong>Indirizzo:</strong> {{ $property->address }}</p>
        <p><strong>Città:</strong> {{ $property->city }}</p>
        <p><strong>Nazione:</strong> {{ $property->country }}</p>

        <p><strong>Stelle:</strong> {{ $property->stars ?? '-' }}</p>
        <p><strong>Tipo Struttura:</strong> {{ $property->property_type ?? '-' }}</p>

        <hr class="my-4">

        <a href="{{ route('properties.index') }}" class="text-blue-600">
            ‹ Torna alla lista
        </a>

        <br><br>

        <a href="{{ route('properties.calendar', $property) }}"
           class="px-4 py-2 bg-indigo-600 text-white rounded">
            📅 Apri Calendario PMS
        </a>

    </div>

</div>
@endsection
