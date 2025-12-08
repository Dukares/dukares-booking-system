<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Owner;

class OwnerController extends Controller
{
    public function index()
    {
        $owners = Owner::all();
        return view('owners.index', compact('owners'));
    }

    public function create()
    {
        return view('owners.create');
    }

    public function store(Request $request)
    {
        Owner::create($request->all());
        return redirect()->route('owners.index')->with('success', 'Proprietario creato.');
    }

    public function edit($id)
    {
        $owner = Owner::findOrFail($id);
        return view('owners.edit', compact('owner'));
    }

    public function update(Request $request, $id)
    {
        $owner = Owner::findOrFail($id);
        $owner->update($request->all());
        return redirect()->route('owners.index')->with('success', 'Aggiornato.');
    }

    public function destroy($id)
    {
        Owner::destroy($id);
        return redirect()->route('owners.index')->with('success', 'Eliminato.');
    }
}
