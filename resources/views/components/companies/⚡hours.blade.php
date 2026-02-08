<?php

use App\Models\OpeningHours;
use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component {
    public $company;
    public $weekday;
    public $hours;

    public function deleteHour(int $hour)
    {
        OpeningHours::findOrFail($hour)->delete();
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
                        <x-ts-button icon="phosphor.trash" wire:click="deleteHour({{$hour->id}})" sm color="light" flat />
                    </div>
                </li>
            @empty
                <li class="text-red-500">Fechado</li>
            @endforelse
        </ul>
    </x-slot:subtitle>
    <div>
        <x-ts-button icon="phosphor.plus" x-on:click="$modalOpen('add-hour-modal', {weekday: {{ $weekday->value }} })" sm color="light" flat />
    </div>
</x-list-item>
