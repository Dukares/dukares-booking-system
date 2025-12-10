@extends('layouts.dukares-layout')

@section('content')
<div class="container mx-auto py-8">

    <h1 class="text-3xl font-bold mb-6">
        📡 Channel Manager — {{ $property->name }}
    </h1>

    {{-- MESSAGGI SUCCESSO/ERRORE --}}
    @if(session('success'))
        <div class="p-3 mb-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-3 mb-4 bg-red-100 text-red-800 rounded">
            {{ session('error') }}
        </div>
    @endif

    {{-- EXPORT ICS --}}
    <div class="bg-white shadow p-6 rounded mb-6">
        <h2 class="text-2xl font-semibold mb-4">📤 Export ICS (Airbnb / Booking.com)</h2>

        <div class="flex gap-2 items-center">
            <input type="text" readonly
                   class="w-full border rounded p-2 bg-gray-100"
                   value="{{ $icsExportUrl }}"
                   id="icsLink">

            <button onclick="copyICS()"
                    class="px-4 py-2 bg-blue-600 text-white rounded">
                📋 Copia
            </button>
        </div>
    </div>

    {{-- SYNC NOW --}}
    <div class="bg-white shadow p-6 rounded mb-6">
        <h2 class="text-2xl font-semibold mb-4">🔄 Sincronizza Ora (ICS Import)</h2>

        <form method="POST" action="{{ route('properties.syncNow', $property) }}">
            @csrf
            <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                🔄 Sincronizza adesso
            </button>
        </form>
    </div>

    {{-- ⭐ CALENDARIO DISPONIBILITÀ --}}
    <div class="bg-white shadow p-6 rounded mb-6">
        <h2 class="text-2xl font-semibold mb-4">📅 Disponibilità in Tempo Reale</h2>

        <div id="calendarContainer">
            {{-- Il calendario verrà caricato via AJAX --}}
            <p>Caricamento calendario...</p>
        </div>
    </div>

    {{-- LOG ICS --}}
    <div class="bg-white shadow p-6 rounded mb-6">
        <h2 class="text-2xl font-semibold mb-4">📝 Ultimi Log ICS Import</h2>

        @if(empty($logs))
            <p class="text-gray-600">Nessun log disponibile.</p>
        @else
            <pre class="bg-gray-100 p-3 rounded text-sm overflow-auto">
@foreach($logs as $line)
{{ $line }}
@endforeach
            </pre>
        @endif
    </div>

</div>

<script>
function copyICS() {
    let copyText = document.getElementById("icsLink");
    copyText.select();
    navigator.clipboard.writeText(copyText.value);
    alert("Link ICS copiato!");
}

// CARICAMENTO CALENDARIO VIA AJAX
document.addEventListener("DOMContentLoaded", function () {
    loadCalendar(new Date().getMonth() + 1, new Date().getFullYear());
});

function loadCalendar(month, year) {
    fetch(`/calendar/month/{{ $property->id }}?month=${month}&year=${year}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById("calendarContainer").innerHTML = html;
        });
}
</script>

@endsection
