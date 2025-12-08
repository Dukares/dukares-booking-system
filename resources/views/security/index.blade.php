@extends('layouts.dukares-layout')

@section('content')
<h1 class="text-3xl font-bold mb-6">Security Center</h1>

<table class="w-full text-left border-collapse">
    <thead>
        <tr class="border-b bg-gray-100">
            <th class="p-2">Browser</th>
            <th class="p-2">OS</th>
            <th class="p-2">IP</th>
            <th class="p-2">Ultimo Utilizzo</th>
            <th class="p-2">Rischio</th>
            <th class="p-2">Stato</th>
            <th class="p-2 text-center">Azione</th>
        </tr>
    </thead>

    <tbody>
        @forelse($devices as $d)
        <tr class="border-b">
            <td class="p-2">{{ $d->browser }}</td>
            <td class="p-2">{{ $d->os }}</td>
            <td class="p-2">{{ $d->ip }}</td>
            <td class="p-2">{{ $d->last_used }}</td>
            <td class="p-2">
                @if($d->risk_level > 0)
                    <span class="text-red-600 font-bold">Alto</span>
                @else
                    <span class="text-green-600">Normale</span>
                @endif
            </td>
            <td class="p-2">
                @if($d->is_blocked)
                    <span class="text-red-600 font-bold">Bloccato</span>
                @else
                    <span class="text-green-600">Attivo</span>
                @endif
            </td>

            <td class="p-2 text-center">
                @if(!$d->is_blocked)
                    <a href="/security/block/{{ $d->id }}" class="text-red-500 font-bold">Blocca</a>
                @else
                    <a href="/security/unblock/{{ $d->id }}" class="text-blue-500 font-bold">Sblocca</a>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="p-2 text-center text-gray-500">
                Nessun dispositivo registrato
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
