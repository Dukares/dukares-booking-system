@extends('layouts.dukares-layout')

@section('content')

<h1 class="text-3xl font-bold mb-6">Dashboard – Le tue strutture</h1>

<p class="text-gray-600 mb-6">
    Oggi: {{ $today->format('d/m/Y') }}
</p>

{{-- KPI HOST --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">

    <div class="bg-white shadow rounded p-4 border-t-4 border-blue-500">
        <div class="text-gray-500 text-sm mb-1">Prenotazioni mese</div>
        <div class="text-2xl font-bold">{{ $totalePrenotazioniMese }}</div>
    </div>

    <div class="bg-white shadow rounded p-4 border-t-4 border-green-500">
        <div class="text-gray-500 text-sm mb-1">Fatturato mese</div>
        <div class="text-2xl font-bold">
            €{{ number_format($fatturatoMese, 2, ',', '.') }}
        </div>
    </div>

    <div class="bg-white shadow rounded p-4 border-t-4 border-indigo-500">
        <div class="text-gray-500 text-sm mb-1">Commissioni del mese</div>
        <div class="text-xl font-bold text-indigo-700">
            €{{ number_format($commissioniMese, 2, ',', '.') }}
        </div>
    </div>

    <div class="bg-white shadow rounded p-4 border-t-4 border-amber-500">
        <div class="text-gray-500 text-sm mb-1">Pagamenti in attesa</div>
        <div class="text-2xl font-bold">{{ $pagamentiInAttesa }}</div>
    </div>

</div>

{{-- ARRIVI / PARTENZE / SOGGIORNI --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

    {{-- ARRIVI --}}
    @include('dashboard.widgets.arrivi-oggi')

    {{-- PARTENZE --}}
    @include('dashboard.widgets.partenze-oggi')

    {{-- SOGGIORNI IN CORSO --}}
    @include('dashboard.widgets.soggiorni-corso')

</div>

{{-- ULTIME PRENOTAZIONI --}}
@include('dashboard.widgets.recent-reservations')

@endsection
