<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Property;
use App\Models\Reservation;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totUsers'       => User::count(),
            'totOwners'      => User::where('role', 'owner')->count(),
            'totProperties'  => Property::count(),
            'totReservations'=> Reservation::count(),
        ]);
    }
}
