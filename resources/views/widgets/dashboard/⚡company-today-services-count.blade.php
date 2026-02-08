<?php

use App\Models\Booking;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    #[Computed]
    public function services()
    {
        return Booking::where('scheduled_date', today())
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->count();
    }
};
?>

<x-ts-stats :number="$this->services" title="Serviços para hoje" :href="route('bookings.index')" wire:navigate />
