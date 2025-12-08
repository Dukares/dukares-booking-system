<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\DeviceLog;

class SecurityCenterController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Recupera tutti i dispositivi dell'utente
        $devices = DeviceLog::where('user_id', $user->id)
            ->orderBy('last_used_at', 'desc')
            ->get();

        return view('security.index', compact('devices'));
    }

    public function remove($id)
    {
        $device = DeviceLog::findOrFail($id);

        // Sicurezza: l'utente può cancellare solo i suoi dispositivi
        if ($device->user_id !== Auth::id()) {
            abort(403);
        }

        $device->delete();

        return redirect()->back()->with('success', 'Dispositivo rimosso con successo.');
    }
}
