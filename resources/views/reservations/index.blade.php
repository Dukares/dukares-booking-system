@extends('layouts.dukares-layout')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Prenotazioni</h1>

    <a href="{{ route('reservations.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
       + Nuova Prenotazione
    </a>
</div>

@if(session('success'))
    <div class="bg-green-200 text-green-800 p-3 rounded mb-4 shadow">
        {{ session('success') }}
    </div>
@endif

@if($reservations->count() == 0)
    <p class="text-gray-500">Nessuna prenotazione registrata.</p>
@else
<table class="w-full bg-white shadow rounded-lg">
    <thead>
        <tr class="border-b bg-gray-100 text-left">
            <th class="p-3 font-semibold">Ospite</th>
            <th class="p-3 font-semibold">Struttura</th>
            <th class="p-3 font-semibold">Check-in</th>
            <th class="p-3 font-semibold">Check-out</th>
            <th class="p-3 font-semibold">Prezzo</th>
            <th class="p-3 font-semibold">Stato</th>
            <th class="p-3 font-semibold">Azioni</th>
        </tr>
    </thead>

    <tbody>
        @foreach($reservations as $r)
        <tr class="border-b hover:bg-gray-50">
            <td class="p-3">{{ $r->guest_name }}</td>
            <td class="p-3">{{ $r->property->name ?? '-' }}</td>
            <td class="p-3">{{ $r->checkin }}</td>
            <td class="p-3">{{ $r->checkout }}</td>
            <td class="p-3">{{ number_format($r->price, 2) }} €</td>
            <td class="p-3">{{ ucfirst($r->status) }}</td>

            <td class="p-3 flex gap-2">
                <a href="{{ route('reservations.edit', $r->id) }}"
                   class="text-blue-600 hover:underline">Modifica</a>

                <form method="POST" action="{{ route('reservations.destroy', $r->id) }}">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-600 hover:underline"
                            onclick="return confirm('Eliminare la prenotazione?')">
                        Elimina
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@endsection
