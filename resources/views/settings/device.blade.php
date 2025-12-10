@extends('layouts.dukares-layout')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white shadow p-6 rounded-lg">

    <h1 class="text-2xl font-bold mb-4">Dettagli dispositivo</h1>

    <p><strong>Browser:</strong> {{ $device->browser }}</p>
    <p><strong>Sistema:</strong> {{ $device->os }}</p>
    <p><strong>IP:</strong> {{ $device->ip }}</p>
    <p><strong>Fingerprint:</strong> {{ $device->device_fingerprint }}</p>
    <p><strong>Ultimo accesso:</strong> {{ $device->last_used_at }}</p>

    <div class="mt-6 flex gap-3">
        <form method="POST" action="{{ route('settings.device.trust', $device->id) }}">
            @csrf
            <button class="bg-green-600 text-white px-4 py-2 rounded">
                Segna come fidato
            </button>
        </form>

        <form method="POST" action="{{ route('settings.device.delete', $device->id) }}">
            @csrf
            @method('DELETE')
            <button class="bg-red-600 text-white px-4 py-2 rounded">
                Rimuovi dispositivo
            </button>
        </form>
    </div>

</div>
@endsection
