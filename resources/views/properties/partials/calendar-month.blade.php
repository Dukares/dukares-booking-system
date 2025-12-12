@php
use Carbon\Carbon;

$firstDay = Carbon::create($year, $month, 1);
$lastDay  = $firstDay->copy()->endOfMonth();
$daysInMonth = $lastDay->day;

$monthName = ucfirst($firstDay->locale('it')->translatedFormat('F Y'));

// Giorno della settimana (1 = Lun, 7 = Dom)
$startWeekDay = $firstDay->dayOfWeekIso;
@endphp

{{-- 🔵 NAVIGAZIONE MESE --}}
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

{{-- 🔵 GIORNI DELLA SETTIMANA --}}
<div class="grid grid-cols-7 gap-2 text-center font-semibold">
    <div>Lun</div>
    <div>Mar</div>
    <div>Mer</div>
    <div>Gio</div>
    <div>Ven</div>
    <div>Sab</div>
    <div>Dom</div>
</div>

{{-- 🔵 CALENDARIO --}}
<div class="grid grid-cols-7 gap-2 mt-2 text-center">

    {{-- Spazi vuoti prima del 1° giorno --}}
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
            } elseif ($info?->is_blocked) {
                $color = "bg-yellow-300";
            } else {
                $color = "bg-green-300";
            }
        @endphp

        <div class="p-2 rounded shadow text-sm cursor-pointer day-cell {{ $color }}"
            data-date="{{ $date }}"
            data-property="{{ $property->id }}"
            onclick="toggleDay(this)">
            
            <div class="font-bold">{{ $d }}</div>
        </div>
    @endfor
</div>

{{-- 🔵 JAVASCRIPT PER CLICK GIORNO --}}
<script>
function toggleDay(element) {
    const date = element.dataset.date;
    const propertyId = element.dataset.property;

    fetch("/calendar/update-day", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            property_id: propertyId,
            date: date
        })
    })
    .then(res => res.json())
    .then(data => {
        // BLOCCHI OCCUPATI
        if (data.status === "occupied") {
            alert("❌ Il giorno è occupato da una prenotazione.");
            return;
        }

        // BLOCCA → GIALLO
        if (data.status === "blocked") {
            element.classList.remove("bg-green-300");
            element.classList.add("bg-yellow-300");
        } 

        // SBLOCCA → VERDE
        else if (data.status === "free") {
            element.classList.remove("bg-yellow-300");
            element.classList.add("bg-green-300");
        }
    })
    .catch(err => {
        console.error(err);
        alert("Errore durante l'aggiornamento del calendario.");
    });
}
</script>
