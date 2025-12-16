<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // ✅ Valori temporanei (placeholder)
        // così NON rompe la view
        $totProperties   = 0;
        $totOwners       = 0;
        $totReservations = 0;

        return view('dashboard', compact(
            'totProperties',
            'totOwners',
            'totReservations'
        ));
    }
}
