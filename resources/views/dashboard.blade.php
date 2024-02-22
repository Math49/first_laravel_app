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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
