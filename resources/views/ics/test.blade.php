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

<form method="POST" enctype="multipart/form-data" 
      action="{{ route('ics.test.run') }}"
      class="bg-white shadow p-6 rounded-lg max-w-xl">

    @csrf

    <div class="mb-4">
        <label class="font-semibold">ID Struttura *</label>
        <input type="number" name="property_id" 
               required
               class="w-full border rounded p-2"
               placeholder="Es: 1">
    </div>

    <div class="mb-6">
        <label class="font-semibold">Carica file ICS *</label>
        <input type="file" name="ics_file" 
               accept=".ics"
               required
               class="w-full border rounded p-2">
    </div>

    <button 
        type="submit"
        class="px-4 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700">
        Importa ICS
    </button>

</form>

@endsection
