<?php

use App\Models\ServiceType;
use App\Enums\VehicleSizeEnum;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

new class extends Component {
    use Interactions;

    public ServiceType $service;
    public $sizeOptions = [];
    public $vehicle_size;
    public $duration;
    public $price;

    public function mount()
    {
        $sizeOptions = VehicleSizeEnum::cases();
        $this->sizeOptions = array_map(fn($size) => ['label' => $size->label(), 'value' => $size->value], $sizeOptions);
        array_unshift($this->sizeOptions, ['label' => 'Selecione uma opção', 'value' => null]);
    }

    public function save()
    {
        $data = $this->validate([
            'vehicle_size' => 'required|in:' . implode(',', array_column($this->sizeOptions, 'value')) . '|unique:service_variants,vehicle_size,null,id,service_type_id,' . $this->service->id,
            'duration' => 'required|integer|min:5|max:1440',
            'price' => 'required|numeric|min:1',
        ]);

        try {
            $this->service->variants()->create($data);
            $this->dispatch('saved');
            $this->reset(['vehicle_size', 'duration', 'price']);
            $this->dispatch('closeModal');
            $this->toast()->success('Variação cadastrada com sucesso!')->send();
        } catch (\Throwable $th) {
            $this->dialog()->error('Erro ao cadastrar variação.')->send();
            $this->dispatch('closeModal');
            return;
        }
    }
};
?>

<x-ts-modal title="Adicionar variação" id="create-variant-modal" size="sm">
    <form wire:submit="save" class="space-y-4" id="create-variant-form">
        <x-ts-select.native label="Tamanho do veículo" :options="$sizeOptions" wire:model="vehicle_size" />
        <x-ts-number label="Duração (minutos)" wire:model="duration" placeholder="Ex: 60" step="5" min="5" />
        <x-ts-currency label="Preço" wire:model="price" placeholder="Ex: 50,00" locale="pt-BR" symbol min="1" />
    </form>
    <x-slot:footer>
        <x-ts-button type="submit" text="Salvar" form="create-variant-form" />
    </x-slot:footer>
</x-ts-modal>

<script>
    this.$on('saved', () => {
        $modalClose('create-variant-modal');
    });
</script>