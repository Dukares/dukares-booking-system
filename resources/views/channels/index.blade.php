@extends('layouts.dukares-layout')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Channel Manager – Canali Disponibili</h1>

    <a href="{{ route('channels.create') }}"
       class="px-4 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700">
        + Nuovo Canale
    </a>
</div>

@if(session('success'))
    <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white shadow rounded-lg p-4">
    <table class="min-w-full text-sm">
        <thead>
        <tr class="border-b">
            <th class="text-left py-2">Nome</th>
            <th class="text-left py-2">Slug</th>
            <th class="text-left py-2">Tipo</th>
            <th class="text-left py-2">Attivo</th>
            <th class="text-left py-2">Azioni</th>
        </tr>
        </thead>
        <tbody>

        @forelse($channels as $channel)
            <tr class="border-t">
                <td class="py-2 font-semibold">{{ $channel->name }}</td>
                <td class="py-2 text-gray-500">{{ $channel->slug }}</td>
                <td class="py-2 uppercase text-xs">{{ $channel->type }}</td>
                <td class="py-2">
                    @if($channel->is_active)
                        <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Attivo</span>
                    @else
                        <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Disattivo</span>
                    @endif
                </td>
                <td class="py-2 space-x-2">

                    <a href="{{ route('channels.edit', $channel) }}"
                       class="text-blue-600 hover:underline text-sm">Modifica</a>

                    <form method="POST"
                          action="{{ route('channels.destroy', $channel) }}"
                          class="inline-block"
                          onsubmit="return confirm('Eliminare questo canale?');">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="text-red-600 hover:underline text-sm">
                            Elimina
                        </button>
                    </form>

                </td>
            </tr>
        @empty
            <tr>
                <td class="py-3 text-gray-500" colspan="5">
                    Nessun canale configurato.
                </td>
            </tr>
        @endforelse

        </tbody>
    </table>
</div>

@endsection
