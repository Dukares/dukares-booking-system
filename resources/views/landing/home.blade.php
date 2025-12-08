@extends('layouts.landing-layout')

@section('content')

    {{-- HERO + SEARCH --}}
    <section class="bg-gradient-to-br from-blue-600 via-blue-500 to-indigo-600 text-white">
        <div class="max-w-6xl mx-auto px-4 py-12 md:py-16 grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            
            <div>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4">
                    Your smart travel assistant for the whole world.
                </h1>
                <p class="text-sm md:text-base text-blue-100 mb-6">
                    Hotels, flights, buses, ferries and experiences powered by AI.  
                    Ask DukaRes where you want to go – we’ll do the rest.
                </p>

                {{-- AI PROMPT SHORTCUT --}}
                <div class="bg-white/10 rounded-2xl p-3 mb-4 text-xs md:text-sm">
                    <p class="mb-1 text-blue-100">Try asking:</p>
                    <div class="flex flex-wrap gap-2">
                        <button class="px-3 py-1 rounded-full bg-white/15 hover:bg-white/25">
                            “Find me a weekend trip under €200”
                        </button>
                        <button class="px-3 py-1 rounded-full bg-white/15 hover:bg-white/25">
                            “Best hotels in Tirana for 2 nights”
                        </button>
                    </div>
                </div>
            </div>

            {{-- SEARCH CARD --}}
            <div class="bg-white rounded-2xl shadow-xl p-5 text-slate-900">
                <p class="text-xs font-semibold text-blue-600 mb-2">Global search</p>

                <form class="space-y-3">
                    <div>
                        <label class="text-xs font-semibold text-slate-600">Where do you want to go?</label>
                        <input type="text" name="destination" placeholder="City, region, country"
                               class="mt-1 w-full border rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-semibold text-slate-600">Check-in</label>
                            <input type="date" name="checkin"
                                   class="mt-1 w-full border rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-600">Check-out</label>
                            <input type="date" name="checkout"
                                   class="mt-1 w-full border rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-semibold text-slate-600">Guests</label>
                            <input type="number" name="guests" value="2" min="1"
                                   class="mt-1 w-full border rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-600">Type</label>
                            <select name="type"
                                    class="mt-1 w-full border rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option>Stays (hotels, apartments)</option>
                                <option>Flights</option>
                                <option>Buses</option>
                                <option>Ferries</option>
                            </select>
                        </div>
                    </div>

                    <button type="button"
                            class="w-full mt-2 bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-xl text-sm font-semibold">
                        Search with DukaRes
                    </button>

                    <p class="text-[11px] text-slate-400 mt-1">
                        Soon: fully AI-powered results and voice search.
                    </p>
                </form>
            </div>

        </div>
    </section>

    {{-- TOP DESTINATIONS --}}
    <section id="destinations" class="max-w-6xl mx-auto px-4 py-10 md:py-14">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl md:text-2xl font-bold text-slate-900">Popular destinations worldwide</h2>
            <span class="text-xs text-slate-500">Powered by real data in the next phase</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5">
            @foreach($popularDestinations as $dest)
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition shadow-slate-200">
                    <div class="h-32 md:h-36 w-full overflow-hidden">
                        <img src="{{ $dest['image'] }}" alt="{{ $dest['city'] }}"
                             class="w-full h-full object-cover">
                    </div>
                    <div class="p-3">
                        <p class="font-semibold text-sm text-slate-900">{{ $dest['city'] }}</p>
                        <p class="text-xs text-slate-500 mb-1">{{ $dest['country'] }}</p>
                        <p class="text-xs text-slate-600">
                            From <span class="font-semibold text-blue-600">€{{ $dest['from_price'] }}</span> / night
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- HOW IT WORKS --}}
    <section id="how-it-works" class="bg-white border-y">
        <div class="max-w-6xl mx-auto px-4 py-10 md:py-14">
            <h2 class="text-xl md:text-2xl font-bold text-slate-900 mb-6">How DukaRes works</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                <div class="bg-slate-50 rounded-2xl p-4">
                    <p class="text-xs font-semibold text-blue-600 mb-1">1. Tell us your plan</p>
                    <p class="text-slate-700">
                        Type or say what you want: destination, dates, budget, who is travelling with you.
                    </p>
                </div>
                <div class="bg-slate-50 rounded-2xl p-4">
                    <p class="text-xs font-semibold text-blue-600 mb-1">2. AI builds your trip</p>
                    <p class="text-slate-700">
                        Our AI compares hotels, flights, buses, ferries and experiences to design the best options.
                    </p>
                </div>
                <div class="bg-slate-50 rounded-2xl p-4">
                    <p class="text-xs font-semibold text-blue-600 mb-1">3. Book in one place</p>
                    <p class="text-slate-700">
                        You confirm the plan and book everything directly inside DukaRes – one platform, global coverage.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- AI ASSISTANT PREVIEW --}}
    <section id="ai-assistant" class="max-w-6xl mx-auto px-4 py-10 md:py-14">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">

            <div>
                <h2 class="text-xl md:text-2xl font-bold text-slate-900 mb-3">
                    DukaRes AI – your personal travel brain.
                </h2>
                <p class="text-sm text-slate-600 mb-3">
                    Soon you’ll speak with DukaRes in your language, ask anything about the world
                    and instantly get trips planned with hotels, flights and transport ready to book.
                </p>

                <ul class="text-sm text-slate-600 space-y-1">
                    <li>• Voice and text search</li>
                    <li>• Multi-language support</li>
                    <li>• Smart recommendations</li>
                    <li>• Real-time global data</li>
                </ul>
            </div>

            <div class="bg-white rounded-2xl shadow-md p-4">
                <p class="text-xs font-semibold text-blue-600 mb-2">Preview – AI chat (coming soon)</p>

                <div class="space-y-3 text-xs">
                    <div class="bg-slate-100 rounded-xl p-2">
                        <p class="font-semibold text-slate-700 mb-1">You</p>
                        <p>“Find me a 3-day trip from Rome in March, under €300, somewhere warm.”</p>
                    </div>

                    <div class="bg-blue-50 rounded-xl p-2">
                        <p class="font-semibold text-slate-700 mb-1">DukaRes AI</p>
                        <p>
                            “I found 4 perfect options: Barcelona, Lisbon, Tirana and Malta.  
                            All include flights + stays under €300. Would you like to see them?”
                        </p>
                    </div>
                </div>

                <button class="mt-4 w-full border border-dashed border-blue-400 rounded-xl py-2 text-xs text-blue-600">
                    AI Assistant integration – next phase
                </button>

            </div>

        </div>
    </section>

@endsection
