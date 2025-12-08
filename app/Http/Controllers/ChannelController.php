<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function index()
    {
        $channels = Channel::orderBy('name')->get();
        return view('channels.index', compact('channels'));
    }

    public function create()
    {
        return view('channels.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'required|string|max:100|unique:channels,slug',
            'type'        => 'required|string|in:ics,api,manual',
            'is_active'   => 'nullable',
            'description' => 'nullable|string',
        ]);

        $data['is_active'] = $request->has('is_active');

        Channel::create($data);

        return redirect()
            ->route('channels.index')
            ->with('success', 'Canale creato correttamente.');
    }

    public function edit(Channel $channel)
    {
        return view('channels.edit', compact('channel'));
    }

    public function update(Request $request, Channel $channel)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'required|string|max:100|unique:channels,slug,' . $channel->id,
            'type'        => 'required|string|in:ics,api,manual',
            'is_active'   => 'nullable',
            'description' => 'nullable|string',
        ]);

        $data['is_active'] = $request->has('is_active');

        $channel->update($data);

        return redirect()
            ->route('channels.index')
            ->with('success', 'Canale aggiornato correttamente.');
    }

    public function destroy(Channel $channel)
    {
        $channel->delete();
        return redirect()
            ->route('channels.index')
            ->with('success', 'Canale eliminato.');
    }
}
