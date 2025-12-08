@extends('layouts.dukares-layout')

@section('content')

<h1 class="text-2xl font-bold mb-4">
    Calendario – {{ $property->nome }}
</h1>

<div class="flex gap-6">

    <!-- LEFT: CALENDARIO -->
    <div class="bg-white shadow rounded p-4 w-3/4 overflow-x-auto">

        <!-- HEADER MESE -->
        <div class="flex justify-between items-center mb-4 border-b pb-3">
            <h2 class="text-xl font-semibold">
                {{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}
            </h2>

            <div class="flex gap-2">
                <a href="?month={{ \Carbon\Carbon::parse($month)->subMonth()->format('Y-m') }}"
                   class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded border">←</a>

                <a href="?month={{ \Carbon\Carbon::parse($month)->addMonth()->format('Y-m') }}"
                   class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded border">→</a>
            </div>
        </div>

        <!-- CALENDARIO -->
        <table class="w-full border text-sm">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="p-2 border text-left bg-gray-100 font-semibold">Camera</th>

                    @for ($d=1; $d <= $daysInMonth; $d++)
                        @php
                            $dayName = \Carbon\Carbon::parse("$month-$d")->format('D');
                        @endphp

                        <th class="p-1 border text-center text-gray-600 font-medium">
                            {{ $d }}
                            <div class="text-xs text-gray-400 -mt-1">{{ $dayName }}</div>
                        </th>
                    @endfor
                </tr>
            </thead>

            <tbody>
                @foreach ($rooms as $room)
                <tr class="hover:bg-gray-50">
                    <td class="p-2 font-semibold border bg-gray-50">{{ $room->nome }}</td>

                    @for ($d=1; $d <= $daysInMonth; $d++)
                        @php
                            $date = \Carbon\Carbon::parse("$month-$d")->format('Y-m-d');
                            $today = \Carbon\Carbon::today()->format('Y-m-d');
                            $isToday = $today === $date;

                            $dayPrice = $pricing[$room->id]->firstWhere('data', $date) ?? null;

                            // Colori Booking-like
                            if(!$dayPrice) {
                                $cellClass = 'bg-amber-100 hover:bg-amber-200'; // prezzo mancante
                            } elseif ($dayPrice->disponibilita == 0) {
                                $cellClass = 'bg-red-100 hover:bg-red-200'; // chiuso
                            } else {
                                $cellClass = 'hover:bg-blue-100'; // aperto
                            }

                            if ($isToday) {
                                $cellClass .= ' ring-2 ring-blue-600 ring-inset';
                            }
                        @endphp

                        <td
                            class="calendar-cell border p-1 text-center cursor-pointer {{ $cellClass }}"
                            data-room-id="{{ $room->id }}"
                            data-date="{{ $date }}"
                            data-prezzo1="{{ $dayPrice->prezzo ?? '' }}"
                            data-prezzo_adulto="{{ $dayPrice->prezzo_adulto ?? '' }}"
                            data-prezzo_bambino="{{ $dayPrice->prezzo_bambino ?? '' }}"
                        >
                            @if($dayPrice)
                                <span class="font-semibold text-blue-800">
                                    €{{ $dayPrice->prezzo }}
                                </span>

                                <div class="text-xs text-gray-700 mt-1 leading-tight">
                                    A: €{{ $dayPrice->prezzo_adulto ?? $dayPrice->prezzo }}<br>
                                    B: €{{ $dayPrice->prezzo_bambino ?? $dayPrice->prezzo }}
                                </div>

                                <div class="text-xs text-gray-600 mt-1">
                                    Min {{ $dayPrice->minimo_soggiorno }}
                                </div>

                                @if($dayPrice->disponibilita == 0)
                                    <div class="text-xs text-red-700 font-bold">CHIUSO</div>
                                @endif

                            @else
                                <span class="text-amber-700 font-semibold">—</span>
                            @endif
                        </td>

                    @endfor
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- RIGHT: PANNELLO EDIT -->
    <div class="w-1/4 bg-white shadow rounded p-4 border">

        <h2 class="text-xl font-bold mb-3">Modifica giorni</h2>

        <form method="POST" action="{{ route('calendar.updateRange') }}">
            @csrf

            <input type="hidden" id="room_id" name="room_id">
            <input type="hidden" id="dates" name="dates">

            <div class="mb-3">
                <label class="font-semibold">Date selezionate</label>
                <div id="dates_label" class="text-sm text-gray-600">
                    Nessuna data selezionata
                </div>
            </div>

            <div class="mb-3">
                <label class="font-semibold">Prezzo (€)</label>
                <input type="number" class="w-full border p-1 rounded" id="prezzo" name="prezzo">
            </div>

            <div class="mb-3">
                <label class="font-semibold">Prezzo per adulto</label>
                <input type="number" class="w-full border p-1 rounded" id="prezzo_adulto" name="prezzo_adulto">
            </div>

            <div class="mb-3">
                <label class="font-semibold">Prezzo per bambino</label>
                <input type="number" class="w-full border p-1 rounded" id="prezzo_bambino" name="prezzo_bambino">
            </div>

            <div class="mb-3">
                <label class="font-semibold">Minimo soggiorno</label>
                <input type="number" class="w-full border p-1 rounded" id="minimo_soggiorno" name="minimo_soggiorno">
            </div>

            <div class="mb-3">
                <label class="font-semibold">Disponibilità</label>
                <select id="disponibilita" name="disponibilita" class="w-full border p-1 rounded">
                    <option value="1">Aperto</option>
                    <option value="0">Chiuso</option>
                </select>
            </div>

            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded mt-4">
                Salva modifiche
            </button>
        </form>

    </div>
</div>


<!-- SCRIPT COMPLETO -->
<script>
let isSelecting = false;
let selectedRoomId = null;
let selectedDates = [];

function updateSelectionFields() {
    const inputDates = document.getElementById('dates');
    const label = document.getElementById('dates_label');

    inputDates.value = selectedDates.join(',');

    if (selectedDates.length === 0) {
        label.textContent = 'Nessuna data selezionata';
    } else if (selectedDates.length === 1) {
        label.textContent = selectedDates[0];
    } else {
        label.textContent =
            selectedDates[0] + ' → ' +
            selectedDates[selectedDates.length - 1] +
            ` (${selectedDates.length} giorni)`;
    }
}

function clearSelection() {
    document.querySelectorAll('.calendar-cell').forEach(cell => {
        cell.classList.remove('bg-blue-200', 'border-blue-600');
    });
}

function addDate(cell) {
    const roomId = cell.dataset.roomId;
    const date = cell.dataset.date;

    if (!selectedRoomId) {
        selectedRoomId = roomId;
        document.getElementById('room_id').value = roomId;
    }

    if (roomId !== selectedRoomId) return;

    if (!selectedDates.includes(date)) {
        selectedDates.push(date);
        selectedDates.sort();
    }

    cell.classList.add('bg-blue-200', 'border-blue-600');
    updateSelectionFields();
}

document.querySelectorAll('.calendar-cell').forEach(cell => {

    cell.addEventListener('mousedown', function(e) {
        e.preventDefault();
        isSelecting = true;

        selectedRoomId = null;
        selectedDates = [];
        clearSelection();
        addDate(this);

        document.getElementById('prezzo').value = this.dataset.prezzo1 || '';
        document.getElementById('prezzo_adulto').value = this.dataset.prezzo_adulto || '';
        document.getElementById('prezzo_bambino').value = this.dataset.prezzo_bambino || '';
    });

    cell.addEventListener('mouseover', function() {
        if (!isSelecting) return;
        addDate(this);
    });
});

document.addEventListener('mouseup', () => isSelecting = false);
</script>

@endsection

