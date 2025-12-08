@extends('layouts.dukares-layout')

@section('content')
<div class="container mx-auto py-6">

    {{-- Banner modalità OFFLINE EMERGENZA --}}
    <div id="offlineBanner"
         class="hidden mb-4 p-3 rounded-lg border border-yellow-400 bg-yellow-50 text-sm text-yellow-800 flex items-center justify-between">
        <div>
            <strong>Modalità emergenza offline (solo lettura)</strong><br>
            <span>
                Stai visualizzando i dati già caricati.  
                Per aggiornare prezzi, disponibilità o fare modifiche, riconnettiti a Internet.
            </span>
        </div>
        <button type="button" onclick="document.getElementById('offlineBanner').classList.add('hidden')"
                class="ml-4 text-xs uppercase tracking-wide">
            Chiudi
        </button>
    </div>

    {{-- Testata stile Booking / PMS --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold">
                Calendario – {{ $property->name ?? $property->nome ?? 'Struttura' }}
            </h1>
            <p class="text-gray-600 text-sm">
                Mese di {{ $currentMonth->locale('it')->isoFormat('MMMM YYYY') }}
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('properties.calendar', ['property' => $property->id, 'month' => $prevMonth]) }}"
               class="px-3 py-1 rounded-full border text-sm hover:bg-gray-100 flex items-center gap-1">
                ‹ <span>Prec.</span>
            </a>
            <a href="{{ route('properties.calendar', ['property' => $property->id, 'month' => $nextMonth]) }}"
               class="px-3 py-1 rounded-full border text-sm hover:bg-gray-100 flex items-center gap-1">
                <span>Succ.</span> ›
            </a>
        </div>
    </div>

    {{-- Legenda stile gestionale --}}
    <div class="flex flex-wrap items-center gap-4 mb-4 text-sm">
        <div class="flex items-center gap-1">
            <span class="inline-block w-3 h-3 rounded bg-green-400"></span> Disponibile
        </div>
        <div class="flex items-center gap-1">
            <span class="inline-block w-3 h-3 rounded bg-red-400"></span> Prenotato
        </div>
        <div class="flex items-center gap-1">
            <span class="inline-block w-3 h-3 rounded bg-gray-400"></span> Chiuso
        </div>
        <div class="flex items-center gap-1">
            <span class="inline-block w-3 h-3 rounded border border-dashed border-gray-400"></span> Fuori mese
        </div>
        <div class="flex items-center gap-1 text-xs text-gray-500">
            Fonte: DUKARES / BOOKING / AIRBNB...
        </div>
    </div>

    {{-- Intestazione giorni settimana --}}
    <div class="grid grid-cols-7 text-center text-xs font-semibold text-gray-600 mb-2">
        <div>Lun</div>
        <div>Mar</div>
        <div>Mer</div>
        <div>Gio</div>
        <div>Ven</div>
        <div>Sab</div>
        <div>Dom</div>
    </div>

    {{-- Calendario stile Booking.com / PMS --}}
    <div class="grid grid-cols-7 gap-[2px] text-sm bg-gray-200 rounded-lg overflow-hidden">
        @foreach($calendar as $week)
            @foreach($week as $day)
                @php
                    $entry   = $day['entry'];
                    $status  = $entry->status ?? 'available';
                    $source  = $entry->source ?? null;

                    $bg = 'bg-white';
                    $borderExtra = '';

                    if (!$day['in_current']) {
                        $bg = 'bg-gray-50';
                        $borderExtra = 'border-dashed';
                    } else {
                        if ($status === 'booked') {
                            $bg = 'bg-red-50';
                        } elseif ($status === 'closed') {
                            $bg = 'bg-gray-100';
                        } elseif ($status === 'available') {
                            $bg = 'bg-green-50';
                        }
                    }
                @endphp

                <div class="min-h-[80px] border {{ $borderExtra }} {{ $bg }} p-1.5 flex flex-col text-[0.72rem]">
                    {{-- Riga superiore: numero giorno + fonte --}}
                    <div class="flex justify-between items-center mb-1">
                        <span class="font-semibold text-xs">
                            {{ $day['date']->day }}
                        </span>

                        @if($source)
                            <span class="px-1 py-[1px] rounded-full text-[0.6rem]
                                         @if($source === 'booking') bg-blue-100 text-blue-700
                                         @elseif($source === 'airbnb') bg-red-100 text-red-700
                                         @elseif($source === 'vrbo') bg-purple-100 text-purple-700
                                         @else bg-gray-100 text-gray-600 @endif">
                                {{ strtoupper($source) }}
                            </span>
                        @endif
                    </div>

                    {{-- Stato della giornata --}}
                    <div class="mb-1">
                        @if($status === 'booked')
                            <span class="inline-flex items-center px-1.5 py-[1px] rounded-full bg-red-200 text-red-800 text-[0.65rem] font-semibold">
                                Prenotato
                            </span>
                        @elseif($status === 'closed')
                            <span class="inline-flex items-center px-1.5 py-[1px] rounded-full bg-gray-300 text-gray-800 text-[0.65rem] font-semibold">
                                Chiuso
                            </span>
                        @else
                            <span class="inline-flex items-center px-1.5 py-[1px] rounded-full bg-green-200 text-green-800 text-[0.65rem] font-semibold">
                                Disponibile
                            </span>
                        @endif
                    </div>

                    {{-- Prezzo --}}
                    @if($entry && $entry->price)
                        <div class="text-[0.7rem] text-gray-800 font-semibold">
                            € {{ number_format($entry->price, 2, ',', '.') }}
                        </div>
                    @else
                        <div class="text-[0.7rem] text-gray-400">
                            Nessun prezzo
                        </div>
                    @endif
                </div>
            @endforeach
        @endforeach
    </div>

    <div class="mt-6">
        <a href="{{ route('properties.index') }}" class="text-blue-600 text-sm">
            ‹ Torna alla lista strutture
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Modalità emergenza offline (solo lettura)
    function updateOfflineBanner() {
        const banner = document.getElementById('offlineBanner');
        if (!banner) return;

        if (navigator.onLine) {
            banner.classList.add('hidden');
        } else {
            banner.classList.remove('hidden');
        }
    }

    window.addEventListener('load', updateOfflineBanner);
    window.addEventListener('online', updateOfflineBanner);
    window.addEventListener('offline', updateOfflineBanner);
</script>
@endsection
