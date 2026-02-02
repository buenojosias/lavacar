<?php

use App\Models\ServiceVariant;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public $serviceVariantId;
    public $vehicleSize;
    public $services;
    public $serviceOptions = [];

    #[On('changed-company-vehicle')]
    public function loadServices($data)
    {
        $this->reset();
        if (!$data) {
            // $this->customerId = null;
            // $this->companyVehicleId = null;
            // $this->vehicles = [];
            // $this->vehicleOptions = [];
            // $this->reset();
            $this->dispatch('set-company-vehicle-id', null);
            return;
        }

        $this->services = ServiceVariant::query()
            ->where('vehicle_size', $data['vehicleSize'])
            ->whereHas('serviceType', function ($query) {
                $query->where('company_id', auth()->user()->selected_company_id)->where('is_active', true);
            })
            ->with('serviceType')
            ->get();

        $this->serviceOptions = $this->services->map(
            fn($service) => [
                'label' => $service->serviceType->name,
                'description' => $service->formattedDuration . ' - ' . $service->formattedPrice,
                'value' => $service->id,
            ],
        );

        $this->dispatch('set-company-vehicle-id', $data['companyVehicleId'] ?? null);
    }

    public function updatedServiceVariantId($value)
    {
        dump($value);
        // if (!$value) {
        //     return;
        // }
        // $this->dispatch('updated-company-vehicle-id', $this->company_vehicle_id);
    }
};
?>

<div>
    <x-ts-select.styled label="Serviço" wire:model="serviceVariantId" :options="$serviceOptions" />
</div>
