<?php

use App\Models\ServiceType;
use Livewire\Component;
use Livewire\Attributes\On;
use TallStackUi\Traits\Interactions;

new class extends Component {
    use Interactions;

    public ServiceType $service;
    public $serviceVariant;
    public string $size;
    public string $vehicle_size;
    public int $duration;
    public int $price;
    public bool $is_active;

    #[On('open-edit-modal')]
    public function openEditModal($id)
    {
        $this->serviceVariant = $this->service->variants()->findOrFail($id);
        $this->size = $this->serviceVariant->vehicle_size->label();
        $this->duration = $this->serviceVariant->duration;
        $this->price = $this->serviceVariant->price / 100;
        $this->is_active = $this->serviceVariant->is_active;
        $this->dispatch('loaded');
    }

    public function save()
    {
        $data = $this->validate([
            'duration' => 'required|integer|min:5|max:1440',
            'price' => 'required|numeric|min:1',
            'is_active' => 'required|boolean',
        ]);

        try {
            $this->serviceVariant->update($data);
            $this->dispatch('saved');
            $this->reset(['duration', 'price', 'is_active']);
            $this->dispatch('closeModal');
            $this->toast()->success('Alterações salvas com sucesso!')->send();
        } catch (\Throwable $th) {
            $this->dialog()->error('Erro ao salvar alterações.')->send();
            $this->dispatch('closeModal');
            return;
        }
    }
};
?>

<x-ts-modal title="Editar variação" id="edit-variant-modal" size="sm">
    <form wire:submit="save" class="space-y-4" id="edit-variant-form">
        <x-ts-input label="Tamanho do veículo" wire:model="size" readonly />
        <x-ts-number label="Duração (minutos)" wire:model="duration" placeholder="Ex: 60" step="5" min="5" />
        <x-ts-currency label="Preço" wire:model="price" placeholder="Ex: 50,00" locale="pt-BR" symbol min="1" />
        <x-ts-toggle label="Ativo" wire:model="is_active" />
    </form>
    <x-slot:footer>
        <x-ts-button type="submit" text="Salvar" form="edit-variant-form" />
    </x-slot:footer>
</x-ts-modal>

<script>
    this.$on('loaded', () => {
        $modalOpen('edit-variant-modal');
    });
    this.$on('saved', () => {
        $modalClose('edit-variant-modal');
    });
</script>