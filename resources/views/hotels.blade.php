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
    @error('address')
        <div class="alert alert-danger d-flex justify-content-between align-items-center">
            <p>{{ $message }}</p>
            <button type="button" class="btn-close h-auto" data-bs-dismiss="alert" aria-label="Close">X</button>
        </div>
    @enderror
    <div class="py-12">
        <button type="button" class="btn btn-primary ms-5" data-bs-toggle="modal" data-bs-target="#addHotelModal">
            Add Hotel
        </button>
        <div class="p-6 text-gray-900 w-full d-flex justify-content-center">
            <table class="table-auto w-75 table">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">Nom</th>
                        <th scope="col" class="text-center">Adresse</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @if (count($hotels) == 0)
                        <tr>
                            <td class="text-center" colspan="4">No hotels found</td>
                        </tr>
                    @else
                    @foreach ($hotels as $hotel)
                        <tr>
                            <td class="text-center">
                                <a href="{{ route('hotels.show', $hotel->id) }}" class="btn btn-outline-primary">{{ $hotel->name }}</a>
                            </td>
                            <td class="text-center">{{ $hotel->address }}</td>
                            <td class="text-center flex-row d-flex gap-3 justify-content-center">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#staticBackdrop" data-id="{{ $hotel->id }}"
                                    data-name="{{ $hotel->name }}" data-address="{{ $hotel->address }}">
                                    Edit
                                </button>
                                <form method="POST" action="{{ route('hotels.destroy') }}">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="id" value="{{ $hotel->id }}">
                                    <button type="submit" class="btn btn-primary">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="addHotelModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="addHotelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addHotelModalLabel">Add Hotel</h1>
                </div>
                <div class="modal-body">
                    <form id="addForm" method="POST" action="{{ route('hotels.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nom</label>
                            <input type="text" name="name" id="name" value="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="address">Addresse</label>
                            <input type="text" name="address" id="address" value="" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Save</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Nom</label>
                            <input type="text" name="name" id="name" value=""
                                class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="address">Addresse</label>
                            <input type="text" name="address" id="address" value=""
                                class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Save</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var updateUrlTemplate = "{{ route('hotels.update', 'placeholder') }}";
            let exampleModal = document.getElementById('staticBackdrop');
            exampleModal.addEventListener('show.bs.modal', function(event) {

                let button = event.relatedTarget;
                let id = button.getAttribute('data-id');
                let name = button.getAttribute('data-name');
                let address = button.getAttribute('data-address');

                let modalForm = exampleModal.querySelector('#editForm');
                modalForm.action = updateUrlTemplate.replace('placeholder', id);

                exampleModal.querySelector('#name').value = name;
                exampleModal.querySelector('#address').value = address;

            });
        });
    </script>
</x-app-layout>
