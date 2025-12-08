@extends('layouts.dukares-layout')

@section('content')
<div class="max-w-4xl mx-auto mt-10">

    <h1 class="text-3xl font-bold mb-6">Sicurezza Account</h1>

    @if(session('message'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <!-- Sezione Passkey -->
    <div class="bg-white shadow p-6 rounded-lg mb-8">
        <h2 class="text-xl font-bold mb-4">Passkey registrate</h2>

        @if($passkeys->count() == 0)
            <p class="text-gray-500">Nessuna passkey registrata.</p>
        @else
            <ul class="space-y-3">
                @foreach($passkeys as $pk)
                    <li class="border p-3 rounded-lg">
                        <strong>ID:</strong> {{ $pk->id }} <br>
                        <strong>Attivo:</strong> {{ $pk->enabled ? 'Sì' : 'No' }}
                    </li>
                @endforeach
            </ul>
        @endif

        <a href="/passkey/register" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded">
            Aggiungi nuova Passkey
        </a>
    </div>

    <!-- Sezione Dispositivi -->
    <div class="bg-white shadow p-6 rounded-lg">
        <h2 class="text-xl font-bold mb-4">Dispositivi riconosciuti</h2>

        @if($devices->count() == 0)
            <p class="text-gray-500">Nessun dispositivo registrato.</p>
        @else
            <ul class="divide-y">
                @foreach($devices as $device)
                    <li class="py-4 flex justify-between items-center">
                        <div>
                            <strong>{{ $device->browser }} – {{ $device->os }}</strong><br>
                            <span class="text-sm text-gray-500">Ultimo accesso: {{ $device->last_used_at }}</span>
                        </div>

                        <a href="{{ route('settings.device', $device->id) }}"
                           class="text-blue-600 underline">
                           Dettagli
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif

    </div>

</div>
@endsection
