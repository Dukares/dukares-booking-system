@extends('layouts.dukares-layout')

@section('content')

<div class="container mx-auto py-6">

    <h1 class="text-3xl font-bold mb-6">🏡 Le Tue Strutture</h1>

    @if(session('success'))
        <div class="p-3 mb-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6">
        <a href="{{ route('properties.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded shadow">
            ➕ Aggiungi Struttura
        </a>
    </div>

    <div class="bg-white shadow rounded p-6">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b">
                    <th class="p-3">ID</th>
                    <th class="p-3">Nome</th>
                    <th class="p-3">Proprietario</th>
                    <th class="p-3">Città</th>
                    <th class="p-3">ICS</th>
                    <th class="p-3 text-right">Azioni</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($properties as $property)
                <tr class="border-b hover:bg-gray-50">

                    <td class="p-3">{{ $property->id }}</td>

                    <td class="p-3 font-semibold">{{ $property->name }}</td>

                    <td class="p-3">
                        @if($property->owner)
                            {{ $property->owner->nome }} {{ $property->owner->cognome }}
                        @else
                            <span class="text-gray-500">N/D</span>
                        @endif
                    </td>

                    <td class="p-3">{{ $property->city ?? '—' }}</td>

                    {{-- Indica se è configurato l'ICS --}}
                    <td class="p-3">
                        @if($property->ics_url)
                            <span class="px-2 py-1 bg-green-200 text-green-800 rounded text-sm">
                                ✔️ Configurato
                            </span>
                        @else
                            <span class="px-2 py-1 bg-red-200 text-red-800 rounded text-sm">
                                ❌ No ICS
                            </span>
                        @endif
                    </td>

                    <td class="p-3 text-right">

                        {{-- ⭐ BUTTON: CHANNEL MANAGER --}}
                        <a href="{{ route('properties.channelManager', $property) }}"
                           class="px-3 py-1 bg-purple-600 text-white rounded text-sm shadow hover:bg-purple-700">
                            📡 Channel Manager
                        </a>

                        {{-- Modifica --}}
                        <a href="{{ route('properties.edit', $property) }}"
                           class="ml-2 px-3 py-1 bg-blue-600 text-white rounded text-sm shadow hover:bg-blue-700">
                            ✏️ Modifica
                        </a>

                        {{-- Elimina --}}
                        <form action="{{ route('properties.destroy', $property) }}"
                              method="POST" class="inline-block ml-2"
                              onsubmit="return confirm('Sei sicuro di voler eliminare questa struttura?');">
                            @csrf
                            @method('DELETE')

                            <button class="px-3 py-1 bg-red-600 text-white rounded text-sm shadow hover:bg-red-700">
                                🗑️ Elimina
                            </button>
                        </form>

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-3 text-center text-gray-500">
                        Nessuna struttura trovata.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
