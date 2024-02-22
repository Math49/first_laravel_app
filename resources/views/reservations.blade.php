<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex py-1">
            <x-nav-link :href="route('hotels')" :active="request()->routeIs('hotels')">
                {{ __('Hotels') }}
            </x-nav-link>
            <x-nav-link :href="route('reservations')" :active="request()->routeIs('reservations')">
                {{ __('Réservations') }}
            </x-nav-link>
        </div>
    </x-slot>

    <style>
        .modal {
            --bs-modal-width: 600px;
        }

        a.fc-event {
            cursor: pointer;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="">
                <div class="p-6 text-gray-900 d-flex align-items-center flex-column gap-6">
                    @foreach ($hotels as $hotel)
                        <div
                            class="mx-auto w-100 bg-white overflow-hidden shadow-sm sm:rounded-lg d-flex align-items-center flex-column p-3 gap-3">
                            <table class="w-75 mb-3">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center font-bold">Nom</th>
                                        <th scope="col" class="text-center font-bold">Adresse</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    <tr>
                                        <td class="text-center">{{ $hotel->name }}</td>
                                        <td class="text-center">{{ $hotel->address }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            @if ($chambres->where('hotel_id', $hotel->id)->count() > 0)
                                @foreach ($chambres->where('hotel_id', $hotel->id) as $chambre)
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#calendarModal"
                                        class="buttonCalendar mx-auto w-75 bg-white overflow-hidden shadow-sm sm:rounded-lg d-flex justify-content-center p-3"
                                        data-chambre="{{ $chambre }}">
                                        <table class="w-100 ">
                                            <tbody class="d-flex justify-content-around">
                                                <tr
                                                    class="d-flex gap
                                                -3">
                                                    <td class="fw-bold">Numéro :</td>
                                                    <td>{{ $chambre->numero }}</td>
                                                </tr>
                                                <tr
                                                    class="d-flex gap
                                                -3">
                                                    <td class="fw-bold">Nombre de lits :</td>
                                                    <td>{{ $chambre->nmb_lits }}</td>
                                                </tr>
                                                <tr
                                                    class="d-flex gap
                                                -3">
                                                    <td class="fw-bold">Disponible ? :</td>
                                                    <td>
                                                        @if ($chambre->disponible)
                                                            Oui
                                                        @else
                                                            Non
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Nombre de réservations : </td>
                                                    <td>{{ $reservations->where('chambre_id', $chambre->id)->count() }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </button>
                                @endforeach
                            @else
                                <div
                                    class="mx-auto w-75 bg-white overflow-hidden shadow-sm sm:rounded-lg d-flex justify-content-center p-3">
                                    <p class="text-center">Aucune chambre trouvée</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="calendarModal" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="calendarModalLabel"></h5>
                </div>
                <div class="modal-body">
                    <div id="calendar"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ... Autres codes ... -->

    <div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="reservationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reservationModalLabel">Détails de la réservation</h5>
                </div>
                <div class="modal-body">
                    <p class="fw-bold">Nom: </p>
                    <p id="reservation-name"></p>
                    <p class="fw-bold mt-3">Date de réservation: </p>
                    <p id="reservation-date"></p>
                    <form method="POST" action="{{ route('reservations.destroy') }}">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="id" value="" id="resadelete">
                        <button type="submit" class="btn btn-primary mt-3">Delete</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let calendarModal = document.getElementById('calendarModal');
                calendarModal.addEventListener('shown.bs.modal', function(event) {
                    let button = event.relatedTarget;
                    let chambre = JSON.parse(button.getAttribute('data-chambre'));
                    let modalBody = calendarModal.querySelector('.modal-body');

                    calendarModal.querySelector('#calendarModalLabel').textContent = "Chambre N°" + chambre
                        .numero;

                    var calendarEl = document.getElementById('calendar');
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        events: @json($events).filter(event => event.chambre_id ===
                            chambre.id),
                        eventClick: function(info) {
                            let reservation = info.event;
                            let modal = new bootstrap.Modal(document.getElementById(
                                'reservationModal'));
                            // Mettre à jour les détails de la réservation dans la fenêtre modale
                            document.getElementById('reservation-name').textContent =
                                reservation.title;
                            const options = {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric'
                            };

                            const startDate = reservation.start.toLocaleDateString('fr-FR',
                                options);
                            const endDate = reservation.end.toLocaleDateString('fr-FR', options);

                            document.getElementById('reservation-date').textContent =
                                `${startDate} au ${endDate}`;

                            document.getElementById('resadelete').value = reservation.extendedProps.reservation_id;

                            modal.show();
                        }
                    });
                    calendar.render();
                });
            });
        </script>
    @endpush

</x-app-layout>
