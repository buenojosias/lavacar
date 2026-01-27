<?php

use App\Models\Booking;
use App\Models\CompanyVehicle;
use App\Models\OpeningHours;
use App\Models\ServiceType;
use App\Models\ServiceVariant;
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

    public $vehicles;
    public $vehicleOptions = [];

    public $services;
    public $serviceOptions = [];

    public $weekdays = [];

    public function mount()
    {
        $openingHours = OpeningHours::query()
            ->where('company_id', auth()->user()->selected_company_id)
            ->get();

        $this->weekdays = $openingHours->map(fn($openingHour) => [
            'value' => $openingHour->weekday->value,
        ]);
    }

    public function updatedCustomerId($value)
    {
        if (!$value) {
            return;
        }
        // $this->reset(['company_vehicle_id', 'vehicles', 'vehicleOptions']);
        $this->vehicles = CompanyVehicle::with('vehicle')->where('customer_id', $value)->get();
        $this->vehicleOptions = $this->vehicles->map(fn($vehicle) => [
            'label' => $vehicle->nickname,
            'description' => $vehicle->vehicle->category . ' (' . $vehicle->vehicle->size->label() . ') - ' . $vehicle->vehicle->color,
            'value' => $vehicle->id,
        ]);
    }

    public function updatedCompanyVehicleId($value)
    {
        if (!$value) {
            return;
        }
        // $this->reset(['service_variant_id', 'services', 'serviceOptions']);
        $vehicle_size = $this->vehicles->where('id', $value)->first()->vehicle->size;
        $this->services = ServiceVariant::query()
            ->where('vehicle_size', $vehicle_size)
            ->whereHas('serviceType', function ($query) {
                $query->where('company_id', auth()->user()->selected_company_id)->where('is_active', true);
            })
            ->with('serviceType')
            ->get();

        $this->serviceOptions = $this->services->map(fn($service) => [
            'label' => $service->serviceType->name,
            'description' => $service->formattedDuration . ' - ' . $service->formattedPrice,
            'value' => $service->id,
        ]);
    }

    public function updatedServiceVariantId($value)
    {
        if (!$value) {
            return;
        }
        $this->service_variant_id = $value;
        $this->price = ($this->services->where('id', $value)->first()->price) / 100;
    }

    public function save()
    {
        $data = $this->validate([
            'customer_id' => 'required',
            'company_vehicle_id' => 'required',
            'service_variant_id' => 'required',
            'price' => 'required',
            'scheduled_date' => 'required',
            'time' => 'required',
        ]);

        $data['company_id'] = auth()->user()->selected_company_id;
        $data['starts_at'] = \Carbon\Carbon::parse($data['scheduled_date'] . ' ' . $data['time']);
        $data['ends_at'] = $data['starts_at']->addMinutes($this->services->where('id', $data['service_variant_id'])->first()->duration);
        $data['status'] = 'confirmed';

        try {
            $booking = Booking::create($data);
            $this->toast()->success('Serviço agendado com sucesso')->send();
            $this->reset();
        } catch (\Throwable $th) {
            $this->toast()->error('Erro ao agendar serviço')->send();
            dd($th);
        }
    }
};
