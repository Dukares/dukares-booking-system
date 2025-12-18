@extends('layouts.dukares-layout')

@section('content')
<div class="max-w-5xl">

    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Reservations</h1>

        {{-- ✅ LINK CORRETTO --}}
        <a href="{{ route('reservations.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold">
            ➕ New Reservation
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($reservations->count() === 0)
        <div class="bg-white border rounded p-6 text-gray-600">
            No reservations found.
        </div>
    @else
        <div class="bg-white border rounded shadow overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 border">Property</th>
                        <th class="p-3 border">Guest</th>
                        <th class="p-3 border">Check-in</th>
                        <th class="p-3 border">Check-out</th>
                        <th class="p-3 border">Status</th>
                        <th class="p-3 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations as $r)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border">
                                {{ $r->property->title ?? '—' }}
                            </td>
                            <td class="p-3 border">
                                {{ $r->guest_name }}
                            </td>
                            <td class="p-3 border">
                                {{ $r->checkin->format('d/m/Y') }}
                            </td>
                            <td class="p-3 border">
                                {{ $r->checkout->format('d/m/Y') }}
                            </td>
                            <td class="p-3 border">
                                {{ ucfirst($r->status) }}
                            </td>
                            <td class="p-3 border">
                                <a href="{{ route('reservations.edit', $r) }}"
                                   class="text-blue-600 hover:underline">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
