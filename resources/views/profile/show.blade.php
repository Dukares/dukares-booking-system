<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

            {{-- =============================
                 UPDATE PROFILE INFORMATION
            ============================== --}}
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('profile.update-profile-information-form')

                <x-section-border />
            @endif

            {{-- =============================
                 UPDATE PASSWORD
            ============================== --}}
            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>

                <x-section-border />
            @endif

            {{-- =============================
                 TWO FACTOR AUTHENTICATION
            ============================== --}}
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.two-factor-authentication-form')
                </div>

                <x-section-border />
            @endif

            {{-- =============================
                 BROWSER SESSIONS
            ============================== --}}
            <div class="mt-10 sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            {{-- =============================
                 ACCOUNT DELETION
            ============================== --}}
            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <x-section-border />

                <div class="mt-10 sm:mt-0">
                    @livewire('profile.delete-user-form')
                </div>
            @endif


            {{-- =======================================================
                 🔐 DUKARES – PASSKEY / BIOMETRICO (FIDO2)
            ======================================================= --}}
            <x-section-border />

            <div class="mt-10 p-6 bg-white shadow rounded-lg">
                <h2 class="text-lg font-medium text-gray-900">
                    🔐 Sicurezza avanzata — Passkey (FaceID / Impronta / Windows Hello)
                </h2>

                <p class="mt-2 text-sm text-gray-600">
                    Registra un metodo di accesso biometrico per entrare senza password.
                </p>

                {{-- Bottone nuova passkey --}}
                <div class="mt-6">
                    <button id="register-passkey-btn"
                            class="px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700 transition">
                        ➕ Registra una nuova Passkey
                    </button>
                </div>

                {{-- Lista passkey utente --}}
                <div class="mt-6">
                    <h3 class="font-semibold text-gray-800">Passkey registrate:</h3>

                    @if($passkeys->isEmpty())
                        <p class="text-sm text-gray-500 mt-2">Nessuna passkey registrata.</p>
                    @else
                        <ul class="mt-2 space-y-2">
                            @foreach($passkeys as $p)
                                <li class="flex items-center justify-between p-2 bg-gray-100 rounded">
                                    <span>{{ $p->name ?? 'Passkey' }} (ID: {{ $p->id }})</span>

                                    <form method="POST" action="{{ route('passkeys.destroy', $p->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:text-red-800 text-sm">
                                            Elimina
                                        </button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- =======================================================
         SCRIPT REGISTRAZIONE PASSKEY
    ======================================================= --}}
    <script type="module">
        import {
            createCredential,
        } from "/js/vendor/webauthn/webauthn.js";

        const btn = document.getElementById('register-passkey-btn');

        btn.addEventListener('click', async () => {
            try {
                // 1) Ottieni le opzioni di registrazione
                const optResp = await fetch("{{ route('passkeys.register.options') }}", {
                    method: "POST",
                    headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
                });

                const options = await optResp.json();

                // 2) Avvia registrazione biometrica
                const credential = await createCredential(options);

                // 3) Salva la passkey
                const saveResp = await fetch("{{ route('passkeys.register.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(credential),
                });

                if (saveResp.ok) {
                    alert("Passkey registrata con successo!");
                    window.location.reload();
                } else {
                    alert("Errore durante la registrazione della passkey.");
                }
            } catch (err) {
                console.error(err);
                alert("Impossibile registrare la passkey.");
            }
        });
    </script>

</x-app-layout>

