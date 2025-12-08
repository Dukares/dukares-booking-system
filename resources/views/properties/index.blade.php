@extends('layouts.dukares-layout')

@section('content')
<div class="container mx-auto py-6">

    <h1 class="text-3xl font-bold mb-6">Lista Proprietà</h1>

    <a href="{{ route('properties.create') }}"
       class="px-4 py-2 bg-blue-600 text-white rounded mb-4 inline-block">
        + Aggiungi Proprietà
    </a>

    @if($properties->isEmpty())
        <p class="text-gray-600">Nessuna proprietà registrata.</p>
    @else
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 border">ID</th>
                    <th class="p-2 border">Nome</th>
                    <th class="p-2 border">Indirizzo</th>
                    <th class="p-2 border">Azioni</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($properties as $property)
                    <tr>
                        <td class="p-2 border">{{ $property->id }}</td>
                        <td class="p-2 border">{{ $property->nome }}</td>
                        <td class="p-2 border">{{ $property->indirizzo }}</td>

                        <td class="p-2 border">
                            {{-- DETTAGLI --}}
                            <a href="{{ route('properties.show', $property) }}"
                               class="text-blue-600 mr-3">
                                Dettagli
                            </a>

                            {{-- MODIFICA --}}
                            <a href="{{ route('properties.edit', $property) }}"
                               class="text-green-600 mr-3">
                                Modifica
                            </a>

                            {{-- ⭐ CALENDARIO PMS (NUOVO LINK) --}}
                            <a href="{{ route('properties.calendar', $property->id) }}"
                               class="text-purple-600 mr-3">
                                Calendario PMS
                            </a>

                            {{-- ELIMINA --}}
                            <form action="{{ route('properties.destroy', $property) }}"
                                  method="POST"
                                  class="inline-block"
                                  onsubmit="return confirm('Sei sicuro di voler eliminare questa proprietà?');">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">
                                    Elimina
                                </button>
                            </form>

                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    @endif

</div>
@endsection
