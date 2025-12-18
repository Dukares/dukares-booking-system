@extends('layouts.dukares-layout')

@section('content')
<div class="max-w-xl bg-white p-6 rounded-lg shadow">

    <h1 class="text-2xl font-bold mb-4">New Reservation</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-300 text-red-800 p-3 rounded mb-4">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('reservations.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block font-semibold mb-1">Property *</label>
            <select name="property_id" required
                class="w-full border border-gray-300 rounded px-3 py-2">
                @foreach($properties as $p)
                    <option value="{{ $p->id }}">{{ $p->title }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-semibold mb-1">Guest name *</label>
            <input type="text" name="guest_name" required
                class="w-full border border-gray-300 rounded px-3 py-2"
                placeholder="John Smith">
        </div>

        <div>
            <label class="block font-semibold mb-1">Dates *</label>
            <input type="text"
                   id="date_range"
                   placeholder="Click to select Check-in → Check-out"
                   class="w-full border border-gray-400 rounded px-3 py-2 bg-white cursor-pointer"
                   readonly>
        </div>

        <input type="hidden" name="checkin" id="checkin">
        <input type="hidden" name="checkout" id="checkout">

        <div>
            <label class="block font-semibold mb-1">Status</label>
            <select name="status"
                class="w-full border border-gray-300 rounded px-3 py-2">
                <option value="confirmed">Confirmed</option>
                <option value="pending">Pending</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        <div>
            <label class="block font-semibold mb-1">Notes</label>
            <textarea name="notes" rows="3"
                class="w-full border border-gray-300 rounded px-3 py-2"></textarea>
        </div>

        <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
            Save reservation
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof flatpickr === 'undefined') {
        console.error('Flatpickr NOT loaded. Check layout includes.');
        return;
    }

    flatpickr("#date_range", {
        mode: "range",
        dateFormat: "Y-m-d",
        showMonths: 2,
        minDate: "today",
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
@endpush
