<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-6">

            <div class="bg-white shadow rounded p-6">
                <div class="text-gray-500">Utenti totali</div>
                <div class="text-3xl font-bold">{{ $totUsers }}</div>
            </div>

            <div class="bg-white shadow rounded p-6">
                <div class="text-gray-500">Proprietari</div>
                <div class="text-3xl font-bold">{{ $totOwners }}</div>
            </div>

            <div class="bg-white shadow rounded p-6">
                <div class="text-gray-500">Strutture</div>
                <div class="text-3xl font-bold">{{ $totProperties }}</div>
            </div>

            <div class="bg-white shadow rounded p-6">
                <div class="text-gray-500">Prenotazioni</div>
                <div class="text-3xl font-bold">{{ $totReservations }}</div>
            </div>

        </div>
    </div>
</x-app-layout>
