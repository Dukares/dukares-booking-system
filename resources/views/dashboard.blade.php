@extends('layouts.dukares-layout')

@section('content')

<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">
        Dashboard Amministratore
    </h1>
    <p class="text-gray-500">
        Panoramica generale di DukaRes
    </p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-gray-500">Strutture</h3>
        <p class="text-3xl font-bold">{{ $totProperties }}</p>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-gray-500">Proprietari</h3>
        <p class="text-3xl font-bold">{{ $totOwners }}</p>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-gray-500">Prenotazioni</h3>
        <p class="text-3xl font-bold">{{ $totReservations }}</p>
    </div>

</div>

@endsection
