<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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

<body>
    <x-ts-layout>
        <x-slot:header>
            <x-ts-layout.header>
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
                <x-ts-side-bar.item text="Home" icon="home" :route="route('dashboard')" />
            </x-ts-side-bar>
        </x-slot:menu>
        {{ $slot }}
    </x-ts-layout>

    @livewireScripts
</body>

</html>