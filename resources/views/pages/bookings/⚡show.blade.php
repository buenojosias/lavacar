<?php

use App\Models\Booking;
use Livewire\Component;

new class extends Component {
    public $booking;

    public function mount($booking)
    {
        $this->booking = Booking::with('customer', 'serviceVariant.serviceType', 'companyVehicle.vehicle')->findOrFail($booking);
        if (auth()->user()->can('isAdmin')) {
            $this->booking->load('company');
        }
    }
};
?>

<div class="space-y-6">
    <div class="page-header">
        <h2>Agendamento</h2>
    </div>

    <x-ts-card class="flex flex-col sm:flex-row justify-between gap-4" :color="$booking->status->color()" bordered>
        <x-slot:header></x-slot:header>
        <div class="grid grid-cols-2 font-medium gap-4 text-lg">
            <div class="flex items-center gap-2">
                <x-phosphor-calendar-blank class="w-5 h-5" />
                {{ $booking->formatted_day }}
            </div>
            <div class="flex items-center gap-2">
                <x-phosphor-clock class="w-5 h-5" />
                {{ $booking->starts_at->format('H\hi') }}
            </div>
        </div>
        <x-slot:footer>
            <div class="flex justify-between items-center gap-4">
                <x-ts-badge :text="$booking->status->label()" lg :color="$booking->status->color()" />
                @if ($booking->status->nextStatus() && !auth()->user()->can('isAdmin'))
                    <div class="flex gap-1">
                        <x-ts-button :text="$booking->status->nextStatus()->actionLabel()"
                            x-on:click="$dispatch('change-status', [{{ $booking->id }}, '{{ $booking->status->nextStatus()->value }}'])" />
                    </div>
                @endif
            </div>
        </x-slot:footer>
    </x-ts-card>

    @can('isAdmin')
        <x-ts-card header="Estabelecimento" class="detail g-2">
            <x-detail label="Nome" :value="$booking->company->name" />
            <x-detail label="Bairro/Cidade"
                value="{{ $booking->company->district }} - {{ $booking->company->city->name }}" />
            <x-slot:footer>
                <x-ts-link text="Acessar estabelecimento" :href="route('companies.show', $booking->company)" />
            </x-slot:footer>
        </x-ts-card>
    @endcan

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-ts-card class="space-y-4">
            <x-slot:header>
                <div class="p-4 detail g-2">
                    <x-detail label="Cliente" :value="$booking->customer->name" />
                    <x-detail label="Veículo" :value="$booking->companyVehicle->vehicle->brand_model" />
                </div>
            </x-slot:header>
            <div class="detail g-3">
                <x-detail label="Placa" :value="$booking->companyVehicle->vehicle->plate" />
                <x-detail label="Categoria" :value="$booking->companyVehicle->vehicle->category" />
                <x-detail label="Cor" :value="$booking->companyVehicle->vehicle->color" />
                <x-detail label="Data" :value="$booking->scheduled_date->format('d/m/Y')" />
                <x-detail label="Horário"
                    value="{{ $booking->starts_at->format('H\hi') }} - {{ $booking->ends_at->format('H\hi') }}" />
                <x-detail label="Duração" :value="$booking->formatted_duration" />
                <x-detail label="Serviço" :value="$booking->serviceVariant->serviceType->name" class="col-span-3" />
                <x-detail label="Preço" :value="$booking->formatted_price" />
                <x-detail label="Status" :value="$booking->status->label()" />
                <div class="col-span-3">
                    <x-detail label="Observações" :value="$booking->notes" />
                </div>
                <x-detail label="Agendado em" :value="$booking->created_at->format('d/m/Y H:i')" />
            </div>
            @if ($booking->status->nextStatus() && !auth()->user()->can('isAdmin'))
                <x-slot:footer>
                    <div class="flex gap-2 justify-between">
                        <x-ts-button text="Reagendar" :color="App\Enums\BookingStatusEnum::from('rescheduled')->color()"
                            x-on:click="$dispatch('change-status', [{{ $booking->id }}, '{{ App\Enums\BookingStatusEnum::from('rescheduled')->value }}'])"
                            sm />
                        <div class="flex gap-2">
                            <x-ts-button text="Cancelar" :color="App\Enums\BookingStatusEnum::from('cancelled')->color()"
                                x-on:click="$dispatch('change-status', [{{ $booking->id }}, '{{ App\Enums\BookingStatusEnum::from('cancelled')->value }}'])"
                                sm />
                            <x-ts-button text="No show" :color="App\Enums\BookingStatusEnum::from('no_show')->color()"
                                x-on:click="$dispatch('change-status', [{{ $booking->id }}, '{{ App\Enums\BookingStatusEnum::from('no_show')->value }}'])"
                                sm />
                        </div>
                    </div>
                </x-slot:footer>
            @endif
        </x-ts-card>

        @island(lazy: true, name: 'timeline')
            @placeholder
                <x-placeholder quantity="3" />
            @endplaceholder
            <livewire:bookings.timeline :booking="$booking" />
        @endisland

        @island()
            <livewire:bookings.change-status @updated="$refresh()" />
        @endisland
    </div>
</div>
