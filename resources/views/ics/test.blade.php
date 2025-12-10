@extends('layouts.dukares-layout')

@section('content')

<h1 class="text-3xl font-bold text-gray-800 mb-6">
    Import ICS – Test Locale
</h1>

@if(session('success'))
    <div class="mb-4 p-3 bg-green-100 rounded text-green-800">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-3 bg-red-100 rounded text-red-800">
        {{ session('error') }}
    </div>
@endif

<form method="POST" action="{{ route('ics.test.run') }}" enctype="multipart/form-data"
      style="background: white; padding: 24px; border-radius: 12px; max-width: 600px;">

    @csrf

    <div style="margin-bottom: 16px;">
        <label style="font-weight: bold;">ID Struttura *</label>
        <input type="number" name="property_id"
               required placeholder="Es: 1"
               style="border: 1px solid #ccc; padding: 8px; border-radius: 6px; width: 100%;">
    </div>

    <div style="margin-bottom: 16px;">
        <label style="font-weight: bold;">Carica file ICS *</label>
        <input type="file" name="ics_file" accept=".ics"
               required
               style="border: 1px solid #ccc; padding: 8px; border-radius: 6px; width: 100%;">
    </div>

    <div style="margin-top: 24px;">
        <button type="submit"
                style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 6px; cursor: pointer;">
            🚀 Importa ICS
        </button>
    </div>

</form>

@endsection
