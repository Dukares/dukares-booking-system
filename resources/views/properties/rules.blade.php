@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10">

    <!-- Title -->
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
        Regole della struttura – {{ $property->name }}
    </h1>

    <!-- Success message -->
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('property.rules.update', $property->id) }}">
        @csrf

        <!-- CARD 1: Orari Check-in -->
        <div class="bg-white shadow-md rounded-xl p-6 mb-6 border-l-4 border-blue-600">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Orari Check-in</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="font-medium">Check-in dalle</label>
                    <input type="time" name="checkin_from" value="{{ $rules->checkin_from }}"
                           class="w-full border-gray-300 rounded-lg mt-1">
                </div>

                <div>
                    <label class="font-medium">Check-in fino alle</label>
                    <input type="time" name="checkin_to" value="{{ $rules->checkin_to }}"
                           class="w-full border-gray-300 rounded-lg mt-1">
                </div>
            </div>
        </div>

        <!-- CARD 2: Orari Check-out -->
        <div class="bg-white shadow-md rounded-xl p-6 mb-6 border-l-4 border-blue-600">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Orari Check-out</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="font-medium">Check-out dalle</label>
                    <input type="time" name="checkout_from" value="{{ $rules->checkout_from }}"
                           class="w-full border-gray-300 rounded-lg mt-1">
                </div>

                <div>
                    <label class="font-medium">Check-out fino alle</label>
                    <input type="time" name="checkout_to" value="{{ $rules->checkout_to }}"
                           class="w-full border-gray-300 rounded-lg mt-1">
                </div>
            </div>
        </div>

        <!-- CARD 3: Bambini -->
        <div class="bg-white shadow-md rounded-xl p-6 mb-6 border-l-4 border-blue-600">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Bambini</h2>

            <label class="flex items-center gap-3">
                <input type="checkbox" name="children_allowed"
                       value="1" {{ $rules->children_allowed ? 'checked' : '' }}
                       class="h-5 w-5 text-blue-600 rounded">
                <span class="text-gray-700 text-lg">I bambini sono ammessi</span>
            </label>
        </div>

        <!-- CARD 4: Animali domestici -->
        <div class="bg-white shadow-md rounded-xl p-6 mb-6 border-l-4 border-blue-600">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Animali domestici</h2>

            <div class="space-y-2">
                <label class="flex items-center gap-3">
                    <input type="radio" name="pets_policy" value="yes"
                        {{ $rules->pets_policy === 'yes' ? 'checked' : '' }}
                        class="h-5 w-5 text-blue-600">
                    Animali ammessi
                </label>

                <label class="flex items-center gap-3">
                    <input type="radio" name="pets_policy" value="on_request"
                        {{ $rules->pets_policy === 'on_request' ? 'checked' : '' }}
                        class="h-5 w-5 text-blue-600">
                    Su richiesta
                </label>

                <label class="flex items-center gap-3">
                    <input type="radio" name="pets_policy" value="no"
                        {{ $rules->pets_policy === 'no' ? 'checked' : '' }}
                        class="h-5 w-5 text-blue-600">
                    Non ammessi
                </label>
            </div>
        </div>

        <!-- CARD 5: Fumo -->
        <div class="bg-white shadow-md rounded-xl p-6 mb-6 border-l-4 border-blue-600">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Fumo</h2>

            <label class="flex items-center gap-3">
                <input type="checkbox" name="smoking_allowed" value="1"
                       {{ $rules->smoking_allowed ? 'checked' : '' }}
                       class="h-5 w-5 text-blue-600">
                <span class="text-lg text-gray-700">È permesso fumare</span>
            </label>
        </div>

        <!-- CARD 6: Età minima -->
        <div class="bg-white shadow-md rounded-xl p-6 mb-6 border-l-4 border-blue-600">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Età minima</h2>

            <input type="number" name="min_age" min="0" max="100"
                   value="{{ $rules->min_age }}"
                   class="w-32 border-gray-300 rounded-lg">
        </div>

        <!-- CARD 7: Note aggiuntive -->
        <div class="bg-white shadow-md rounded-xl p-6 mb-6 border-l-4 border-blue-600">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Note aggiuntive</h2>

            <textarea name="additional_rules" rows="4"
                      class="w-full border-gray-300 rounded-lg">{{ $rules->additional_rules }}</textarea>
        </div>

        <!-- Submit -->
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl text-lg font-semibold shadow">
            Salva Regole
        </button>

    </form>
</div>
@endsection
