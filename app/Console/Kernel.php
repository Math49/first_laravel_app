<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Chambres;
use App\Models\Reservation;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            // Récupérer toutes les chambres
            $chambres = Chambres::all();
            foreach ($chambres as $chambre) {
                // Vérifier si la chambre a une réservation dont la date de fin est dépassée
                
                
                foreach ($chambres as $chambre) {
                    // Vérifier si la chambre a une réservation dont la date de début et la date de fin sont comprises
                    $reservation = Reservation::where('chambre_id', $chambre->id)
                        ->where('date_start', '<=', now())
                        ->where('date_end', '>=', now())
                        ->first();

                    if ($reservation) {
                        // Si oui, mettre à jour la disponibilité de la chambre à 0
                        $chambre->disponible = 0;
                    } else {
                        // Si non, mettre à jour la disponibilité de la chambre à 1
                        $chambre->disponible = 1;
                    }

                    $chambre->save();
                }
            }
        })->everySecond();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
