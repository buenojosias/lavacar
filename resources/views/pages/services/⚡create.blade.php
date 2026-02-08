<?php

use App\Models\ServiceType;
use App\Enums\VehicleSizeEnum;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

new #[Title('Cadastrar serviço')] class extends Component {
    // Service Type fields
    public string $name = '';
    public string $description = '';
    public bool $is_active = true;

    // Variants array: indexed by vehicle size value
    public array $variants = [];

    public function mount()
    {
        // Initialize variants for all vehicle sizes
        foreach (VehicleSizeEnum::cases() as $size) {
            $this->variants[$size->value] = [
                'duration' => '',
                'price' => '',
                'is_active' => true,
            ];
        }
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ];

        // Add validation for variants
        foreach (VehicleSizeEnum::cases() as $size) {
            $key = $size->value;
            $rules["variants.{$key}.duration"] = 'nullable|integer|min:1';
            $rules["variants.{$key}.price"] = 'nullable|integer|min:1';
            $rules["variants.{$key}.is_active"] = 'boolean';
        }

        return $rules;
    }

    #[Computed]
    public function can_save()
    {
        // Can save if name is filled and at least one variant has duration and price
        if (empty($this->name)) {
            return false;
        }

        foreach ($this->variants as $variant) {
            if (!empty($variant['duration']) && !empty($variant['price'])) {
                return true;
            }
        }

        return false;
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            // Create ServiceType
            $serviceType = ServiceType::create([
                'company_id' => auth()->user()->selected_company_id,
                'name' => $this->name,
                'description' => $this->description ?: null,
                'is_active' => $this->is_active,
            ]);

            // Create ServiceVariants for filled variants
            foreach ($this->variants as $sizeValue => $variantData) {
                // Only create variant if both duration and price are filled
                if (!empty($variantData['duration']) && !empty($variantData['price'])) {
                    $serviceType->variants()->create([
                        'vehicle_size' => $sizeValue,
                        'duration' => $variantData['duration'],
                        'price' => $variantData['price'],
                        'is_active' => $variantData['is_active'] ?? true,
                    ]);
                }
            }
        });

        session()->flash('success', 'Serviço cadastrado com sucesso!');
        return $this->redirect(route('services.index'), navigate: true);
    }
};
?>

<div>
    <div class="page-header">
        <h2>Cadastrar serviço</h2>
    </div>

    @cannot('isAdmin')
        <form wire:submit.prevent="save" class="space-y-6">
            <x-ts-card header="Informações do serviço">
                <div class="space-y-4">
                    <x-ts-input 
                        label="Nome do serviço" 
                        wire:model.live="name" 
                        placeholder="Ex: Lavagem completa"
                        required 
                    />

                    <x-ts-textarea 
                        label="Descrição" 
                        wire:model="description" 
                        placeholder="Descreva o serviço oferecido"
                        rows="3"
                    />

                    <x-ts-toggle 
                        label="Ativo"
                        wire:model="is_active" 
                    />
                </div>
            </x-ts-card>

            <x-ts-card header="Variações por tamanho de veículo">
                <div class="space-y-4">
                    @foreach(VehicleSizeEnum::cases() as $size)
                        <div class="border-b border-gray-200 pb-6 last:border-b-0">
                            <h4 class="font-semibold text-gray-900">
                                {{ $size->label() }}
                            </h4>
                            <p class="text-sm text-gray-500 mb-4">{{ $size->description() }}</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <x-ts-number 
                                    label="Duração (minutos)" 
                                    wire:model.live="variants.{{ $size->value }}.duration"
                                    placeholder="Ex: 60"
                                    step="5"
                                    min="5"
                                />

                                <x-ts-currency 
                                    label="Preço" 
                                    wire:model.live="variants.{{ $size->value }}.price"
                                    placeholder="Ex: 50,00"
                                    locale="pt-BR"
                                    symbol
                                    min="1"
                                />

                                {{-- <div class="flex items-end">
                                    <x-ts-toggle 
                                        label="Ativo" 
                                        wire:model="variants.{{ $size->value }}.is_active"
                                    />
                                </div> --}}
                            </div>
                        </div>
                    @endforeach
                </div>

                <x-slot:footer>
                    <p class="text-sm text-gray-600">
                        <strong>Dica:</strong> Preencha apenas os tamanhos de veículo que você deseja oferecer. 
                        Deixe em branco os que não se aplicam ao seu serviço.
                    </p>
                </x-slot:footer>
            </x-ts-card>

            <div class="flex justify-end gap-3">
                <x-ts-button 
                    href="{{ route('services.index') }}" 
                    wire:navigate
                    text="Cancelar" 
                    color="white"
                />
                <x-ts-button 
                    type="submit" 
                    text="Salvar" 
                    :disabled="!$this->can_save"
                />
            </div>
        </form>
    @else
        <x-ts-card>
            <p class="text-gray-600">Ação permitida apenas a gestores dos estabelecimentos.</p>
        </x-ts-card>
    @endcannot
</div>