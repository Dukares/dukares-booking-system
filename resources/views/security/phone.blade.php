@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white shadow-lg rounded-2xl p-6">

    <h1 class="text-2xl font-bold text-gray-800 mb-4">Verifica il tuo numero di telefono</h1>

    @if(session('status'))
        <div class="bg-blue-100 text-blue-900 p-3 rounded-lg mb-3">
            {{ session('status') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 text-red-900 p-3 rounded-lg mb-3">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- PHONE FORM -->
    <form action="{{ route('security.phone.send') }}" method="POST" class="mb-4">
        @csrf

        <label class="font-semibold text-gray-700">Numero di telefono</label>
        <input type="text" name="phone"
            value="{{ old('phone', $user->phone) }}"
            class="w-full mt-1 mb-4 border-gray-300 rounded-lg">

        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold">
            Invia codice SMS
        </button>
    </form>

    <!-- VERIFY FORM -->
    <form action="{{ route('security.phone.verify') }}" method="POST">
        @csrf

        <label class="font-semibold text-gray-700">Codice SMS</label>
        <input type="text" name="code"
            class="w-full mt-1 mb-4 border-gray-300 rounded-lg">

        <button class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-semibold">
            Conferma codice
        </button>
    </form>

</div>
@endsection
