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
                    <livewire:header-dropdown />
                </x-slot:right>
            </x-ts-layout.header>
        </x-slot:header>

        <x-slot:menu>
            <x-ts-side-bar>
                <x-ts-side-bar.item text="Home" icon="phosphor.house" :current="request()->routeIs('dashboard')" :route="route('dashboard')" wire:navigate/>
                @can('isAdmin')
                    <x-ts-side-bar.item text="Estabelecimentos" icon="phosphor.building-light" :current="request()->routeIs('companies.*')"
                        :route="route('companies.index')" wire:navigate/>
                @endcan
                <x-ts-side-bar.item text="Agendamentos" icon="phosphor.calendar-dots-light" :current="request()->routeIs('bookings.*')"
                    :route="route('bookings.index')" wire:navigate />
                <x-ts-side-bar.item text="Clientes" icon="phosphor.users-three-light" :current="request()->routeIs('customers.*')"
                    :route="route('customers.index')" wire:navigate />
                <x-ts-side-bar.item text="Serviços" icon="phosphor.shower-light" :current="request()->routeIs('services.*')"
                    :route="route('services.index')" wire:navigate />
                </x-ts-side-bar>
        </x-slot:menu>
        {{ $slot }}
    </x-ts-layout>

    @livewireScripts
</body>

</html>
