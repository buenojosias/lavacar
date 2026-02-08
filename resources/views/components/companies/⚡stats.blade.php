<?php

use App\Models\Company;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

new class extends Component {
    use Interactions;

    public Company $company;

    public function toggleActive()
    {
        $this->dialog()
            ->question('Tem certeza que deseja ' . ($this->company->is_active ? 'desativar' : 'ativar') . ' o estabelecimento?')
            ->confirm('Confirmar', 'toggleActiveConfirmed')
            ->cancel('Cancelar')
            ->send();
    }

    public function toggleActiveConfirmed()
    {
        $this->company->is_active = !$this->company->is_active;
        $this->company->save();
        $this->toast()->success('Sucesso', 'Estabelecimento atualizado com sucesso!')->send();
    }

    public function toggleVisible()
    {
        $this->dialog()
            ->question('Tem certeza que deseja ' . ($this->company->is_visible ? 'ocultar' : 'exibir') . ' o estabelecimento no aplicativo?')
            ->confirm('Confirmar', 'toggleVisibleConfirmed')
            ->cancel('Cancelar')
            ->send();
    }

    public function toggleVisibleConfirmed()
    {
        $this->company->is_visible = !$this->company->is_visible;
        $this->company->save();
        $this->toast()->success('Sucesso', 'Estabelecimento atualizado com sucesso!')->send();
    }
};
?>

<x-ts-card header="Estatísticas">
    <div class="detail">
        <x-detail label="Clientes" :value="$company->customers->count()" />
    </div>
    <div class="grid grid-cols-2 detail my-4">
        <x-detail label="Ativo" :bool="$company->is_active ? 'Y' : 'N'">
            @can('isAdmin', $company)
                <x-ts-button :text="$company->is_active ? 'Desativar' : 'Ativar'" color="light" flat sm wire:click="toggleActive" />
            @endcan
        </x-detail>
        <x-detail label="Visível" :bool="$company->is_visible ? 'Y' : 'N'">
            @can('isOwner', $company)
                <x-ts-button :icon="$company->is_visible ? 'phosphor.eye-slash' : 'phosphor.eye'" color="light" flat wire:click="toggleVisible" />
            @endcan
        </x-detail>
    </div>
    <div class="detail">
        @island()
            <x-detail label="Agendamentos" :value="$company->bookings->count()" />
            <x-detail label="Avaliações" :value="$company->rating_count" />
        @endisland
    </div>
</x-ts-card>
