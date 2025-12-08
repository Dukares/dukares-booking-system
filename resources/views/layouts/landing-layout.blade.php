<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DukaRes – Global Travel Assistant</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <meta name="description" content="DukaRes is the smart global travel assistant for hotel booking, flights, buses, ferries and full trip planning powered by AI.">
</head>
<body class="bg-slate-50 text-slate-800">

    {{-- TOP NAVBAR --}}
    <header class="w-full border-b bg-white/90 backdrop-blur sticky top-0 z-30">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">

            {{-- LOGO --}}
            <div class="flex items-center gap-2">
                <div class="h-9 w-9 rounded-xl bg-blue-600 flex items-center justify-center text-white font-bold">
                    DR
                </div>
                <div class="flex flex-col leading-tight">
                    <span class="font-bold text-lg text-slate-900">DukaRes</span>
                    <span class="text-xs text-slate-500">Global Travel Intelligence</span>
                </div>
            </div>

            {{-- NAVIGATION --}}
            <nav class="hidden md:flex items-center gap-6 text-sm">
                <a href="#how-it-works" class="hover:text-blue-600">How it works</a>
                <a href="#destinations" class="hover:text-blue-600">Top destinations</a>
                <a href="#ai-assistant" class="hover:text-blue-600">AI Assistant</a>
            </nav>

            {{-- ACTIONS --}}
            <div class="flex items-center gap-3 text-sm">
                <select class="border rounded-lg px-2 py-1 text-xs md:text-sm">
                    <option>EN</option>
                    <option>IT</option>
                    <option>AL</option>
                </select>
                <select class="border rounded-lg px-2 py-1 text-xs md:text-sm hidden sm:inline">
                    <option>EUR €</option>
                    <option>USD $</option>
                    <option>GBP £</option>
                </select>
                <a href="/login" class="px-3 py-1 rounded-lg border text-xs md:text-sm hover:bg-slate-100">
                    Log in
                </a>
                <a href="/register" class="px-3 py-1 rounded-lg bg-blue-600 text-white text-xs md:text-sm hover:bg-blue-700">
                    Sign up
                </a>
            </div>

        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="min-h-screen">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="mt-16 border-t bg-white">
        <div class="max-w-6xl mx-auto px-4 py-8 grid grid-cols-1 md:grid-cols-4 gap-6 text-sm">

            <div>
                <h3 class="font-semibold mb-2">DukaRes</h3>
                <p class="text-slate-500 text-xs">
                    Global AI-powered travel ecosystem for hotels, flights, transport and experiences.
                </p>
            </div>

            <div>
                <h3 class="font-semibold mb-2">Product</h3>
                <ul class="space-y-1 text-slate-500 text-xs">
                    <li><a href="#how-it-works" class="hover:text-blue-600">How it works</a></li>
                    <li><a href="#destinations" class="hover:text-blue-600">Top destinations</a></li>
                    <li><a href="#ai-assistant" class="hover:text-blue-600">AI Assistant</a></li>
                </ul>
            </div>

            <div>
                <h3 class="font-semibold mb-2">For partners</h3>
                <ul class="space-y-1 text-slate-500 text-xs">
                    <li>Property owners</li>
                    <li>Travel agencies</li>
                    <li>Transport companies</li>
                </ul>
            </div>

            <div>
                <h3 class="font-semibold mb-2">Legal</h3>
                <ul class="space-y-1 text-slate-500 text-xs">
                    <li>Terms & Conditions</li>
                    <li>Privacy Policy</li>
                    <li>Cookie Policy</li>
                </ul>
            </div>

        </div>

        <div class="border-t text-xs text-center py-3 text-slate-400">
            © {{ date('Y') }} DukaRes. All rights reserved.
        </div>
    </footer>

</body>
</html>
