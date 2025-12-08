@extends('layouts.dukares-layout')

@section('content')

<div class="container mx-auto py-6">

    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold">
                Calendario – {{ $property->name }}
            </h1>
            <p class="text-gray-600 text-sm">
                Mese di {{ $currentMonth->locale('it')->isoFormat('MMMM YYYY') }}
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('properties.calendar', ['property' => $property->id, 'month' => $prevMonth]) }}"
               class="px-3 py-1 rounded border text-sm hover:bg-gray-100">‹ Mese precedente</a>

            <a href="{{ route('properties.calendar', ['property' => $property->id, 'month' => $nextMonth]) }}"
               class="px-3 py-1 rounded border text-sm hover:bg-gray-100">Mese successivo ›</a>
        </div>
    </div>

    {{-- Legenda --}}
    <div class="flex items-center gap-6 mb-6 text-sm">
        <div class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded bg-green-400"></span> Disponibile</div>
        <div class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded bg-red-400"></span> Prenotato</div>
        <div class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded bg-gray-400"></span> Chiuso</div>
        <div class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded border border-dashed border-gray-400"></span> Fuori mese</div>
    </div>

    {{-- Intestazione --}}
    <div class="grid grid-cols-7 text-center text-xs font-semibold text-gray-600 mb-2">
        <div>Lun</div><div>Mar</div><div>Mer</div><div>Gio</div><div>Ven</div><div>Sab</div><div>Dom</div>
    </div>

    {{-- Calendario --}}
    <div class="grid grid-cols-7 gap-1 text-sm">

        @foreach($calendar as $week)
            @foreach($week as $day)

                @php
                    $entry  = $day['entry'];
                    $status = $entry->status ?? 'available';

                    $bg = 'bg-white';
                    $borderExtra = '';

                    if (!$day['in_current']) {
                        $bg = 'bg-gray-50';
                        $borderExtra = 'border-dashed';
                    } else {
                        if ($status === 'booked') $bg = 'bg-red-200';
                        elseif ($status === 'closed') $bg = 'bg-gray-300';
                        else $bg = 'bg-green-100';
                    }
                @endphp

                <div 
                    class="calendar-cell min-h-[75px] border {{ $borderExtra }} {{ $bg }} p-1 flex flex-col text-xs rounded cursor-pointer"
                    data-date="{{ $day['date']->format('Y-m-d') }}"
                    onclick="openPopup(
                        '{{ $day['date']->format('Y-m-d') }}',
                        '{{ $property->id }}',
                        '{{ $entry->price ?? '' }}',
                        '{{ $entry->min_stay ?? '' }}',
                        '{{ $status }}'
                    )">

                    <div class="flex justify-between items-center mb-1">
                        <span class="font-semibold">{{ $day['date']->day }}</span>

                        @if($entry && $entry->source)
                            <span class="text-[0.65rem] text-gray-500">{{ strtoupper($entry->source) }}</span>
                        @endif
                    </div>

                    <div class="status-label text-[0.7rem] font-semibold">
                        @if($status === 'booked')
                            <span class="text-red-700">Prenotato</span>
                        @elseif($status === 'closed')
                            <span class="text-gray-700">Chiuso</span>
                        @else
                            <span class="text-green-700">Disponibile</span>
                        @endif
                    </div>

                    <div class="price-label text-[0.7rem] text-gray-800">
                        @if($entry && $entry->price)
                            € {{ number_format($entry->price, 2, ',', '.') }}
                        @endif
                    </div>

                </div>

            @endforeach
        @endforeach

    </div>

    <div class="mt-6">
        <a href="{{ route('properties.index') }}" class="text-blue-600 text-sm hover:underline">
            ‹ Torna alla lista strutture
        </a>
    </div>

</div>

{{-- POPUP SINGOLO --}}
<div id="dayPopup" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl p-6 w-80">
        <h2 class="text-xl font-bold mb-3">Modifica giorno: <span id="popup-date"></span></h2>

        <form id="popupForm">
            <input type="hidden" name="property_id" id="popup-property-id">
            <input type="hidden" name="date" id="popup-date-value">

            <label class="block text-sm font-medium mt-2">Prezzo (€)</label>
            <input type="number" step="0.01" name="price" id="popup-price" class="w-full border rounded p-2">

            <label class="block text-sm font-medium mt-2">Minimo notti</label>
            <input type="number" name="min_stay" id="popup-min-stay" class="w-full border rounded p-2">

            <label class="block text-sm font-medium mt-2">Stato</label>
            <select name="status" id="popup-status" class="w-full border rounded p-2">
                <option value="available">Disponibile</option>
                <option value="booked">Prenotato</option>
                <option value="closed">Chiuso</option>
            </select>

            <div class="flex justify-between mt-5">
                <button type="button" onclick="closePopup()" class="px-4 py-2 bg-gray-300 rounded">Annulla</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Salva</button>
            </div>
        </form>
    </div>
