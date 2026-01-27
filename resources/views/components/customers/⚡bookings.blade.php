<?php

use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public $customer;

    public function mount($customer)
    {
        $this->customer = $customer;
    }

    #[Computed]
    public function bookings()
    {
        return $this->customer
            ->bookings()
            ->with('serviceVariant.serviceType', 'companyVehicle')
            ->where('scheduled_date', '>=', now()->format('Y-m-d'))
            ->orderBy('starts_at', 'asc')
            ->get();
    }
};
?>

<x-ts-card header="Agendamentos do cliente">
    <div class="list">
        @forelse($this->bookings as $booking)
            <x-list-item :title="$booking->serviceVariant->serviceType->name" :subtitle="$booking->starts_at->format('d/m/Y H:i')" :description="$booking->companyVehicle->nickname ?? null" :href="route('bookings.show', $booking)">
                <x-ts-badge :text="$booking->status->label()" :color="$booking->status->color()" />
            </x-list-item>
        @empty
            Nenhum agendamento encontrado
        @endforelse
    </div>
    <x-slot:footer>
        <x-ts-button text="Agendar" color="primary" />
        <x-ts-button text="Ver todos" color="secondary" />
    </x-slot:footer>
</x-ts-card>
