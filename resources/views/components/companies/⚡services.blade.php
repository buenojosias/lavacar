<?php

use App\Models\Company;
use Livewire\Attributes\Computed;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

new class extends Component {
    use Interactions;

    public Company $company;

    public bool $renderModal = false;

    // public function mount()
    // {
    //     $this->name = $this->company->name;
    //     $this->cnpj = $this->company->cnpj;
    //     $this->whatsapp = $this->company->formated_whatsapp;
    //     $this->simultaneous_services = $this->company->simultaneous_services;
    // }

    #[Computed]
    public function services()
    {
        return $this->company->serviceTypes()->get();
    }

    // public function edit()
    // {
    //     $this->renderModal = true;
    //     $this->dispatch('open-modal');
    // }

    // public function save()
    // {
    //     $data = $this->validate([
    //         'name' => 'required|string|max:255',
    //         'cnpj' => 'required|string|max:18',
    //         'whatsapp' => 'required|string|max:15',
    //         'simultaneous_services' => 'required|integer|min:1',
    //     ]);

    //     $data['cnpj'] = str_replace(['.', '-', '/'], '', $data['cnpj']);
    //     $data['whatsapp'] = str_replace(['(', ')', ' ', '-'], '', $data['whatsapp']);

    //     try {
    //         $this->company->update($data);
    //         $this->dispatch('close-modal');
    //         $this->toast()->success('Informações atualizadas com sucesso!')->send();
    //     } catch (\Throwable $th) {
    //         $this->dispatch('close-modal');
    //         $this->toast()->error('Erro ao atualizar informações.')->send();
    //         return;
    //     }
    // }
};
?>

<div>
    <x-ts-card header="Serviço disponíveis" class="list">
        @foreach ($this->services as $service)
            <x-list-item :title="$service->name" :subtitle="$service->description" :href="route('services.show', $service)" />
        @endforeach
    </x-ts-card>
    @can('isOwner')
        @if ($renderModal)
            {{-- <x-ts-modal title="Editar informações" id="edit-info-modal" size="md">
                <form wire:submit="save" id="edit-info-form" class="space-y-4">
                    <x-ts-input label="Nome" wire:model="name" />
                    <x-ts-input label="CNPJ" wire:model="cnpj" x-mask="99.999.999/9999-99" />
                    <x-ts-input label="Telefone" wire:model="whatsapp" x-mask="(99) 99999-9999" />
                    <x-ts-number label="Serviços simultâneos" wire:model="simultaneous_services" min="1"
                        centralized />
                </form>
                <x-slot:footer>
                    <x-ts-button text="Salvar" type="submit" form="edit-info-form" />
                </x-slot:footer>
            </x-ts-modal> --}}
        @endif
    @endcan
</div>

<script>
    this.$on('open-modal', () => {
        setTimeout(() => {
            $modalOpen('edit-services-modal');
        }, 100);
    });

    this.$on('close-modal', () => {
        $modalClose('edit-services-modal');
    });
</script>
