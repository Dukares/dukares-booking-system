<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Owner;

class OwnerSeeder extends Seeder
{
    public function run(): void
    {
        Owner::create([
            'nome'      => 'Mario',
            'cognome'   => 'Rossi',
            'email'     => 'mario.rossi@example.com',
            'telefono'  => '+39 333 1234567',
            'note'      => 'Owner di test DukaRes',
        ]);
    }
}
