<?php

use App\Models\ServiceType;
use App\Models\ServiceVariant;
use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component {
    public ServiceType $service;

    #[Computed]
    public function variants()
    {
        return ServiceVariant::query()->where('service_type_id', $this->service->id)->withCount('bookings')->get();
    }
};
?>

<div>
    <div class="page-header">
        <h2>{{ $service->name }}</h2>
        <div>
            <x-ts-button text="Editar" />
        </div>
    </div>

    <x-ts-card header="Variações" class="list">
        @forelse ($this->variants ?? [] as $variant)
            <x-list-item :title="$variant->vehicle_size->label()"
                subtitle="{{ $variant->formatted_duration . ' • ' . $variant->formatted_price }}"
                :description="$variant->bookings_count . ' serviços agendados/executados'">
                <div class="flex flex-col items-end gap-2">
                    @if (!$variant->is_active)
                        <x-ts-badge text="Inativo" color="red" light round />
                    @endif
                    <div>
                        <x-ts-button icon="phosphor.chart-line" color="dark" flat />
                        <x-ts-button icon="phosphor.note-pencil" color="dark" flat x-on:click="$dispatch('open-edit-modal', { id: {{ $variant->id }} })" />
                    </div>
                </div>
            </x-list-item>
        @empty
            <div class="py-2">Nenhuma variação adicionada</div>
        @endforelse
        <x-slot:footer>
            <x-ts-button text="Adicionar variação" x-on:click="$modalOpen('create-variant-modal')" />
        </x-slot:footer>
    </x-ts-card>

    <livewire:services.create-variant-modal :$service @saved="$refresh" />
    <livewire:services.edit-variant-modal :$service @saved="$refresh" />
</div>
