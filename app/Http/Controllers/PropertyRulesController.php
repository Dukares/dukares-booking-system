<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyRule;
use Illuminate\Http\Request;

class PropertyRulesController extends Controller
{
    public function edit($propertyId)
    {
        $property = Property::findOrFail($propertyId);
        $rules = $property->rules ?? new PropertyRule();

        return view('properties.rules', compact('property', 'rules'));
    }

    public function update(Request $request, $propertyId)
    {
        $property = Property::findOrFail($propertyId);

        $data = $request->validate([
            'checkin_from'   => 'nullable|string',
            'checkin_to'     => 'nullable|string',
            'checkout_from'  => 'nullable|string',
            'checkout_to'    => 'nullable|string',
            'children_allowed' => 'required|boolean',
            'pets_policy'      => 'required|string',
            'smoking_allowed'  => 'required|boolean',
            'min_age'          => 'nullable|integer',
            'additional_rules' => 'nullable|string',
        ]);

        PropertyRule::updateOrCreate(
            ['property_id' => $propertyId],
            $data
        );

        return back()->with('success', 'Regole salvate con successo!');
    }
}
