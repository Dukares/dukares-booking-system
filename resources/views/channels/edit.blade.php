@extends('layouts.dukares-layout')

@section('content')

<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Modifica Canale</h1>

    <form method="POST" action="{{ route('channels.update', $channel) }}"
          class="bg-white shadow rounded-lg p-6">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-semibold mb-1">Nome *</label>
            <input type="text" name="name" required
                   value="{{ old('name', $channel->name) }}"
                   class="w-full border-gray-300 rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Slug *</label>
            <input type="text" name="slug" required
                   value="{{ old('slug', $channel->slug) }}"
                   class="w-full border-gray-300 rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Tipo *</label>
            <select name="type" class="w-full border-gray-300 rounded p-2">
                <option value="ics" {{ $channel->type === 'ics' ? 'selected' : '' }}>ICS</option>
                <option value="api" {{ $channel->type === 'api' ? 'selected' : '' }}>API</option>
                <option value="manual" {{ $channel->type === 'manual' ? 'selected' : '' }}>Manuale</option>
            </select>
        </div>

        <div class="mb-4">
            <label>
                <input type="checkbox" name="is_active"
                       {{ $channel->is_active ? 'checked' : '' }}>
                <span class="ml-1 font-semibold">Canale attivo</span>
            </label>
        </div>

        <div class="mb-6">
            <label class="block font-semibold mb-1">Descrizione</label>
            <textarea name="description" rows="3"
                      class="w-full border-gray-300 rounded p-2">{{ $channel->description }}</textarea>
        </div>

        <div class="flex justify-end space-x-2">
            <a href="{{ route('channels.index') }}"
               class="px-4 py-2 rounded border">Annulla</a>

            <button type="submit"
                    class="px-4 py-2 rounded bg-blue-600 text-white">
                Salva modifiche
            </button>
        </div>

    </form>
</div>

@endsection
