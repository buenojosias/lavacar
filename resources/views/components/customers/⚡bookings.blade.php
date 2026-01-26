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
            ->where('scheduled_date', '>=', now()->format('Y-m-d'))
            ->orderBy('starts_at', 'asc')
            ->get();
    }
};
?>

<x-ts-card header="Agendamentos do cliente">
    @forelse($this->bookings as $booking)
        @dump($booking->toArray())
    @empty
        Nenhum agendamento encontrado
    @endforelse
    <x-slot:footer>
        <x-ts-button text="Agendar" color="primary" />
        <x-ts-button text="Ver todos" color="secondary" />
    </x-slot:footer>
</x-ts-card>
