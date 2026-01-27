<?php

use App\Enums\BookingStatusEnum;
use App\Models\Booking;
use Livewire\Component;
use Livewire\Attributes\On;
use TallStackUi\Traits\Interactions;

new class extends Component {
    use Interactions;

    public $bookingId;
    public $status;

    #[On('change-status')]
    public function changeStatus($bookingId, $status)
    {
        $this->bookingId = $bookingId;
        $this->status = $status;

        $statusLabel = BookingStatusEnum::from($status)->label();
        $this->dialog()
            ->question('Alterar status para ' . $statusLabel . '?')
            ->confirm(method: 'confirmed')
            ->cancel(method: 'cancelled')
            ->send();
    }

    public function confirmed()
    {
        $booking = Booking::find($this->bookingId);

        if (!$booking) {
            $this->dialog()->error('Agendamento não encontrado')->send();
            $this->reset();
            return;
        }

        $booking->status = $this->status;

        $booking->save();
        $this->dispatch('updated');
        $this->toast()
            ->success('Status alterado para ' . BookingStatusEnum::from($this->status)->label())
            ->send();

        $this->reset();
    }

    public function cancelled()
    {
        $this->reset();
    }
};
?>

<div>
    {{-- Order your soul. Reduce your wants. - Augustine --}}
</div>
