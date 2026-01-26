<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public $customer;

    #[Computed]
    public function c_vehicles()
    {
        return $this->customer->companyVehicles()->with('vehicle')->get();
    }
};
?>

<div>
    <x-ts-card header="Veículos do cliente">
        <div class="list">
            @forelse ($this->c_vehicles as $c_vehicle)
                <x-list-item :title="$c_vehicle->nickname ?? $c_vehicle->vehicle->plate" :subtitle="$c_vehicle->vehicle->brand_model"
                    description="{{ $c_vehicle->vehicle->size->label() }} ({{ $c_vehicle->vehicle->category }})"
                    href="#">
                    <x-ts-badge :text="$c_vehicle->vehicle->plate" color="amber" light />
                </x-list-item>
            @empty
                <x-list-item>Nenhum veículo cadastrado.</x-list-item>
            @endforelse
        </div>
        @if (auth()->user()->role === 'PARTNER')
            <x-slot:footer>
                <x-ts-button x-on:click="$modalOpen('create-vehicle-modal')" text="Adicionar veículo" />
            </x-slot:footer>
        @endif
    </x-ts-card>
    @if (auth()->user()->role === 'PARTNER')
        <livewire:customers.create-vehicle :customer="$customer" @created="$refresh" />
    @endif
</div>
