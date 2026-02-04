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
            ->where('is_active', true)
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

    @if($serviceTypes->isEmpty())
        <div class="text-center py-8 text-gray-500">
            <p>Nenhum serviço cadastrado ainda.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($serviceTypes as $serviceType)
                <x-ts-card :header="$serviceType->name" minimize="mount">
                    @if($serviceType->description)
                        <p class="dark:text-gray-400 text-gray-500 text-sm mb-3">{{ $serviceType->description }}</p>
                    @endif

                    @if($serviceType->variants->isNotEmpty())
                        <div class="list">
                            @foreach($serviceType->variants as $variant)
                                <x-list-item 
                                    :title="$variant->vehicle_size->label()" :description="$variant->vehicle_size->description() ?? null"
                                >
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">{{ $variant->formatted_duration }}</p>
                                        <p class="text-md font-semibold text-green-600">{{ $variant->formatted_price }}</p>
                                    </div>
                                    @if(!$variant->is_active)
                                        <x-ts-badge text="Inativo" color="red" light />
                                    @endif
                                </x-list-item>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 italic">Nenhuma variante cadastrada.</p>
                    @endif
                </x-ts-card>
            @endforeach
        </div>
    @endif
</div>