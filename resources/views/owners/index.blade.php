@extends('layouts.dukares-layout')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">👤 Proprietari</h1>

    <a href="{{ route('owners.create') }}"
       class="px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700">
        ➕ Aggiungi Proprietario
    </a>
</div>

@if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded shadow p-4">
    <table class="w-full">
        <thead>
            <tr class="border-b">
                <th class="p-2">ID</th>
                <th class="p-2">Nome</th>
                <th class="p-2">Email</th>
                <th class="p-2">Telefono</th>
            </tr>
        </thead>
        <tbody>
            @forelse($owners as $owner)
                <tr class="border-b">
                    <td class="p-2">{{ $owner->id }}</td>
                    <td class="p-2">{{ $owner->name }}</td>
                    <td class="p-2">{{ $owner->email }}</td>
                    <td class="p-2">{{ $owner->phone }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-4 text-center text-gray-500">
                        Nessun proprietario trovato.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
