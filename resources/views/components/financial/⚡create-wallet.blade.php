<?php

use App\Models\Wallet;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

new class extends Component
{
    use Interactions;

    public string $name = '';
    public int $balance = 0;

    public function save()
    {
        $data = $this->validate([
            'name' => 'required|string|max:50',
            'balance' => 'required|integer',
        ]);
        $data['company_id'] = auth()->user()->selected_company_id;
        
        try {
            Wallet::create($data);
            $this->toast()->success('Carteira adicionada com sucesso!')->send();
            $this->dispatch('saved');
            $this->reset();
        } catch (\Throwable $th) {
            $this->dispatch('close-modal');
            $this->dialog()->error('Erro ao adicionar carteira!')->send();
        }
    }
};
?>

<x-ts-modal title="Adicionar carteira" id="create-wallet-modal" size="sm">
    <form wire:submit="save" id="add-wallet-form" class="space-y-4">
        <x-ts-input label="Nome" wire:model="name" />
        <x-ts-currency label="Saldo inicial" wire:model="balance" locale="pt-BR" symbol />
    </form>
    <x-slot:footer>
        <x-ts-button type="submit" text="Salvar" form="add-wallet-form" />
    </x-slot:footer>
</x-ts-modal>

<script>
    this.$on('saved', () => {
        $modalClose('create-wallet-modal');
    });
</script>