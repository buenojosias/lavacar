<?php

use App\Models\ServiceType;
use App\Enums\VehicleSizeEnum;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\Title;

new #[Title('Serviços')] class extends Component {
    #[Computed]
    public function serviceTypes()
    {
        return ServiceType::with('variants')
            // ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();
    }

    public function with(): array
    {
        return [
            'serviceTypes' => $this->serviceTypes,
        ];
    }
};
?>

<div>
    <div class="page-header">
        <h2>Serviços</h2>
        @cannot('isAdmin')
            <x-ts-button text="Cadastrar serviço" :href="route('services.create')" />
        @endcannot
    </div>

    @if ($serviceTypes->isEmpty())
        <div class="text-center py-8 text-gray-500">
            <p>Nenhum serviço cadastrado ainda.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($serviceTypes as $serviceType)
                <x-ts-card :header="$serviceType->name" minimize="mount">
                    @if ($serviceType->description || !$serviceType->is_active)
                        <div class="mb-3 flex justify-between gap-4 items-center">
                            <p class="dark:text-gray-400 text-gray-500 text-sm flex-1">
                                {{ $serviceType->description ?? '' }}</p>
                            <div class="flex flex-col sm:flex-row gap-1">
                                @if (!$serviceType->is_active)
                                    <x-ts-badge text="Inativo" color="red" light round />
                                @endif
                            </div>
                        </div>
                    @endif

                    @if ($serviceType->variants->isNotEmpty())
                        <div class="list">
                            @foreach ($serviceType->variants as $variant)
                                <x-list-item :title="$variant->vehicle_size->label()" :description="$variant->vehicle_size->description() ?? null">
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">{{ $variant->formatted_duration }}</p>
                                        <p class="text-md font-semibold text-green-600">{{ $variant->formatted_price }}
                                        </p>
                                        @if (!$variant->is_active)
                                            <x-ts-badge text="Inativo" color="red" light round />
                                        @endif
                                    </div>
                                </x-list-item>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 italic">Nenhuma variante cadastrada.</p>
                    @endif
                    <x-slot:footer>
                        <x-ts-button text="Detalhes" :href="route('services.show', $serviceType)" flat />
                        <x-ts-button text="Editar" href="#" flat />
                    </x-slot:footer>
                </x-ts-card>
            @endforeach
        </div>
    @endif
</div>
