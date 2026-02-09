<?php

use App\Models\OpeningHours;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Carbon\Carbon;
use TallStackUi\Traits\Interactions;

new class extends Component {
    use Interactions;

    public $company;
    public $weekday;
    public $hours;

    public $opens_at;
    public $closes_at;

    public function deleteHour(int $hour)
    {
        OpeningHours::findOrFail($hour)->delete();
    }

    public function addHour()
    {
        $data = $this->validate([
            'opens_at' => 'required|date_format:H:i',
            'closes_at' => 'required|date_format:H:i',
        ]);

        try {
            $hour = OpeningHours::create([
                'company_id' => $this->company->id,
                'weekday' => $this->weekday,
                'opens_at' => $data['opens_at'],
                'closes_at' => $data['closes_at'],
            ]);
            $this->hours->push($hour);
            $this->reset(['opens_at', 'closes_at']);
            $this->toast()->success('Horário adicionado com sucesso.')->send();
            // $this->dispatch('close-modal');
        } catch (\Throwable $th) {
            $this->dialog()->error('Já existe um horário que se sobrepõe ao que está sendo adicionado.')->send();
            return;
        }
    }
};
?>

<x-list-item :title="$weekday->label()">
    <x-slot:subtitle>
        <ul class="mt-1 text-sm">
            @forelse ($this->hours as $hour)
                <li class="flex justify-between items-center border-b py-0.5 border-gray-400">
                    <span>{{ $hour->opens_at->format('H:i') }} -
                        {{ $hour->closes_at->format('H:i') }}</span>
                    <div>
                        <x-ts-button icon="phosphor.pencil" sm color="light" flat />
                        <x-ts-button icon="phosphor.trash" wire:click="deleteHour({{ $hour->id }})" sm color="light"
                            flat />
                    </div>
                </li>
            @empty
                <li class="text-red-500">Fechado</li>
            @endforelse
        </ul>
    </x-slot:subtitle>
    <div>
        <x-ts-button icon="phosphor.plus" x-on:click="$modalOpen('add-hour-modal-{{ $weekday->value }}')" sm
            color="light" flat />
    </div>
    <x-ts-modal title="Adicionar horário a {{ $weekday->label() }}" id="add-hour-modal-{{ $weekday->value }}" size="sm">
        <x-ts-errors class="mb-4" />
        <form wire:submit="addHour" id="add-hour-form" class="space-y-4">
            <x-ts-input label="Abre às" wire:model="opens_at" type="time" />
            <x-ts-input label="Fecha às" wire:model="closes_at" type="time" />
        </form>
        <x-slot:footer>
            <x-ts-button type="submit" form="add-hour-form" text="Adicionar" />
        </x-slot:footer>
    </x-ts-modal>
</x-list-item>

{{-- <script>
    this.$on('close-modal', () => {
        console.log('close-modal');
        $modalClose('add-hour-modal-{{ $weekday->value }}');
    });
</script> --}}