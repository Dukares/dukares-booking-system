@extends('layouts.dukares-layout')

@section('content')

<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Dashboard Amministratore</h1>
    <p class="text-gray-500">Panoramica generale di DukaRes</p>
</div>

{{-- WIDGET STATISTICHE --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">

    <div class="bg-white rounded shadow p-4 border-l-4 border-blue-500">
        <h3 class="text-lg font-semibold text-gray-700">Strutture Totali</h3>
        <div class="text-3xl font-bold text-gray-900 mt-2">{{ $totProperties }}</div>
    </div>

    <div class="bg-white rounded shadow p-4 border-l-4 border-green-500">
        <h3 class="text-lg font-semibold text-gray-700">Host Registrati</h3>
        <div class="text-3xl font-bold text-gray-900 mt-2">{{ $totOwners }}</div>
    </div>

    <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-500">
        <h3 class="text-lg font-semibold text-gray-700">Prenotazioni Mese</h3>
        <div class="text-3xl font-bold text-gray-900 mt-2">{{ $totBookingsMonth }}</div>
    </div>

    <div class="bg-white rounded shadow p-4 border-l-4 border-red-500">
        <h3 class="text-lg font-semibold text-gray-700">Commissioni DukaRes</h3>
        <div class="text-3xl font-bold text-gray-900 mt-2">€ {{ number_format($totCommissioni, 2) }}</div>
    </div>

</div>

{{-- GRAFICO PRENOTAZIONI MENSILI --}}
<div class="bg-white rounded shadow p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Andamento Prenotazioni (Ultimi 12 mesi)</h2>
    <canvas id="bookingsChart" height="90"></canvas>
</div>

{{-- ULTIME PRENOTAZIONI --}}
<div class="bg-white rounded shadow p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Ultime prenotazioni</h2>

    <table class="w-full text-left text-sm">
        <thead>
            <tr class="border-b bg-gray-100">
                <th class="p-3">Struttura</th>
                <th class="p-3">Ospite</th>
                <th class="p-3">Check-in</th>
                <th class="p-3">Check-out</th>
                <th class="p-3">Totale</th>
            </tr>
        </thead>

        <tbody>
            @foreach($recentBookings as $booking)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3 font-semibold">{{ $booking->property->nome }}</td>
                <td class="p-3">{{ $booking->nome_ospite }}</td>
                <td class="p-3">{{ $booking->checkin }}</td>
                <td class="p-3">{{ $booking->checkout }}</td>
                <td class="p-3">€ {{ number_format($booking->totale, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('bookingsChart');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [{
            label: 'Prenotazioni',
            data: {!! json_encode($chartData) !!},
            borderWidth: 3,
            borderColor: '#2563eb',
            backgroundColor: 'rgba(37, 99, 235, 0.3)',
            tension: 0.4
        }]
    }
});
</script>
@endsection


