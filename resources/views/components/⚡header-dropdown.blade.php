<?php

use Livewire\Component;

new class extends Component {
    public function logout()
    {
        auth()->logout();
        return redirect()->route('login');
    }
};
?>

<x-ts-dropdown>
    <x-slot:action>
        <x-ts-avatar :model="auth()->user()" x-on:click="show = !show" sm />
    </x-slot:action>
    <x-ts-dropdown.items icon="user" text="Perfil" />
    <x-ts-dropdown.items icon="phosphor.building-light" text="Estabelecimento" />
    <x-ts-dropdown.items class="flex items-center justify-between">
        <x-ts-label>Modo escuro</x-ts-label>
        <x-ts-theme-switch />
    </x-ts-dropdown.items>
    <x-ts-dropdown.items wire:click="logout" icon="arrow-left-on-rectangle" text="Sair" separator />
</x-ts-dropdown>
