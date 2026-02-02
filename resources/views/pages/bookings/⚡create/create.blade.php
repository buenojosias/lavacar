<div>
    <div class="page-header">
        <h2>Agendar serviço</h2>
    </div>

    @dump($customer_id, $company_vehicle_id)

    <x-ts-card>
        <form wire:submit="save" id="booking-form" class="space-y-4 grid grid-cols-1 md:grid-cols-2 gap-4">
            @island(name: 'customer-select')
                <livewire:bookings.create.customer-select />
            @endisland
            @island(name: 'vehicle-select', lazy: true)
                @placeholder
                    ...
                @endplaceholder
                <livewire:bookings.create.vehicle-select />
            @endisland
            @island(name: 'service-select', lazy: true)
                @placeholder
                    ...
                @endplaceholder
                <livewire:bookings.create.service-select />
            @endisland
            {{-- @island(name: 'service-select')
                <livewire:bookings.create.service-select />
            @endisland --}}
            {{--
            <x-ts-select.styled label="Serviço" wire:model.live="service_variant_id" wire:key="service_variant_id"
                :options="$serviceOptions" />
            <x-ts-currency label="Custo" wire:model="price" readonly locale="pt-BR" symbol />
            <x-ts-date label="Data" wire:model="scheduled_date" :min-date="now()" format="DD/MM/YYYY" />
            <x-ts-time label="Horário" wire:model="time" :step-minute="5" format="24" /> --}}
        </form>
        <x-slot name="footer">
            <x-ts-button type="submit" form="booking-form">Agendar</x-ts-button>
        </x-slot>
    </x-ts-card>
</div>

{{-- <script>
    document.addEventListener('livewire:init', () => {
       Livewire.on('vehicles-loaded', (event) => {
           alert('carregou')
       });
    });
</script> --}}
