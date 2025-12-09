@extends('layouts.dukares-layout')

@section('content')

<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Channel Manager – Le mie Strutture</h1>
    <p class="text-gray-600 mt-2">
        Collega la tua struttura ai portali esterni (Airbnb, Google Calendar, VRBO…)
        inserendo l’ID annuncio e, se previsto, l’URL ICS.
    </p>
</div>

@if(session('success'))
    <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
        {{ session('success') }}
    </div>
@endif

<div class="space-y-6">

    @forelse($properties as $property)

        <div class="bg-white shadow rounded-lg p-4">

            <div class="flex justify-between items-center mb-3">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $property->name }}</h2>
                    <p class="text-gray-500 text-sm">ID Struttura: {{ $property->id }}</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                    <tr class="border-b">
                        <th class="text-left py-2">Canale</th>
                        <th class="text-left py-2">Stato</th>
                        <th class="text-left py-2">ID Annuncio</th>
                        <th class="text-left py-2">URL ICS</th>
                        <th class="text-left py-2">Azione</th>
                    </tr>
                    </thead>

                    <tbody>

                    @foreach($channels as $channel)

                        @php
                            $connection = $property->channelConnections
                                ->firstWhere('channel_id', $channel->id);
                        @endphp

                        <tr class="border-t">
                            <td class="py-2 font-semibold">{{ $channel->name }}</td>

                            <td class="py-2">
                                @if($connection && $connection->status === 'connected')
                                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Connesso</span>
                                @elseif($connection && $connection->status === 'paused')
                                    <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Pausa</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-600">Non collegato</span>
                                @endif
                            </td>

                            <td class="py-2">
                                <form method="POST" action="{{ route('host.channels.store') }}">
                                    @csrf

                                    <input type="hidden" name="property_id" value="{{ $property->id }}">
                                    <input type="hidden" name="channel_id" value="{{ $channel->id }}">

                                    <input type="text"
                                           name="external_listing_id"
                                           value="{{ $connection->external_listing_id ?? '' }}"
                                           placeholder="Es. ID Airbnb"
                                           class="w-full border-gray-300 rounded p-1 text-xs">
                            </td>

                            <td class="py-2">
                                    <input type="text"
                                           name="ics_url"
                                           value="{{ $connection->ics_url ?? '' }}"
                                           placeholder="URL ICS"
                                           class="w-full border-gray-300 rounded p-1 text-xs">
                            </td>

                            <td class="py-2">

                                    <select name="status"
                                            class="border-gray-300 rounded p-1 text-xs mb-1">
                                        <option value="disconnected" {{ !$connection || $connection->status === 'disconnected' ? 'selected' : '' }}>Disconnesso</option>
                                        <option value="connected"    {{ $connection && $connection->status === 'connected'    ? 'selected' : '' }}>Connesso</option>
                                        <option value="paused"       {{ $connection && $connection->status === 'paused'       ? 'selected' : '' }}>Pausa</option>
                                    </select>

                                    <button type="submit"
                                            class="block mt-1 px-3 py-1 rounded bg-blue-600 text-white text-xs font-semibold hover:bg-blue-700">
                                        Salva
                                    </button>

                                </form>

                            </td>
                        </tr>

                    @endforeach

                    </tbody>
                </table>
            </div>

        </div>

    @empty

        <p class="text-gray-600">Non hai ancora strutture inserite.</p>

    @endforelse

</div>

@endsection
