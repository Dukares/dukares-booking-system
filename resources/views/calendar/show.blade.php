@extends('layouts.dukares-layout')

@section('content')
<div class="max-w-7xl mx-auto">

    <h1 class="text-3xl font-bold mb-6">
        Calendario – {{ $property->nome }}
    </h1>

    {{-- Navigazione mesi --}}
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('calendar.show', ['property' => $property->id, 'month' => $month - 1, 'year' => $year]) }}"
           class="px-4 py-2 bg-gray-200 rounded shadow">← Mese Precedente</a>

        <div class="text-2xl font-semibold">
            {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}
        </div>

        <a href="{{ route('calendar.show', ['property' => $property->id, 'month' => $month + 1, 'year' => $year]) }}"
           class="px-4 py-2 bg-gray-200 rounded shadow">Mese Successivo →</a>
    </div>


    {{-- Header dei giorni --}}
    <div class="grid grid-cols-7 text-center font-bold text-gray-700 mb-2">
        @foreach(['Lun','Mar','Mer','Gio','Ven','Sab','Dom'] as $dow)
            <div class="p-2 bg-gray-300 border">{{ $dow }}</div>
        @endforeach
    </div>


    {{-- Griglia calendario --}}
    <div class="grid grid-cols-7 gap-1">

        @php
            $firstDay = $start->dayOfWeekIso; // 1 = lunedì
        @endphp

        {{-- Celle vuote prima del primo giorno del mese --}}
        @for($i = 1; $i < $firstDay; $i++)
            <div class="border bg-gray-100"></div>
        @endfor

        {{-- Giorni del mese --}}
        @foreach(range(1, $end->day) as $day)

            @php
                $date = \Carbon\Carbon::create($year, $month, $day)->toDateString();
                $info = $days[$date] ?? null;

                $color = "bg-green-200"; // Libero
                if ($info && !$info->available) {
                    $color = "bg-red-300"; // Occupato
                }
            @endphp

            <div class="p-3 border rounded cursor-pointer {{ $color }}"
                 onclick="openPopup('{{ $date }}', '{{ $info->price ?? '' }}', '{{ $info->available ?? 1 }}')">

                <div class="font-bold">{{ $day }}</div>

                <div class="text-sm mt-1">
                    @if($info)
                        @if(!$info->available)
                            <span class="text-red-700 font-bold">Occupato</span>
                        @else
                            € {{ number_format($info->price, 2) }}
                        @endif
                    @else
                        <span class="text-green-700 font-semibold">Libero</span>
                    @endif
                </div>

            </div>

        @endforeach

    </div>

</div>



{{-- POPUP --}}
<div id="dayPopup"
     class="fixed inset-0 bg-black bg-opacity-40 hidden justify-center items-center z-50">

    <div class="bg-white p-6 rounded-lg shadow-lg w-96">

        <h2 class="text-2xl font-bold mb-4">Modifica giorno</h2>

        <form id="dayForm">
            @csrf

            <input type="hidden" id="property_id" value="{{ $property->id }}">
            <input type="hidden" id="date">

            <label class="block mb-2">Prezzo (€)</label>
            <input type="number" step="0.01" class="border p-2 w-full mb-4" id="price">

            <label class="block mb-2">Disponibilità</label>
            <select class="border p-2 w-full mb-4" id="available">
                <option value="1">Disponibile</option>
                <option value="0">Occupato</option>
            </select>

            <div class="flex justify-between">
                <button type="button"
                        onclick="saveDay()"
                        class="px-4 py-2 bg-blue-600 text-white rounded">
                    Salva
                </button>

                <button type="button"
                        onclick="closePopup()"
                        class="px-4 py-2 bg-gray-500 text-white rounded">
                    Chiudi
                </button>
            </div>

        </form>
    </div>

</div>



<script>
function openPopup(date, price, available) {
    document.getElementById('date').value = date;
    document.getElementById('price').value = price;
    document.getElementById('available').value = available;

    document.getElementById('dayPopup').classList.remove('hidden');
    document.getElementById('dayPopup').classList.add('flex');
}

function closePopup() {
    document.getElementById('dayPopup').classList.add('hidden');
}

function saveDay() {
    fetch("{{ route('calendar.update') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('input[name=\"_token\"]').value
        },
        body: JSON.stringify({
            property_id: document.getElementById('property_id').value,
            date: document.getElementById('date').value,
            price: document.getElementById('price').value,
            available: document.getElementById('available').value,
        })
    })
    .then(res => res.json())
    .then(() => location.reload());
}
</script>

@endsection
