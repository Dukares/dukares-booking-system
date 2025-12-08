<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * SHOW GLOBAL DUKARES HOMEPAGE
     */
    public function index()
    {
        // Popular destinations (temporary demo data)
        $popularDestinations = [
            [
                'city' => 'New York',
                'country' => 'United States',
                'image' => 'https://images.pexels.com/photos/1737957/pexels-photo-1737957.jpeg',
                'from_price' => 89,
            ],
            [
                'city' => 'Tirana',
                'country' => 'Albania',
                'image' => 'https://images.pexels.com/photos/210617/pexels-photo-210617.jpeg',
                'from_price' => 29,
            ],
            [
                'city' => 'Rome',
                'country' => 'Italy',
                'image' => 'https://images.pexels.com/photos/532263/pexels-photo-532263.jpeg',
                'from_price' => 49,
            ],
            [
                'city' => 'Dubai',
                'country' => 'UAE',
                'image' => 'https://images.pexels.com/photos/325193/pexels-photo-325193.jpeg',
                'from_price' => 99,
            ],
        ];

        return view('landing.home', [
            'popularDestinations' => $popularDestinations,
        ]);
    }
}
