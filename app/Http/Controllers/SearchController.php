<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    /**
     * HOTEL SEARCH FROM JOMRES TABLES
     */
    public function search(Request $request)
    {
        $destination = $request->input('destination');
        $checkin     = $request->input('checkin');
        $checkout    = $request->input('checkout');
        $guests      = $request->input('guests');
        $type        = $request->input('type'); // hotel, apartment, villa etc.

        /*
        |--------------------------------------------------------------------------
        | 1) CERCA TRA LE PROPRIETÀ JOMRES
        |--------------------------------------------------------------------------
        |
        | Tabella principale: uasns_jomres_propertys
        | Contiene: nome hotel, indirizzo, city, region, country, idproperty
        |
        */

        $properties = DB::table('uasns_jomres_propertys')
            ->where(function($q) use ($destination) {
                $q->where('property_name', 'LIKE', "%$destination%")
                  ->orWhere('property_street', 'LIKE', "%$destination%")
                  ->orWhere('property_town', 'LIKE', "%$destination%")
                  ->orWhere('property_region', 'LIKE', "%$destination%")
                  ->orWhere('property_country', 'LIKE', "%$destination%");
            })
            ->limit(100)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | 2) RESTITUISCE ALLA PAGINA RISULTATI
        |--------------------------------------------------------------------------
        */

        return view('search.results', [
            'properties'  => $properties,
            'destination' => $destination,
            'checkin'     => $checkin,
            'checkout'    => $checkout,
            'guests'      => $guests,
            'type'        => $type,
        ]);
    }
}
