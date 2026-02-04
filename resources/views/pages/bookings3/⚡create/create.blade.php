
<div class="space-y-6">
    {{-- @dump($customer_id, $company_vehicle_id, $service_variant_id) --}}
    @island(name: 'customer')
        <livewire:bookings.create.select-customer />
    @endisland
    @island(name: 'vehicle')
        <livewire:bookings.create.select-vehicle />
    @endisland
    @island(name: 'service')
        <livewire:bookings.create.select-service />
    @endisland
</div>