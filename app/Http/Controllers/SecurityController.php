<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SecurityDevice;
use Illuminate\Support\Facades\Log;

class SecurityController extends Controller
{
    public function index()
    {
        $devices = SecurityDevice::where('user_id', Auth::id())
            ->orderBy('last_login_at', 'desc')
            ->get();

        return view('security.index', compact('devices'));
    }

    public function destroy($id)
    {
        $device = SecurityDevice::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        Log::channel('security')->warning('Dispositivo rimosso', [
            'user_id' => Auth::id(),
            'device_id' => $device->id,
            'ip' => $device->ip_address,
        ]);

        $device->delete();

        return redirect()->route('security')->with('success', 'Dispositivo rimosso con successo.');
    }
}

