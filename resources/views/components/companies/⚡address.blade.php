<?php

use App\Models\Company;
use App\Models\City;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

new class extends Component {
    use Interactions;

    public Company $company;

    public string $address;
    public string $district;
    public int $city_id;
    public string $zipcode;

    public $cities = [];

    public bool $renderModal = false;

    public function mount()
    {
        $this->address = $this->company->address;
        $this->district = $this->company->district;
        $this->city_id = $this->company->city_id;
        $this->zipcode = $this->company->zipcode;
    }

    public function edit()
    {
        $cities = City::orderBy('name')->get();
        $this->cities = $cities->map(function ($city) {
            return [
                'value' => $city->id,
                'label' => $city->name,
            ];
        });

        $this->renderModal = true;
        $this->dispatch('open-modal');
    }

    public function save()
    {
        $data = $this->validate([
            'address' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'city_id' => 'required|integer|exists:cities,id',
            'zipcode' => 'required|string|max:9',
        ]);

        try {
            $this->company->update($data);
            $this->dispatch('close-modal');
            $this->toast()->success('Informações atualizadas com sucesso!')->send();
        } catch (\Throwable $th) {
            $this->dispatch('close-modal');
            $this->toast()->error('Erro ao atualizar informações.')->send();
            return;
        }
    }
};
?>

<div>
    <x-ts-card header="Localização" class="detail g-2">
        <x-detail label="Endereço" value="{{ $company->address }}" />
        <x-detail label="Bairro" value="{{ $company->district }}" />
        <x-detail label="Cidade" value="{{ $company->city->name }}" />
        <x-detail label="CEP" value="{{ $company->zipcode }}" />
        <x-detail label="Latitude" value="{{ $company->latitude }}" />
        <x-detail label="Longitude" value="{{ $company->longitude }}" />
        <div class="col-span-2 text-red-500">
            MAPA AQUI
        </div>
        <x-slot:footer>
            <x-ts-button text="Editar" wire:click="edit" flat />
        </x-slot:footer>
    </x-ts-card>
    @can('isOwner')
        @if ($renderModal)
            <x-ts-modal title="Editar endereço" id="edit-location-modal" size="md">
                <form wire:submit="save" id="edit-location-form" class="space-y-4">
                    <x-ts-input label="Endereço" wire:model="address" />
                    <x-ts-input label="Bairro" wire:model="district" />
                    <x-ts-select.native label="Cidade" wire:model="city_id" :options="$cities" />
                    <x-ts-input label="CEP" wire:model="zipcode" x-mask="99999-999" />
                </form>
                <x-slot:footer>
                    <x-ts-button text="Salvar" type="submit" form="edit-location-form" />
                </x-slot:footer>
            </x-ts-modal>
        @endif
    @endcan
</div>

<script>
    this.$on('open-modal', () => {
        setTimeout(() => {
            $modalOpen('edit-location-modal');
        }, 100);
    });

    this.$on('close-modal', () => {
        $modalClose('edit-location-modal');
    });
</script>
