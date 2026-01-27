<div>
    <div class="page-header">
        <h2>Agendar serviço</h2>
    </div>

    <x-ts-card>
        <form wire:submit="save" id="booking-form" class="space-y-4 grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-ts-select.styled label="Cliente" wire:model.live="customer_id" wire:key="customer_id" :request="route('api.customers', ['company_id' => auth()->user()->selected_company_id])" />
            <x-ts-select.styled label="Veículo" wire:model.live="company_vehicle_id" wire:key="company_vehicle_id"
                :options="$vehicleOptions" />
            <x-ts-select.styled label="Serviço" wire:model.live="service_variant_id" wire:key="service_variant_id"
                :options="$serviceOptions" />
            <x-ts-currency label="Custo" wire:model="price" readonly locale="pt-BR" symbol />
            <x-ts-date label="Data" wire:model="scheduled_date" :min-date="now()" format="DD/MM/YYYY" />
            <x-ts-time label="Horário" wire:model="time" :step-minute="5" format="24" />
        </form>
        <x-slot name="footer">
            <x-ts-button type="submit" form="booking-form">Agendar</x-ts-button>
        </x-slot>
    </x-ts-card>
</div>
