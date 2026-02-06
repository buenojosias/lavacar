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

    public Booking $booking;
    public $reason;
    public $modal = false;

    #[On('get-cancel-data')]
    public function getCancelData($id)
    {
        $this->booking = Booking::findOrFail($id);
        $this->modal = true;
    }

    public function confirmCancel()
    {
        if (!isset($this->booking)) {
            $this->modal = false;
            $this->dialog()->error('Erro ao cancelar agendamento.')->send();
            return;
        }
        // $data = $this->validate([
        //     'reason' => 'nullable|string',
        // ]);
        try {
            $this->booking->update([
                'status' => 'cancelled',
            ]);
            $this->booking->statusHistories()->create([
                'status' => 'cancelled',
                'user_id' => auth()->id(),
                'origin' => 'dashboard',
            ]);
            $this->toast()->success('Agendamento cancelado com sucesso.')->send();
            $this->dispatch('updated');
            $this->reset();
            $this->modal = false;
        } catch (\Throwable $th) {
            $this->modal = false;
            $this->toast()->error('Erro ao cancelar agendamento.')->send();
        }
    }
};
?>

<x-ts-modal wire title="Cancelar agendamento" size="sm">
    <p class="mb-4">Tem certeza que deseja cancelar este agendamento?</p>
    <x-ts-textarea
        label="Motivo do cancelamento"
        hint="Opcional"
        wire:model="reason"
        placeholder="Informar o motivo do cancelamento..."
    />
    <x-slot:footer>
        <x-ts-button wire:click="confirmCancel" text="Confirmar cancelamento" color="red" />
    </x-slot>
</x-ts-modal>