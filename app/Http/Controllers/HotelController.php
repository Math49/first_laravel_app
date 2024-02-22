<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\Chambres;

class HotelController extends Controller
{

    public function welcome()
    {
        $hotels = Hotel::all();
        $chambres = Chambres::all();
        return view('welcome', ['hotels' => $hotels, 'chambres' => $chambres]);
    }

    public function index()
    {
        $hotels = Hotel::all();
        return view('hotels', ['hotels' => $hotels]);
    }

    public function show($id)
    {
        $hotel = Hotel::find($id);
        $chambres = Chambres::where('hotel_id', $id)->get();
        return view('viewhotel', ['hotel' => $hotel, 'chambres' => $chambres]);
    }

    public function store(Request $request)
    {
        $hotel = new Hotel();
        $hotel->name = $request->get('name');
        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => [
                    'User-Agent: PHP'
                ]
            ]
        ];

        $context = stream_context_create($opts);

        $address = $request->get('address');
        $geocode = file_get_contents('https://nominatim.openstreetmap.org/search?format=json&q=' . urlencode($address), false, $context);

        $response = json_decode($geocode);

        if (empty($response)) {
            return back()->withErrors(['address' => 'L\'adresse n\'est pas valide.']);
        }
        $hotel->address = $request->get('address');


        $hotel->save();

        return redirect()->route('hotels');
    }

    public function update(Request $request, $id)
    {
        $hotel = Hotel::find($id);
        $hotel->name = $request->get('name');
        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => [
                    'User-Agent: PHP'
                ]
            ]
        ];

        $context = stream_context_create($opts);

        $address = $request->get('address');
        $geocode = file_get_contents('https://nominatim.openstreetmap.org/search?format=json&q=' . urlencode($address), false, $context);

        $response = json_decode($geocode);

        if (empty($response)) {
            return back()->withErrors(['address' => 'L\'adresse n\'est pas valide.']);
        }
        $hotel->address = $request->get('address');


        $hotel->save();

        return redirect()->route('hotels');
    }

    public function destroy(Request $request)
    {
        $hotel = Hotel::find($request->get('id'));
        $hotel->delete();
        return redirect()->route('hotels');
    }

    public function addRoom(Request $request, $id)
    {
        $chambre = new Chambres();
        $chambre->numero = $request->get('numero');
        $chambre->nmb_lits = $request->get('nmb_lits');
        $chambre->hotel_id = $id;
        $chambre->disponible = 1;
        $chambre->save();

        return redirect()->route('hotels.show', ['id' => $id]);
    }

    public function destroyRoom(Request $request, $id)
    {
        $chambre = Chambres::find($request->get('id'));
        $chambre->delete();
        return redirect()->route('hotels.show', ['id' => $id]);
    }
}
