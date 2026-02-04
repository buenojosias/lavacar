<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="tallstackui_darkTheme()">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <tallstackui:script />
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body x-bind:class="{ 'dark bg-slate-800': darkTheme, 'bg-slate-50': !darkTheme }">
    <x-ts-toast />
    <x-ts-dialog />
    <x-ts-layout>
        <x-slot:header>
            <x-ts-layout.header>
                <x-slot:left>
                    <x-ts-theme-switch only-icons />
                </x-slot:left>
                <x-slot:right>
                    <x-ts-dropdown text="Hello, {{ auth()->user()->name }}!">
                        <!-- <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-ts-dropdown.items text="Logout" onclick="event.preventDefault(); this.closest('form').submit();" />
                        </form> -->
                    </x-ts-dropdown>
                </x-slot:right>
            </x-ts-layout.header>
        </x-slot:header>

        <x-slot:menu>
            <x-ts-side-bar>
                <x-ts-side-bar.item text="Home" icon="phosphor.house" :current="request()->routeIs('dashboard')" :route="route('dashboard')" />
                @can('isAdmin')
                    <x-ts-side-bar.item text="Estabelecimentos" icon="phosphor.building-light" :current="request()->routeIs('companies.*')"
                        :route="route('companies.index')" />
                @endcan
                <x-ts-side-bar.item text="Agendamentos" icon="phosphor.calendar-dots-light" :current="request()->routeIs('bookings.*')"
                    :route="route('bookings.index')" />
                <x-ts-side-bar.item text="Clientes" icon="phosphor.users-three-light" :current="request()->routeIs('customers.*')"
                    :route="route('customers.index')" />
                <x-ts-side-bar.item text="Serviços" icon="phosphor.wrench-light" :current="request()->routeIs('services.*')"
                    :route="route('services.index')" />
                </x-ts-side-bar>
        </x-slot:menu>
        {{ $slot }}
    </x-ts-layout>

    @livewireScripts
</body>

</html>
