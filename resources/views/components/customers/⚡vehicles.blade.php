<?php

use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
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
        <ul class="space-y-4">
            @forelse ($this->c_vehicles as $c_vehicle)
                <li class="flex gap-2 justify-between items-center">
                    <div class="detail">
                        <x-detail
                            :label="$c_vehicle->nickname ?? $c_vehicle->vehicle->plate"
                            :value="$c_vehicle->vehicle->brand_model"
                            note="{{ $c_vehicle->vehicle->size->label() }} ({{ $c_vehicle->vehicle->size->description() }})"
                            url="#" />
                    </div>
                    <x-ts-badge :text="$c_vehicle->vehicle->plate" color="amber" light />
                </li>
            @empty
                <li>Nenhum veículo cadastrado.</li>
            @endforelse
        </ul>
    </x-ts-card>
</div>