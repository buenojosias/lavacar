<?php

use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public ?int $customer_id = null;
    public ?int $company_vehicle_id = null;
    public ?int $service_variant_id = null;
    // public ?string $scheduled_date = null;
    // public ?string $starts_at = null;
    // public ?string $ends_at = null;
    // public ?float $price = null;
    // public ?string $status = null;
    // public ?string $notes = null;

    #[On('set-customer-id')]
    public function setCustomerId($id)
    {
        $this->customer_id = $id;
    }

    #[On('set-vehicle-id')]
    public function setVehicleId($id)
    {
        $this->company_vehicle_id = $id;
    }
};