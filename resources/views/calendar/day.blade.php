@extends('layouts.dukares-layout')

@section('content')
<div class="max-w-2xl">

    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">
            Day: {{ $date->format('d/m/Y') }} – {{ $property->title }}
        </h1>

        <a href="{{ route('calendar.index', [
                'property_id' => $property->id,
                'month' => $month->format('Y-m')
            ]) }}"
           class="bg-gray-200 px-4 py-2 rounded">
            ← Back to calendar
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-300 text-red-800 p-3 rounded mb-4">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- BUSY DAY --}}
    @if($dayBookings->count() > 0)
        <div class="bg-white border rounded-lg shadow p-5">
            <h2 class="text-lg font-bold mb-3 text-red-700">Booked</h2>

            <div class="space-y-3">
                @foreach($dayBookings as $r)
                    <div class="border rounded p-3 bg-red-50">
                        <div><strong>Guest:</strong> {{ $r->guest_name }}</div>
                        <div>
                            <strong>Dates:</strong>
                            {{ $r->checkin->format('d/m/Y') }}
                            →
                            {{ $r->checkout->format('d/m/Y') }}
                        </div>
                        <div><strong>Status:</strong> {{ ucfirst($r->status) }}</div>

                        <a href="{{ route('reservations.edit', $r) }}"
                           class="inline-block mt-2 bg-blue-600 text-white px-3 py-1 rounded">
                            Edit reservation
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

    {{-- AVAILABLE DAY --}}
    @else
        <div class="bg-white border rounded-lg shadow p-5">
            <h2 class="text-lg font-bold mb-3 text-green-700">
                Available – Create reservation
            </h2>

            <form method="POST" action="{{ route('calendar.store') }}" class="space-y-4">
                @csrf

                <input type="hidden" name="property_id" value="{{ $property->id }}">
                <input type="hidden" name="checkin" id="checkin">
                <input type="hidden" name="checkout" id="checkout">

                <div>
                    <label class="block font-semibold mb-1">Guest name *</label>
                    <input type="text" name="guest_name"
                           value="{{ old('guest_name') }}"
                           class="w-full bg-white border border-gray-400 rounded-md px-3 py-2
                                  focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="e.g. John Doe"
                           required>
                </div>

                {{-- BOOKING STYLE DATE RANGE --}}
                <div>
                    <label class="block font-semibold mb-1">Dates *</label>
                    <input type="text"
                           class="date-range w-full bg-white border border-gray-400 rounded-md px-3 py-2
                                  focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Click to select Check-in → Check-out"
                           readonly
                           required>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold mb-1">Total (€)</label>
                        <input type="number" step="0.01" name="total_price"
                               value="{{ old('total_price', 0) }}"
                               class="w-full bg-white border border-gray-400 rounded-md px-3 py-2">
                    </div>

                    <div>
                        <label class="block font-semibold mb-1">Status *</label>
                        <select name="status"
                                class="w-full bg-white border border-gray-400 rounded-md px-3 py-2"
                                required>
                            <option value="confirmed" selected>confirmed</option>
                            <option value="pending">pending</option>
                            <option value="cancelled">cancelled</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-semibold mb-1">Notes</label>
                    <textarea name="notes" rows="3"
                              class="w-full bg-white border border-gray-400 rounded-md px-3 py-2"
                              placeholder="Internal notes (optional)"></textarea>
                </div>

                <button
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-md shadow">
                    Save reservation
                </button>
            </form>
        </div>
    @endif
</div>

{{-- FLATPICKR RANGE (BOOKING STYLE) --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    flatpickr(".date-range", {
        mode: "range",
        dateFormat: "Y-m-d",
        minDate: "{{ $date->format('Y-m-d') }}",
        showMonths: 2,
        onChange: function(selectedDates) {
            if (selectedDates.length === 2) {
                document.getElementById('checkin').value =
                    selectedDates[0].toISOString().split('T')[0];
                document.getElementById('checkout').value =
                    selectedDates[1].toISOString().split('T')[0];
            }
        }
    });
});
</script>
@endsection