</div>

{{-- POPUP RANGE --}}
<div id="rangePopup" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl p-6 w-96">
        <h2 class="text-xl font-bold mb-3">
            Modifica intervallo:
            <span id="range-start"></span> → <span id="range-end"></span>
        </h2>

        <form id="rangeForm">
            <input type="hidden" name="property_id" value="{{ $property->id }}">
            <input type="hidden" name="start_date" id="range-start-value">
            <input type="hidden" name="end_date" id="range-end-value">

            <label class="block text-sm font-medium mt-2">Prezzo (€)</label>
            <input type="number" step="0.01" name="price" class="w-full border rounded p-2">

            <label class="block text-sm font-medium mt-2">Minimo notti</label>
            <input type="number" name="min_stay" class="w-full border rounded p-2">

            <label class="block text-sm font-medium mt-2">Stato</label>
            <select name="status" class="w-full border rounded p-2">
                <option value="available">Disponibile</option>
                <option value="booked">Prenotato</option>
                <option value="closed">Chiuso</option>
            </select>

            <div class="flex justify-between mt-5">
                <button type="button" onclick="closeRangePopup()" class="px-4 py-2 bg-gray-300 rounded">Annulla</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Salva</button>
            </div>
        </form>
    </div>
</div>


{{-- JAVASCRIPT --}}
<script>
/* =====================================================
   SINGOLO GIORNO
=====================================================*/
function openPopup(date, propertyId, price, minStay, status) {
    document.getElementById('popup-date').innerText = date;
    document.getElementById('popup-date-value').value = date;
    document.getElementById('popup-property-id').value = propertyId;

    document.getElementById('popup-price').value = price ?? '';
    document.getElementById('popup-min-stay').value = minStay ?? '';
    document.getElementById('popup-status').value = status;

    document.getElementById('dayPopup').classList.remove('hidden');
}

function closePopup() {
    document.getElementById('dayPopup').classList.add('hidden');
}

document.getElementById('popupForm').addEventListener('submit', function(e) {
    e.preventDefault();

    let formData = new FormData(this);

    fetch("{{ route('calendar.updateDay') }}", {
        method: "POST",
        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
        body: formData
    })
    .then(r => r.json())
    .then(data => {

        if (data.success) {
            alert("Giorno aggiornato!");
            window.location.reload();
        }
    });
});


/* =====================================================
   RANGE SELECTION
=====================================================*/
let isSelecting = false;
let selectedCells = [];

document.querySelectorAll(".calendar-cell").forEach(cell => {

    cell.addEventListener("mousedown", function () {
        isSelecting = true;
        selectedCells = [];
        selectedCells.push(cell);
        cell.classList.add("ring-2", "ring-blue-600");
    });

    cell.addEventListener("mouseenter", function () {
        if (isSelecting) {
            selectedCells.push(cell);
            cell.classList.add("ring-2", "ring-blue-600");
        }
    });

    cell.addEventListener("mouseup", function () {
        isSelecting = false;

        if (selectedCells.length > 1) {
            openRangePopup(selectedCells);
        }

        selectedCells = [];
    });
});

document.addEventListener("mouseup", () => {
    isSelecting = false;
});

function openRangePopup(cells) {
    let dates = cells.map(c => c.getAttribute("data-date")).sort();

    document.getElementById("range-start").innerText = dates[0];
    document.getElementById("range-end").innerText = dates[dates.length - 1];

    document.getElementById("range-start-value").value = dates[0];
    document.getElementById("range-end-value").value = dates[dates.length - 1];

    document.getElementById("rangePopup").classList.remove("hidden");
}

function closeRangePopup() {
    document.getElementById("rangePopup").classList.add("hidden");
}

document.getElementById("rangeForm").addEventListener("submit", function (e) {
    e.preventDefault();

    let formData = new FormData(this);

    fetch("{{ route('calendar.updateRange') }}", {
        method: "POST",
        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert("Intervallo aggiornato!");
            window.location.reload();
        }
    });
});
</script>

@endsection
