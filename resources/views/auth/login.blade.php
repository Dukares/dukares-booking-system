@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">
            Accedi a DukaRes
        </h1>

        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-700 px-4 py-2 rounded">
                <ul class="list-disc ml-4 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label>Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="w-full border-gray-300 rounded-lg p-2"
                >
            </div>

            <div class="mb-4">
                <label>Password</label>
                <input
                    type="password"
                    name="password"
                    required
                    class="w-full border-gray-300 rounded-lg p-2"
                >
            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg py-2 mt-2"
            >
                Accedi
            </button>
        </form>
    </div>
</div>
@endsection
