<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DukaRes Dashboard</title>

    {{-- SAFE MODE: niente Vite --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>

<body class="bg-gray-100">

{{-- POPUP SICUREZZA --}}
@if(session('security_alert'))
<div id="security-popup"
     style="position:fixed;top:20px;right:20px;background:#fff3cd;
     padding:15px 20px;border-left:6px solid #ff9800;border-radius:8px;
     box-shadow:0 2px 10px rgba(0,0,0,0.2);font-size:15px;z-index:99999;">
    <strong>⚠ Nuovo accesso rilevato</strong><br>
    <small>
        Browser: {{ session('security_alert.browser') }}<br>
        OS: {{ session('security_alert.os') }}<br>
        IP: {{ session('security_alert.ip') }}
    </small>
    <button onclick="this.parentElement.remove()"
        style="float:right;margin-top:-5px;background:none;border:none;
               font-size:18px;cursor:pointer;">
        &times;
    </button>
</div>
@endif

{{-- OFFLINE BANNER --}}
<div id="offline-banner" style="display:none;"
     class="bg-red-600 text-white p-3 text-center">
    ⚠️ Modalità Offline di Emergenza
</div>

<div class="flex">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-white shadow-md h-screen fixed left-0 top-0">

        <div class="p-6 border-b">
            <h1 class="text-2xl font-bold text-blue-600">DukaRes</h1>
        </div>

        <nav class="p-4">
            <ul class="space-y-2 text-gray-700">

                <li>
                    <a href="/dashboard"
                       class="block p-2 hover:bg-blue-100 rounded">
                        🏠 Dashboard
                    </a>
                </li>

                <li>
                    <a href="/properties"
                       class="block p-2 hover:bg-blue-100 rounded">
                        🏡 Strutture
                    </a>
                </li>

                <li>
                    <a href="/owners"
                       class="block p-2 hover:bg-blue-100 rounded">
                        👤 Proprietari
                    </a>
                </li>

                <li>
                    <a href="/reservations"
                       class="block p-2 hover:bg-blue-100 rounded">
                        📅 Prenotazioni
                    </a>
                </li>

                <li>
                    <a href="/security"
                       class="block p-2 hover:bg-blue-100 rounded">
                        🔐 Security Center
                    </a>
                </li>

                {{-- HOST --}}
                <li>
                    <a href="/host/channels"
                       class="block p-2 hover:bg-blue-100 rounded">
                        🌐 Channel Manager
                    </a>
                </li>

                {{-- ADMIN --}}
                <li>
                    <a href="/channels"
                       class="block p-2 hover:bg-blue-100 rounded">
                        ⚙️ Canali (Admin)
                    </a>
                </li>

            </ul>
        </nav>
    </aside>

    {{-- CONTENUTO --}}
    <main class="ml-64 w-full">

        {{-- NAVBAR --}}
        <header class="bg-white shadow p-4 flex justify-between items-center">
            <h2 class="text-xl font-semibold">Pannello di Controllo</h2>

            <div class="flex items-center space-x-4">
                <span class="text-gray-600">
                    Ciao, {{ Auth::user()->name ?? 'Utente' }}
                </span>

                <form method="POST" action="/logout">
                    @csrf
                    <button
                        type="submit"
                        class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                        Logout
                    </button>
                </form>
            </div>
        </header>

        {{-- PAGINE --}}
        <div class="p-6">
            @yield('content')
        </div>

    </main>
</div>

</body>
</html>
