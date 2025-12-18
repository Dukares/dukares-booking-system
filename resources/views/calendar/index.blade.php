@extends('layouts.dukares-layout')

@section('content')
<div class="calendar-header flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">
        Calendar – {{ $property->title }}
    </h1>

    <form method="GET" class="flex gap-2 items-center">
        <select name="property_id"
                onchange="this.form.submit()"
                class="calendar-select bg-white border border-gray-400 rounded px-3 py-2">
            @foreach($properties as $p)
                <option value="{{ $p->id }}" @selected($property->id == $p->id)>
                    {{ $p->title }}
                </option>
            @endforeach
        </select>

        <input type="month"
               name="month"
               value="{{ $month->format('Y-m') }}"
               onchange="this.form.submit()"
               class="calendar-select bg-white border border-gray-400 rounded px-3 py-2">
    </form>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-300 text-green-800 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@php
    $daysInMonth = $month->daysInMonth;
    $firstDay = $month->copy()->startOfMonth(); // Carbon
@endphp

{{-- Week header --}}
<div class="grid grid-cols-7 gap-2 text-center mb-2 font-semibold">
    <div>Mon</div>
    <div>Tue</div>
    <div>Wed</div>
    <div>Thu</div>
    <div>Fri</div>
    <div>Sat</div>
    <div>Sun</div>
</div>

{{-- Calendar grid --}}
<div class="grid grid-cols-7 gap-2">
    {{-- Empty cells before first day --}}
    @for ($i = 1; $i < $firstDay->dayOfWeekIso; $i++)
        <div></div>
    @endfor

    {{-- Days --}}
    @for ($day = 1; $day <= $daysInMonth; $day++)
        @php
            $date = $month->copy()->day($day)->startOfDay();

            $booking = $reservations->first(function($r) use ($date) {
                return $date->between(
                    $r->checkin,
                    $r->checkout->copy()->subDay()
                );
            });

            $url = route('calendar.day', [
                'property_id' => $property->id,
                'date'        => $date->format('Y-m-d'),
                'month'       => $month->format('Y-m'),
            ]);
        @endphp

        <a href="{{ $url }}"
           class="calendar-day block rounded border
                  {{ $booking ? 'bg-red-100 border-red-400' : 'bg-white border-gray-300' }}
                  hover:shadow transition p-2">

            <div class="calendar-day-number font-bold text-lg mb-1">
                {{ $day }}
            </div>

            @if($booking)
                <div class="calendar-status text-sm font-semibold text-red-700">
                    {{ $booking->guest_name }}
                </div>
            @else
                <div class="calendar-status text-sm text-green-700">
                    Available
                </div>
            @endif
        </a>
    @endfor
</div>
@endsection
