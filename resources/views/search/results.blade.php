@extends('layouts.landing-layout')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">

    <!-- TITLE -->
    <h2 class="text-2xl font-bold mb-6 text-slate-800">
        Results for "{{ $destination }}"
    </h2>

    <!-- NO RESULTS -->
    @if($properties->isEmpty())
        <div class="p-6 bg-white shadow rounded-xl text-center">
            <p class="text-gray-600 text-lg">No properties found for your search.</p>
        </div>
    @endif

    <!-- GRID RESULTS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

        @foreach($properties as $p)
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition overflow-hidden">

                <!-- IMAGE (placeholder for now) -->
                <div class="h-40 bg-gray-200 flex items-center justify-center text-gray-400">
                    <span>No image</span>
                </div>

                <!-- CONTENT -->
                <div class="p-4">
                    <h3 class="font-semibold text-lg text-slate-800">
                        {{ $p->property_name }}
                    </h3>

                    <p class="text-sm text-slate-600 leading-tight mt-1">
                        {{ $p->property_street }} <br>
                        {{ $p->property_town }}, {{ $p->property_country }}
                    </p>

                    <p class="mt-3 text-blue-600 font-semibold text-sm">
                        From €{{ rand(25,150) }} / night
                    </p>

                    <a href="#"
                       class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                        View property
                    </a>
                </div>

            </div>
        @endforeach

    </div>

</div>
@endsection
