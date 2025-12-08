<div class="w-64 h-screen bg-white shadow-lg border-r">
    <div class="p-4 border-b">
        <h2 class="text-xl font-bold text-blue-700">DukaRes</h2>
        <p class="text-sm text-gray-500">{{ auth()->user()->name }}</p>
        <p class="text-xs text-gray-400">Ruolo: {{ auth()->user()->role }}</p>
    </div>

    <nav class="p-4 space-y-2">

        {{-- MENU COMUNE A TUTTI --}}
        <a href="{{ route('dashboard') }}"
           class="block p-2 rounded hover:bg-blue-100">
            📊 Dashboard
        </a>

        <a href="{{ route('reservations.index') }}"
           class="block p-2 rounded hover:bg-blue-100">
            📘 Prenotazioni
        </a>

        {{-- MENU HOST --}}
        @if(auth()->user()->role === 'host')
            <h3 class="text-xs text-gray-400 uppercase mt-4">Host</h3>

            <a href="#"
               class="block p-2 rounded hover:bg-blue-100">
                🏠 Le mie strutture
            </a>

            <a href="#"
               class="block p-2 rounded hover:bg-blue-100">
                📅 Calendario & prezzi
            </a>

            <a href="#"
               class="block p-2 rounded hover:bg-blue-100">
                💳 Pagamenti struttura
            </a>
        @endif

        {{-- MENU STAFF --}}
        @if(auth()->user()->role === 'staff')
            <h3 class="text-xs text-gray-400 uppercase mt-4">Staff</h3>

            <a href="#"
               class="block p-2 rounded hover:bg-blue-100">
                🚪 Check-in / Check-out
            </a>

            <a href="#"
               class="block p-2 rounded hover:bg-blue-100">
                💬 Messaggi Ospiti
            </a>
        @endif

        {{-- MENU ADMIN --}}
        @if(auth()->user()->role === 'admin')
            <h3 class="text-xs text-gray-400 uppercase mt-4">Admin</h3>

            <a href="{{ route('owners.index') }}"
               class="block p-2 rounded hover:bg-blue-100">
                👤 Proprietari
            </a>

            <a href="#"
               class="block p-2 rounded hover:bg-blue-100">
                🏨 Strutture
            </a>

            <a href="{{ route('payment-methods.index') }}"
               class="block p-2 rounded hover:bg-blue-100">
                💳 Metodi di pagamento
            </a>

            <a href="{{ route('admin.owner.settings.edit') }}"
               class="block p-2 rounded hover:bg-blue-100">
                ⚙️ Impostazioni DukaRes
            </a>

            <a href="#"
               class="block p-2 rounded hover:bg-blue-100">
                🌍 Canali OTA / API
            </a>
        @endif

        {{-- LOGOUT --}}
        <form method="POST" action="/logout" class="mt-6">
            @csrf
            <button class="w-full p-2 text-left rounded hover:bg-red-100 text-red-600">
                🔴 Logout
            </button>
        </form>
    </nav>
</div>
