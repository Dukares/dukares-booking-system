<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\ChannelConnection;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HostChannelController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ⚠️ DA MODIFICARE QUANDO AGGIUNGI OWNER → USER
        $properties = Property::with('channelConnections.channel')->get();

        $channels = Channel::where('is_active', true)->orderBy('name')->get();

        return view('host.channels.index', compact('properties', 'channels'));
    }

    public function storeOrUpdate(Request $request)
    {
        $data = $request->validate([
            'property_id'         => 'required|exists:properties,id',
            'channel_id'          => 'required|exists:channels,id',
            'external_listing_id' => 'nullable|string|max:255',
            'ics_url'             => 'nullable|url',
            'status'              => 'required|string|in:connected,disconnected,paused',
        ]);

        ChannelConnection::updateOrCreate(
            [
                'property_id' => $data['property_id'],
                'channel_id'  => $data['channel_id'],
            ],
            [
                'external_listing_id' => $data['external_listing_id'],
                'ics_url'             => $data['ics_url'],
                'status'              => $data['status'],
                'last_sync_at'        => now(),
            ]
        );

        return back()->with('success', 'Canale aggiornato correttamente.');
    }
}
