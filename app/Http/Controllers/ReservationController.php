<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Hotel;
use App\Models\Chambres;

class ReservationController extends Controller
{

    public function index()
    {
        $events = [];

        $reservations = Reservation::all();

        $hotels = Hotel::all();
        $chambres = Chambres::all();

        foreach ($reservations as $reservation) {
            $events[] = [
                'title' => $reservation->name,
                'start' => $reservation->date_start,
                'end' => $reservation->date_end,
                'allDay' => true,
                'chambre_id' => $reservation->chambre_id,
                'reservation_id' => $reservation->id,
            ];
        }
        return view('reservations', compact('events', 'hotels', 'chambres', 'reservations'));
    }

    public function store(Request $request, $id)
    {

        $fausseDate = Reservation::where('date_end', '<', $request->get('date_start'))
            ->first();

        if ($fausseDate) {
            return redirect()->route('index')->with('error', 'Merci de mettre une date de fin ultérieure à la date de début');
        }
        $existingReservation = Reservation::where('chambre_id', $id)
            ->where('date_end', '>=', $request->get('date_start'))
            ->first();

        if ($existingReservation) {
            return redirect()->route('index')->with('error', 'La chambre est déjà réservée');
        }

        $reservation = new Reservation();
        $reservation->name = $request->get('name');
        $reservation->date_start = $request->get('date_start');
        $reservation->date_end = $request->get('date_end');
        $reservation->chambre_id = $id;
        $reservation->save();

        return redirect()->route('index');
    }

    public function destroy(Request $request)
    {
        $reservation = Reservation::find($request->get('id'));
        $reservation->delete();
        return redirect()->route('reservations');
    }
}
