@extends('layouts.dukares-layout')

@section('content')
<div class="container mx-auto py-6">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">
            ⚙️ Channel Manager – Canali Disponibili
        </h1>

        <button
            class="px-4 py-2 bg-gray-400 text-white rounded cursor-not-allowed"
            title="Funzione in arrivo">
            ➕ Nuovo Canale
        </button>
    </div>

    <div class="bg-white rounded shadow p-6">
        <table class="w-full">
            <thead>
                <tr class="border-b">
                    <th>Nome</th>
                    <th>Slug</th>
                    <th>Tipo</th>
                    <th>Attivo</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5" class="text-center text-gray-500 py-6">
                        Nessun canale configurato.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
@endsection
