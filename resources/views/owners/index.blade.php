@extends('layouts.dukares-layout')

@section('content')
<h1 class="text-3xl font-bold text-gray-700 mb-6">Proprietari</h1>

<a href="{{ route('owners.create') }}"
   class="text-blue-600 font-semibold hover:underline float-right mb-4">
   + Aggiungi Proprietario
</a>

<div class="clear-both"></div>

@if(session('success'))
    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if($owners->isEmpty())
    <div class="bg-white shadow p-5 rounded">
        Nessun proprietario presente.
    </div>
@else
    <table class="w-full bg-white shadow rounded overflow-hidden">
        <thead class="bg-gray-200 text-left">
            <tr>
                <th class="p-3">Nome</th>
                <th class="p-3">Cognome</th>
                <th class="p-3">Email</th>
                <th class="p-3">Telefono</th>
                <th class="p-3 text-right">Azioni</th>
            </tr>
        </thead>

        <tbody>
            @foreach($owners as $owner)
            <tr class="border-b">
                <td class="p-3">{{ $owner->nome }}</td>
                <td class="p-3">{{ $owner->cognome }}</td>
                <td class="p-3">{{ $owner->email }}</td>
                <td class="p-3">{{ $owner->telefono }}</td>

                <td class="p-3 text-right">
                    <a href="{{ route('owners.edit', $owner) }}"
                       class="text-blue-500 hover:underline mr-3">
                       Modifica
                    </a>

                    <form action="{{ route('owners.destroy', $owner) }}"
                          method="POST"
                          class="inline-block"
                          onsubmit="return confirm('Sei sicuro di voler eliminare questo proprietario?');">
                        @csrf
                        @method('DELETE')

                        <button class="text-red-600 hover:text-red-800 flex items-center gap-1">
    <svg xmlns="http://www.w3.org/2000/svg" 
         fill="none" 
         viewBox="0 0 24 24" 
         stroke-width="1.5" 
         stroke="currentColor" 
         class="w-5 h-5">
        <path stroke-linecap="round" 
              stroke-linejoin="round" 
              d="M6 7.5h12M9.75 7.5V4.5c0-.414.336-.75.75-.75h3c.414 0 .75.336.75.75v3M10.5 11.25v6M13.5 11.25v6M4.5 7.5h15l-.75 12.75a1.5 1.5 0 01-1.5 1.5H6.75a1.5 1.5 0 01-1.5-1.5L4.5 7.5z" />
    </svg>
    Elimina
</button>

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





