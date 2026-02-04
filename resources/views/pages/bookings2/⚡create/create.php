<?php

use App\Models\Booking;
use App\Models\CompanyVehicle;
use App\Models\OpeningHours;
use App\Models\ServiceType;
use App\Models\ServiceVariant;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

new class extends Component
{
    use Interactions;

    public $customer_id;
    public $company_vehicle_id;
    public $service_variant_id;
    public $price;
    public $scheduled_date;
    public $time;

    #[Computed]
    public function vehicles()
    {
        if (!$this->customer_id) {
            return [];
        }

        $vehicles = CompanyVehicle::withoutGlobalScopes()
            ->where('customer_id', $this->customer_id)
            ->get()
            ->map(fn($vehicle) => [
                'label' => $vehicle->nickname ?? $vehicle->plate ?? 'Veículo sem nome',
                'value' => $vehicle->id,
            ])
            ->toArray();

        // add null value before $vehicles
        array_unshift($vehicles, [
            'label' => 'Adicionar novo veículo',
            'value' => null,
        ]);

        return $vehicles;
    }
    
};
