@php
use Carbon\Carbon;

$firstDay = Carbon::create($year, $month, 1);
$lastDay  = $firstDay->copy()->endOfMonth();
$daysInMonth = $lastDay->day;

$monthName = ucfirst($firstDay->locale('it')->translatedFormat('F Y'));

// Giorno della settimana (1 = Lun, 7 = Dom)
$startWeekDay = $firstDay->dayOfWeekIso;
@endphp

{{-- Navigazione mesi --}}
<div class="flex justify-between items-center mb-4">
    <button onclick="loadCalendar({{ $month - 1 }}, {{ $year }})"
            class="px-3 py-1 bg-gray-200 rounded shadow">
        ‹ Precedente
    </button>

    <h3 class="text-xl font-bold">{{ $monthName }}</h3>

    <button onclick="loadCalendar({{ $month + 1 }}, {{ $year }})"
            class="px-3 py-1 bg-gray-200 rounded shadow">
        Successivo ›
    </button>
</div>

{{-- Giorni della settimana --}}
<div class="grid grid-cols-7 gap-2 text-center font-semibold">
    <div>Lun</div>
    <div>Mar</div>
    <div>Mer</div>
    <div>Gio</div>
    <div>Ven</div>
    <div>Sab</div>
    <div>Dom</div>
</div>

{{-- Spazi vuoti fino al primo giorno --}}
<div class="grid grid-cols-7 gap-2 mt-2 text-center">
    @for ($i = 1; $i < $startWeekDay; $i++)
        <div></div>
    @endfor

    {{-- Giorni del mese --}}
    @for ($d = 1; $d <= $daysInMonth; $d++)
        @php
            $date = Carbon::create($year, $month, $d)->format('Y-m-d');
            $info = $days[$date] ?? null;

            if ($info?->reservation_id) {
                $color = "bg-red-500 text-white";
                $label = "Occupato";
            } elseif ($info?->is_blocked) {
                $color = "bg-yellow-300";
                $label = "Bloccato";
            } else {
                $color = "bg-green-300";
                $label = "Libero";
            }
        @endphp

        <div class="p-2 rounded shadow text-sm {{ $color }}">
            <div class="font-bold">{{ $d }}</div>
        </div>
    @endfor
</div>
