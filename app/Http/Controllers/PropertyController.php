<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::latest()->get();
        return view('properties.index', compact('properties'));
    }

    public function create()
    {
        return view('properties.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
        ]);

        Property::create([
            'name'   => $request->name,
            'city'   => $request->city,
            'active' => true,
        ]);

        return redirect()->route('properties.index')
            ->with('success', 'Struttura creata con successo');
    }
}
