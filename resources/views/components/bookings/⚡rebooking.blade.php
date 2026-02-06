<?php

use App\Models\Booking;
use App\Models\OpeningHours;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;
use TallStackUi\Traits\Interactions;

new class extends Component
{
    use Interactions;

    public $oldBooking;
    
    public $company_id;
    public $customer_id;
    public $company_vehicle_id;
    public $service_variant_id;
    public $scheduled_date;
    public $time;
    public $price;
    public $notes;
    public $duration;

    public $weekdays = [];

    public $modal = false;

    #[On('get-rebooking-data')]
    public function getRebookingData($id)
    {
        $this->oldBooking = Booking::findOrFail($id);
        $this->customer_id = $this->oldBooking->customer_id;
        $this->company_vehicle_id = $this->oldBooking->company_vehicle_id;
        $this->service_variant_id = $this->oldBooking->service_variant_id;
        $this->price = $this->oldBooking->price;
        $this->notes = $this->oldBooking->notes;
        $this->duration = $this->oldBooking->starts_at->diffInMinutes($this->oldBooking->ends_at);

        $openingHours = OpeningHours::query()
            ->where('company_id', auth()->user()->selected_company_id)
            ->get();

        $this->weekdays = $openingHours->map(fn($openingHour) => [
            'value' => $openingHour->weekday->value,
        ]);

        $this->modal = true;
    }

    public function saveRebooking()
    {
        $data = $this->validate([
            'scheduled_date' => 'required|string|after_or_equal:today',
            'time' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        $data['customer_id'] = $this->oldBooking->customer_id;
        $data['company_vehicle_id'] = $this->oldBooking->company_vehicle_id;
        $data['service_variant_id'] = $this->oldBooking->service_variant_id;
        $data['company_id'] = auth()->user()->selected_company_id;
        $data['starts_at'] = Carbon::parse($data['scheduled_date'] . ' ' . $data['time']);
        $data['ends_at'] = Carbon::parse($data['starts_at'])->addMinutes($this->duration);
        $data['price'] = $this->oldBooking->price;
        $data['status'] = 'confirmed';

        try {
            $booking = Booking::create($data);
            $this->oldBooking->update(['status' => 'rescheduled']);
            $booking->statusHistories()->create([ 'status' => 'confirmed', 'user_id' => auth()->id(), 'origin' => 'dashboard' ]);
            $this->oldBooking->statusHistories()->create([ 'status' => 'rescheduled', 'user_id' => auth()->id(), 'origin' => 'dashboard' ]);
            $this->toast()->success('Serviço agendado com sucesso.')->send();
            $this->dispatch('updated');
            $this->reset();
            $this->modal = false;
        } catch (\Throwable $th) {
            $this->toast()->error('Erro ao agendar serviço.')->send();
        }
    }
};
?>

<x-ts-modal wire title="Reagendar serviço" size="sm">
    <form wire:submit="saveRebooking" id="rebooking-form" class="space-y-4">
        <x-ts-date
                label="Nova data *"
                wire:model.live="scheduled_date"
                :min-date="now()"
                format="DD/MM/YYYY"
                placeholder="Selecione uma data..."
            />

            <!-- Limitar este campo aos horários disponíveis, de acordo com o dia da semana -->
            <x-ts-time
                label="Novo horário *"
                wire:model="time"
                {{-- :disabled="!$scheduled_date" --}}
                placeholder="{{ $scheduled_date ? 'Selecione um horário...' : 'Selecione uma data primeiro' }}"
                step-minute="5"
                format="24"
            />

            <x-ts-textarea
                label="Observação"
                wire:model="notes"
                placeholder="Inserir uma observação..."
            />
    </form>
    <x-slot:footer>
        <x-ts-button type="submit" form="rebooking-form" text="Confirmar reagendamento" />
    </x-slot>
</x-ts-modal>