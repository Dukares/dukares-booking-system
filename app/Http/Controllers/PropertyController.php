<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Owner;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::with('owner')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('properties.index', compact('properties'));
    }

    public function create()
    {
        $owners = Owner::orderBy('nome')->get();
        return view('properties.create', compact('owners'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'owner_id'        => 'required|exists:owners,id',
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'city'            => 'nullable|string|max:255',
            'price_per_night' => 'nullable|numeric|min:0',
            'active'          => 'nullable|boolean',
            'ics_url'         => 'nullable|url',
        ]);

        // default attivo
        $data['active'] = $data['active'] ?? true;

        Property::create($data);

        return redirect()
            ->route('properties.index')
            ->with('success', 'Struttura creata con successo');
    }

    public function edit(Property $property)
    {
        $owners = Owner::orderBy('nome')->get();
        return view('properties.edit', compact('property', 'owners'));
    }

    public function update(Request $request, Property $property)
    {
        $data = $request->validate([
            'owner_id'        => 'required|exists:owners,id',
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'city'            => 'nullable|string|max:255',
            'price_per_night' => 'nullable|numeric|min:0',
            'active'          => 'nullable|boolean',
            'ics_url'         => 'nullable|url',
        ]);

        $data['active'] = $data['active'] ?? true;

        $property->update($data);

        return redirect()
            ->route('properties.index')
            ->with('success', 'Struttura aggiornata con successo');
    }

    public function destroy(Property $property)
    {
        $property->delete();

        return redirect()
            ->route('properties.index')
            ->with('success', 'Struttura eliminata');
    }
}
