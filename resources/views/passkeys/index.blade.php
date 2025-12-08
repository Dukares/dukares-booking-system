<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🔐 Le tue Passkey (Login biometrico)
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                <h3 class="text-lg font-medium text-gray-800 mb-4">
                    Dispositivi registrati
                </h3>

                @if ($passkeys->isEmpty())
                    <p class="text-gray-500">
                        Nessuna Passkey attiva.
                        <br>Registra subito un dispositivo (Impronta, FaceID, Windows Hello).
                    </p>
                @else
                    <ul class="divide-y">
                        @foreach ($passkeys as $key)
                            <li class="flex items-center justify-between py-3">
                                <div>
                                    <p class="font-semibold text-gray-800">
                                        {{ $key->name ?? 'Dispositivo' }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Registrato il: {{ $key->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>

                                <form method="POST" action="{{ route('passkeys.destroy', $key->id) }}"
                                      onsubmit="return confirm('Rimuovere questa passkey?')">
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                        Elimina
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <hr class="my-6">

                <div class="text-center">
                    <button id="register-passkey"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        ➕ Registra una nuova Passkey
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- SCRIPT REGISTRAZIONE PASSKEY --}}
    <script>
        document.getElementById('register-passkey').addEventListener('click', async () => {
            try {
                // 1) Richiedi opzioni dal server
                const response = await fetch("{{ route('passkeys.register.options') }}", {
                    method: "POST",
                    headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                });

                const options = await response.json();

                // 2) Richiesta biometrica
                const credential = await navigator.credentials.create({
                    publicKey: options,
                });

                // 3) Conversione in JSON
                const data = {
                    id: credential.id,
                    rawId: btoa(String.fromCharCode(...new Uint8Array(credential.rawId))),
                    type: credential.type,
                    response: {
                        attestationObject: btoa(String.fromCharCode(...new Uint8Array(credential.response.attestationObject))),
                        clientDataJSON: btoa(String.fromCharCode(...new Uint8Array(credential.response.clientDataJSON))),
                    },
                };

                // 4) Invio al backend
                const verifyResponse = await fetch("{{ route('passkeys.register.store') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                    body: JSON.stringify(data),
                });

                if (verifyResponse.ok) {
                    alert("Nuova Passkey registrata con successo!");
                    window.location.reload();
                } else {
                    alert("Errore: impossibile registrare la passkey.");
                }

            } catch (err) {
                console.error(err);
                alert("Registrazione biometrica annullata o non riuscita.");
            }
        });
    </script>
</x-app-layout>
