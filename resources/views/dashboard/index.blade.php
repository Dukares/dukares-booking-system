@extends('layouts.dukares-layout')

@section('content')

<h1 class="text-3xl font-bold mb-6">Dashboard</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- BOX 1 -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold">Totale Proprietari</h2>
        <p class="text-4xl font-bold mt-2">{{ $totOwners }}</p>
    </div>

    <!-- BOX 2 -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold">Totale Strutture</h2>
        <p class="text-4xl font-bold mt-2">{{ $totProperties }}</p>
    </div>

    <!-- BOX 3 -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold">Prenotazioni mese</h2>
        <p class="text-4xl font-bold mt-2">{{ $totBookingsMonth }}</p>
    </div>

</div>

<!-- TABELLA PRENOTAZIONI RECENTI -->
<div class="bg-white rounded-lg shadow p-6 mt-10">

    <h2 class="text-2xl font-bold mb-4">Ultime 8 prenotazioni</h2>

    <table class="w-full table-auto">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-2">Struttura</th>
                <th class="p-2">Check-in</th>
                <th class="p-2">Check-out</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($recentBookings as $booking)
            <tr class="border-b">
                <td class="p-2">{{ $booking->property->nome ?? 'N/D' }}</td>
                <td class="p-2">{{ $booking->checkin }}</td>
                <td class="p-2">{{ $booking->checkout }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

@endsection

