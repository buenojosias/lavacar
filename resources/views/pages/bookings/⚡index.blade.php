<?php

use App\Models\Booking;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

new class extends Component {
    public $companyId;

    #[Url(as: 'data')]
    public $selectedDate;

    #[Url(as: 'status')]
    public $status;

    public $statuses = [];

    public function mount($company = null)
    {
        $this->selectedDate = now()->format('Y-m-d');
        $this->statuses = collect(\App\Enums\BookingStatusEnum::cases())
            ->map(function ($status) {
                return [
                    'value' => $status->value,
                    'label' => $status->label(),
                ];
            })
            ->toArray();
        $this->statuses = array_merge([['value' => '', 'label' => 'Todos']], $this->statuses);
        if ($company) {
            $this->companyId = $company;
        }
    }

    #[Computed]
    public function bookings()
    {
        return $bookings = Booking::query()
            ->when($this->companyId, function ($query) {
                $query->where('company_id', $this->companyId);
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->where('scheduled_date', $this->selectedDate)
            ->with('companyVehicle', 'serviceVariant.serviceType')
            ->orderBy('starts_at')
            ->get();
    }

    public function updatedSelectedDate($value)
    {
        if ($value == null) {
            $this->selectedDate = now()->format('Y-m-d');
        }
    }
};
?>

<div>
    <div class="page-header">
        <h2>Agendamentos</h2>
        <x-ts-button :href="route('bookings.create')" text="Novo agendamento" />
    </div>

    <div class="w-full md:w-1/2 flex items-center gap-2 text-sm font-semibold text-secondary-800 dark:text-dark-200">
        <div class="w-1/2">
            <x-ts-date wire:model.live="selectedDate" format="DD/MM/YYYY" helpers />
        </div>
        <div class="w-1/2">
            <x-ts-select.native wire:model.live="status" :options="$statuses" />
        </div>
    </div>

    <div class="space-y-3 mt-6">
        @forelse ($this->bookings ?? [] as $key => $booking)
            <div
                class="p-3 flex flex-col lg:flex-row justify-between gap-2 bg-white dark:bg-dark-700 flex w-full rounded-md shadow-md text-secondary-700 dark:text-dark-300 border-l-4 border-{{ $booking->status->color() }}-500">
                <div class="flex-1 flex gap-3 items-start">
                    <div class="w-14 text-right pr-3 border-r border-gray-300 dark:border-dark-600 font-semibold">
                        {{ $booking->starts_at->format('H:i') }}</div>
                    <a class="flex-1 space-y-0.5" href="{{ route('bookings.show', $booking) }}">
                        <p class="font-semibold">{{ $booking->serviceVariant->serviceType->name }}</p>
                        <p class="text-sm">{{ $booking->companyVehicle->nickname }}</p>
                    </a>
                    <div class="h-full flex items-center">
                        <x-ts-badge :text="$booking->status->label()" :color="$booking->status->color()" round xs />
                    </div>
                </div>
                <div class="pl-14 lg:pl-0 flex items-center gap-2">
                    <div class="ml-3 lg:ml-0 flex gap-2 items-center">
                        @if ($booking->status->nextStatus() && !auth()->user()->can('isAdmin'))
                            <x-ts-button :text="$booking->status->nextStatus()->actionLabel()"
                                x-on:click="$dispatch('change-status', [{{ $booking->id }}, '{{ $booking->status->nextStatus()->value }}'])"
                                sm />

                            <x-ts-dropdown icon="ellipsis-vertical" static>
                                <x-ts-dropdown.items text="Reagendar"
                                    x-on:click="$dispatch('get-rebooking-data', [{{ $booking->id }}])" />
                                <x-ts-dropdown.items text="Cancelar" separator
                                    x-on:click="$dispatch('change-status', [{{ $booking->id }}, '{{ App\Enums\BookingStatusEnum::from('cancelled')->value }}'])" />
                                <x-ts-dropdown.items text="No show"
                                    x-on:click="$dispatch('change-status', [{{ $booking->id }}, '{{ App\Enums\BookingStatusEnum::from('no_show')->value }}'])" />
                            </x-ts-dropdown>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <x-empty icon="x-circle" description="Nenhum agendamento encontrado par a data selecionada" />
        @endforelse
    </div>
    @island()
        <livewire:bookings.change-status @updated="$refresh()" />
    @endisland()
    @island()
        <livewire:bookings.rebooking @updated="$refresh()" />
    @endisland
</div>
