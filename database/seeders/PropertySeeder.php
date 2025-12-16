<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\Owner;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $owner = Owner::first();

        Property::create([
            'owner_id'        => $owner->id,
            'title'           => 'Appartamento Centro Tirana',
            'description'     => 'Appartamento moderno vicino al centro.',
            'city'            => 'Tirana',
            'price_per_night' => 85,
            'ics_url'         => null,
        ]);
    }
}
