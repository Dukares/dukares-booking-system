<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Property $property)
    {
        abort_if($property->owner_id !== auth()->id(), 403);

        $units = $property->units()->orderBy('created_at', 'desc')->get();

        return view('units.index', compact('property', 'units'));
    }

    public function create(Property $property)
    {
        abort_if($property->owner_id !== auth()->id(), 403);

        return view('units.create', compact('property'));
    }

    public function store(Request $request, Property $property)
    {
        abort_if($property->owner_id !== auth()->id(), 403);

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:20',
            'price'    => 'required|integer|min:0',
        ]);

        $data['property_id'] = $property->id;
        $data['active'] = true;

        Unit::create($data);

        return redirect()
            ->route('units.index', $property)
            ->with('success', 'Unità creata');
    }

    public function edit(Property $property, Unit $unit)
    {
        abort_if($property->owner_id !== auth()->id(), 403);

        return view('units.edit', compact('property', 'unit'));
    }

    public function update(Request $request, Property $property, Unit $unit)
    {
        abort_if($property->owner_id !== auth()->id(), 403);

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:20',
            'price'    => 'required|integer|min:0',
            'active'   => 'boolean',
        ]);

        $unit->update($data);

        return redirect()
            ->route('units.index', $property)
            ->with('success', 'Unità aggiornata');
    }

    public function destroy(Property $property, Unit $unit)
    {
        abort_if($property->owner_id !== auth()->id(), 403);

        $unit->delete();

        return redirect()
            ->route('units.index', $property)
            ->with('success', 'Unità eliminata');
    }
}
