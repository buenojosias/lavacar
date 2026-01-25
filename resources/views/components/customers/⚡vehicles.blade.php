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
        <ul class="space-y-6">
            @forelse ($this->c_vehicles as $c_vehicle)
                <li class="flex gap-2 justify-between items-center">
                    <div class="detail">
                        @if ($c_vehicle->vehicle->category)
                            <x-detail :label="$c_vehicle->nickname ?? $c_vehicle->vehicle->plate" :value="$c_vehicle->vehicle->brand_model"
                                note="{{ $c_vehicle->vehicle->size->label() }} ({{ $c_vehicle->vehicle->category }})"
                                url="#" />
                        @else
                            <x-detail :label="$c_vehicle->nickname ?? $c_vehicle->vehicle->plate" :value="$c_vehicle->vehicle->brand_model" />
                        @endif
                    </div>
                    <x-ts-badge :text="$c_vehicle->vehicle->plate" color="amber" light />
                </li>
            @empty
                <li>Nenhum veículo cadastrado.</li>
            @endforelse
        </ul>
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
