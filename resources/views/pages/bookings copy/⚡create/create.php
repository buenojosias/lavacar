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

    public $vehicleOptions = [];
    public $serviceOptions = [];
    public $timeOptions = [];

    public $minHour = null;
    public $maxHour = null;
    public $minMinute = null;
    public $maxMinute = null;

    public $vehicleSelectKey = 0; // Unique key to force re-render
    public $serviceSelectKey = 0; // Unique key to force re-render
    public $timeSelectKey = 0;

    public $weekdays = [];

    public function mount()
    {
        $openingHours = OpeningHours::query()
            ->where('company_id', auth()->user()->selected_company_id)
            ->get();

        $this->weekdays = $openingHours->map(fn($openingHour) => [
            'value' => $openingHour->weekday->value,
        ]);

        // Load initial options if values are already set (e.g., from validation errors)
        if ($this->customer_id) {
            $this->loadVehicleOptions();
        }
        if ($this->company_vehicle_id) {
            $this->loadServiceOptions();
        }
        if ($this->service_variant_id) {
            $this->updatedServiceVariantId();
        }
        if ($this->scheduled_date) {
            $this->updatedScheduledDate();
        }
    }

    public function updatedCustomerId()
    {
        // Reset dependent fields first
        $this->company_vehicle_id = null;
        $this->service_variant_id = null;
        $this->price = null;
        $this->serviceOptions = [];

        if ($this->customer_id) {
            $this->loadVehicleOptions();
        } else {
            $this->vehicleOptions = [];
        }

        // Force re-render of dependent selects
        $this->vehicleSelectKey++;
        $this->serviceSelectKey++;
    }

    private function loadVehicleOptions()
    {
        if ($this->customer_id) {
            $vehicles = CompanyVehicle::query()
                ->where('customer_id', $this->customer_id)
                ->where('company_id', auth()->user()->selected_company_id)
                ->with('vehicle')
                ->get()
                ->map(fn($companyVehicle) => [
                    'label' => $companyVehicle->vehicle->brand_model . ' - ' . $companyVehicle->vehicle->plate,
                    'description' => $companyVehicle->vehicle->size->label() . ($companyVehicle->nickname ? ' (' . $companyVehicle->nickname . ')' : ''),
                    'value' => $companyVehicle->id,
                ]);

            // Add empty option at the beginning
            $this->vehicleOptions = collect([
                [
                    'label' => 'Selecione um veículo...',
                    'description' => '',
                    'value' => null,
                ]
            ])->merge($vehicles)->toArray();
        }
    }

    public function updatedCompanyVehicleId()
    {
        // Reset dependent fields first
        $this->service_variant_id = null;
        $this->price = null;

        if ($this->company_vehicle_id) {
            $this->loadServiceOptions();
        } else {
            $this->serviceOptions = [];
        }

        // Force re-render of dependent select
        $this->serviceSelectKey++;
    }

    private function loadServiceOptions()
    {
        if ($this->company_vehicle_id) {
            $companyVehicle = CompanyVehicle::with('vehicle')->find($this->company_vehicle_id);
            if ($companyVehicle) {
                $services = ServiceVariant::query()
                    ->whereHas('serviceType', function($query) {
                        $query->where('company_id', auth()->user()->selected_company_id);
                    })
                    ->where('vehicle_size', $companyVehicle->vehicle->size->value)
                    ->where('is_active', true)
                    ->with('serviceType')
                    ->get()
                    ->map(fn($serviceVariant) => [
                        'label' => $serviceVariant->serviceType->name,
                        'description' => $serviceVariant->formattedDuration . ' - ' . $serviceVariant->formattedPrice,
                        'value' => $serviceVariant->id,
                        'price' => $serviceVariant->price,
                    ]);

                // Add empty option at the beginning
                $this->serviceOptions = collect([
                    [
                        'label' => 'Selecione um serviço...',
                        'description' => '',
                        'value' => null,
                        'price' => null,
                    ]
                ])->merge($services)->toArray();
            }
        }
    }

    public function updatedServiceVariantId()
    {
        $this->price = null;

        if ($this->service_variant_id && !empty($this->serviceOptions)) {
            $selectedOption = collect($this->serviceOptions)->firstWhere('value', $this->service_variant_id);
            if ($selectedOption && isset($selectedOption['price'])) {
                $this->price = $selectedOption['price'] / 100; // Convert from cents to reais for display
            }
        }
    }

    public function updatedScheduledDate()
    {
        $this->time = null;
        $this->minHour = null;
        $this->maxHour = null;
        $this->minMinute = null;
        $this->maxMinute = null;

        if ($this->scheduled_date) {
            try {
                // The TallStack date component may return different formats
                // Try multiple parsing approaches
                $date = null;

                // Try d/m/Y format first (Brazilian format)
                $date = \Carbon\Carbon::createFromFormat('d/m/Y', $this->scheduled_date);

                // If that fails, try Y-m-d format (ISO format)
                if (!$date) {
                    $date = \Carbon\Carbon::createFromFormat('Y-m-d', $this->scheduled_date);
                }

                // If that fails, try general parsing
                if (!$date) {
                    $date = \Carbon\Carbon::parse($this->scheduled_date);
                }

                if ($date) {
                    $weekday = $date->dayOfWeek;

                    $openingHours = OpeningHours::query()
                        ->where('company_id', auth()->user()->selected_company_id)
                        ->where('weekday', $weekday)
                        ->orderBy('opens_at')
                        ->get();

                    if ($openingHours->isNotEmpty()) {
                        // Find the earliest opening time and latest closing time across all periods
                        $earliestOpen = $openingHours->min('opens_at');
                        $latestClose = $openingHours->max('closes_at');

                        $earliestOpenTime = \Carbon\Carbon::parse($earliestOpen);
                        $latestCloseTime = \Carbon\Carbon::parse($latestClose);

                        $this->minHour = $earliestOpenTime->hour;
                        $this->maxHour = $latestCloseTime->hour;
                        $this->minMinute = $earliestOpenTime->minute;
                        $this->maxMinute = $latestCloseTime->minute;
                    }
                }
            } catch (\Exception $e) {
                // Invalid date format or parsing error
                $this->minHour = null;
                $this->maxHour = null;
                $this->minMinute = null;
                $this->maxMinute = null;
            }
            $this->timeSelectKey++;
        }
    }

    public function save()
    {
        $this->validate([
            'customer_id' => 'required|exists:customers,id',
            'company_vehicle_id' => 'required|exists:company_vehicles,id',
            'service_variant_id' => 'required|exists:service_variants,id',
            'price' => 'required|numeric|min:0',
            'scheduled_date' => 'required|string|after_or_equal:today',
            'time' => 'required|string',
        ]);

        try {
            $serviceVariant = ServiceVariant::findOrFail($this->service_variant_id);

            // Parse date and time more robustly
            $dateTimeString = $this->scheduled_date . ' ' . $this->time;
            $startsAt = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $dateTimeString);
            if (!$startsAt) {
                $startsAt = \Carbon\Carbon::parse($dateTimeString);
            }
            $endsAt = $startsAt->copy()->addMinutes($serviceVariant->duration);

            // Check for conflicts (simplified check)
            $conflictExists = Booking::query()
                ->where('company_id', auth()->user()->selected_company_id)
                ->where('scheduled_date', $this->scheduled_date)
                ->where(function($query) use ($startsAt, $endsAt) {
                    $query->where('starts_at', '<', $endsAt)
                          ->where('ends_at', '>', $startsAt);
                })
                ->exists();

            if ($conflictExists) {
                $this->toast()->error('Horário indisponível. Já existe um agendamento neste período.')->send();
                return;
            }

            Booking::create([
                'company_id' => auth()->user()->selected_company_id,
                'customer_id' => $this->customer_id,
                'company_vehicle_id' => $this->company_vehicle_id,
                'service_variant_id' => $this->service_variant_id,
                'scheduled_date' => $this->scheduled_date,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'price' => $this->price * 100, // Convert from reais to cents for storage
                'status' => 'confirmed',
            ]);

            $this->toast()->success('Serviço agendado com sucesso!')->send();

            // Reset form after successful submission
            $this->resetForm();

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->toast()->error('Dados inválidos. Verifique os campos e tente novamente.')->send();
        } catch (\Throwable $th) {
            $this->toast()->error('Erro ao agendar serviço. Tente novamente.')->send();
        }
    }

    private function resetForm()
    {
        $this->customer_id = null;
        $this->company_vehicle_id = null;
        $this->service_variant_id = null;
        $this->price = null;
        $this->scheduled_date = null;
        $this->time = null;

        $this->vehicleOptions = [];
        $this->serviceOptions = [];
        $this->timeOptions = [];

        $this->minHour = null;
        $this->maxHour = null;
        $this->minMinute = null;
        $this->maxMinute = null;

        // Reset select keys to force re-render
        $this->vehicleSelectKey++;
        $this->serviceSelectKey++;
        $this->timeSelectKey++;
    }
};
