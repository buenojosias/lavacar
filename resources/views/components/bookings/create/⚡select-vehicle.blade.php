<?php

use App\Models\CompanyVehicle;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

new class extends Component
{
    public ?int $customerId = null;
    public ?int $vehicleId = null;

    #[Computed]
    public function vehicles()
    {
        if (! $this->customerId) {
            return [];
        }

        $vehicles = CompanyVehicle::query()
            ->where('customer_id', $this->customerId)
            ->with('vehicle')
            ->get()
            ->map(fn($vehicle) => [
                'value' => $vehicle->id,
                'label' => $vehicle->nickname,
                'description' => $vehicle->vehicle->category . ' (' . $vehicle->vehicle->size->label() . ') ' . $vehicle->vehicle->color,
                'size' => $vehicle->vehicle->size->value,
            ]);

        return $vehicles;
    }

    #[On('customer-selected')]
    public function customerSelected($id)
    {
        $this->customerId = $id;
        $this->vehicleId = null;
        $this->dispatch('set-customer-id', $this->customerId);
    }
};
?>

<div>
    <x-ts-select.styled label="Veículo" :options="$this->vehicles"
        x-on:select="$dispatch('vehicle-selected', { id: $event.detail.select.value, size: $event.detail.select.size })"
        x-on:remove="$dispatch('vehicle-selected', { id: null, size: null })" />
</div>