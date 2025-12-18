@extends('layouts.dukares-layout')

@section('content')
<div class="bg-yellow-100 border border-yellow-300 p-6 rounded">
    <h1 class="text-xl font-bold mb-2">Nessuna struttura trovata</h1>
    <p>Per usare il calendario devi prima creare almeno una struttura.</p>

    <a href="{{ route('properties.create') }}"
       class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded">
        + Crea prima struttura
    </a>
</div>
@endsection
