<?php

use App\Models\CompanyVehicle;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public $customerId;
    public $companyVehicleId;
    public $vehicles;
    public $vehicleOptions = [];

    #[On('changed-customer')]
    public function loadVehicles($customerId)
    {
        $this->reset();
        if (!$customerId) {
            // $this->customerId = null;
            // $this->companyVehicleId = null;
            // $this->vehicles = [];
            // $this->vehicleOptions = [];
            // $this->reset();
            $this->dispatch('set-customer-id', $customerId);
            return;
        }
        $this->vehicles = CompanyVehicle::with('vehicle')->where('customer_id', $customerId)->get();
        $this->vehicleOptions = $this->vehicles->map(
            fn($vehicle) => [
                'label' => $vehicle->nickname,
                'description' => $vehicle->vehicle->category . ' (' . $vehicle->vehicle->size->label() . ') - ' . $vehicle->vehicle->color,
                'value' => $vehicle->id,
            ],
        );
        $this->dispatch('set-customer-id', $customerId);
    }

    #[On('changed-vehicle')]
    public function getVehicleSize($companyVehicleId)
    {
        $vehicleSize = $this->vehicles->where('id', $companyVehicleId)->first()->vehicle->size ?? null;
        $data = [
            'companyVehicleId' => $companyVehicleId,
            'vehicleSize' => $vehicleSize,
        ];
        $this->dispatch('changed-company-vehicle', [
            'companyVehicleId' => $companyVehicleId,
            'vehicleSize' => $vehicleSize,
        ]);
    }

    public function companyVehicleId($value)
    {
        if ($value) {
            return;
        }
        $this->dispatch('changed-company-vehicle', null);
    }

    // public function updatedCompanyVehicleId($value)
    // {
    //     if ($value) {
    //         $vehicleSize = $this->vehicles->where('id', $value)->first()->vehicle->size ?? null;
    //     } else {
    //         $vehicleSize = null;
    //         $this->company_vehicle_id = null;
    //     }
    //     $data = [
    //         'companyVehicleId' => $this->company_vehicle_id,
    //         'vehicleSize' => $vehicleSize
    //     ];
    //     $this->dispatch('updated-vehicle', $data);
    // }
};
?>

<div>
    <x-ts-select.styled label="Veículo" wire:model.live="companyVehicleId"
        x-on:select="$dispatch('changed-vehicle', {
        companyVehicleId: event.detail.select.value,
    })"
        :options="$vehicleOptions" />
</div>

{{-- <script>
    this.$on('changed-vehicle', (data) => {
        console.log(data);
    })
</script> --}}
