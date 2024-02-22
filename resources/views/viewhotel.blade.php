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
    <div class="py-12 ">
        <div
            class="p-6 text-gray-900 mx-auto w-75 bg-white overflow-hidden shadow-sm sm:rounded-lg d-flex justify-content-center">
            <table class="w-75">
                <thead>
                    <tr>
                        <th scope="col" class="text-center font-bold">Nom</th>
                        <th scope="col" class="text-center font-bold">Addresse</th>
                        <th scope="col" class="text-center font-bold">Nombre de chambres</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <tr>
                        <td class="text-center">{{ $hotel->name }}</td>
                        <td class="text-center">{{ $hotel->address }}</td>
                        <td class="text-center">{{$chambres->where('hotel_id', $hotel->id)->count() }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="w-full d-flex justify-content-center mt-5">

            <div id="mapid" style="height: 300px; width: 600px;"></div>
        </div>

        <div class="mt-5 d-flex justify-content-center flex-column gap-3">
            <h2 class="ms-5 mb-2 fw-bold fs-2">Liste des Chambres :</h2>
            <div class="ms-5 mb-4">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                    Ajouter une chambre
                </button>
            </div>
            @if (count($chambres) == 0)
                <div
                    class="mx-auto w-75 bg-white overflow-hidden shadow-sm sm:rounded-lg d-flex justify-content-center p-3">
                    <p class="text-center">Aucune chambre trouvée</p>
                </div>
            @else
                @foreach ($chambres as $chambre)
                    <div
                        class="mx-auto w-75 bg-white overflow-hidden shadow-sm sm:rounded-lg d-flex justify-content-center p-3">
                        <table class="w-100 ">
                            <tbody class="d-flex justify-content-around align-items-center">
                                <tr class="d-flex gap-3">
                                    <td class="fw-bold">Numéro :</td>
                                    <td>{{ $chambre->numero }}</td>
                                </tr>
                                <tr class="d-flex gap-3">
                                    <td class="fw-bold">Nombre de lits :</td>
                                    <td>{{ $chambre->nmb_lits }}</td>
                                </tr>
                                <tr class="d-flex gap-3">
                                    <td class="fw-bold">Disponible ? :</td>
                                    <td>
                                        @if ($chambre->disponible == 1)
                                            Oui
                                        @else
                                            Non
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <form method="POST" action="{{ route('chambres.destroy', $hotel->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="id" value="{{ $chambre->id }}">
                                            <button type="submit" class="btn btn-primary">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                @endforeach
            @endif
        </div>

    </div>

    <!-- Add Room Modal -->
    <div class="modal fade" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRoomModalLabel">Ajouter une chambre</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('addRoom', $hotel->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="numero" class="form-label">Numéro de chambre</label>
                            <input type="text" class="form-control" id="numero" name="numero" required>
                        </div>
                        <div class="mb-3">
                            <label for="nmb_lits" class="form-label">Nombre de lits</label>
                            <input type="number" class="form-control" id="nmb_lits" name="nmb_lits" min="0" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        window.onload = function() {
            var osmUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
            var osmAttrib = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors';
            var osm = new L.TileLayer(osmUrl, {minZoom: 8, maxZoom: 20, attribution: osmAttrib});
        
            var map = new L.Map('mapid', {layers: [osm], center: new L.LatLng(51.505, -0.09), zoom: 13 });
        
            var address = "{{ $hotel->address }}";  
        
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${address}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        var lat = data[0].lat;
                        var lon = data[0].lon;
                        map.setView(new L.LatLng(lat, lon), 13);
                        var marker = L.marker([lat, lon]).addTo(map);
                    }
                });
        }
        </script>
</x-app-layout>
