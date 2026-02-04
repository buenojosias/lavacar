<?php

use App\Models\ServiceVariant;
use App\Models\CompanyVehicle;  
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

new class extends Component
{
    public ?int $vehicleId = null;
    public ?string $vehicleSize = null;
    public ?int $serviceVariantId = null;

    #[Computed]
    public function services()
    {
        if (! $this->vehicleId) {
            return [];
        }

        $serviceVariants = ServiceVariant::query()
            ->where('vehicle_size', $this->vehicleSize)
            ->whereHas('serviceType', fn($query) => $query->where('is_active', true))
            ->with('serviceType')
            ->get()
            ->map(fn($service) => [
                'value' => $service->id,
                'label' => $service->serviceType->name,
            ]);

        return $serviceVariants;
    }

    #[On('vehicle-selected')]
    public function vehicleSelected($id, $size)
    {
        $this->vehicleId = $id;
        $this->vehicleSize = $size;
        $this->dispatch('set-vehicle-id', $this->vehicleId);
    }
};
?>

<div>
    <x-ts-select.native label="Serviço" :options="$this->services"
        x-on:select="$dispatch('service-selected', { id: $event.detail.select.value })"
        x-on:remove="$dispatch('service-selected', { id: null })" />
</div>