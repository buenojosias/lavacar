<?php

use App\Enums\VehicleSizeEnum;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

new class extends Component {
    use Interactions;

    public $plate = null;
    public $brand_model = null;
    public $category = null;
    public $year = null;
    public $size = null;
    public $color = null;

    public $company_id = null;
    public $vehicle_id = null;
    public $customer_id = null;
    public $nickname = null;

    public $sizeOptions = [];

    public function mount($customer)
    {
        $this->company_id = auth()->user()->selected_company_id;
        $this->customer_id = $customer->id;

        $sizesOptions = VehicleSizeEnum::cases();
        foreach ($sizesOptions as $size) {
            $this->sizeOptions[] = [
                'value' => $size->value,
                'label' => $size->label(),
                'description' => $size->description(),
            ];
        }
    }

    public function save()
    {
        $data = $this->validate([
            'plate' => 'required|string|max:8',
            'brand_model' => 'required|string|max:255',
            'category' => 'string|max:100',
            'year' => 'integer|min:1900|max:' . (date('Y') + 1),
            'size' => 'required|in:' . implode(',', array_column($this->sizeOptions, 'value')),
            'color' => 'required|string|max:50',
        ]);

        $exists = Vehicle::query()
            ->where('plate', $this->plate)
            ->whereHas('companyVehicles', function ($query) {
                $query->where('company_id', $this->company_id);
            })
            ->exists();

        if ($exists) {
            $this->addError('plate', 'Veículo já cadastrado.');
            return;
        }

        $this->nickname = $this->brand_model . ' (' . $this->plate . ')';

        DB::beginTransaction();

        try {
            $vehicle = Vehicle::create([
                'plate' => $this->plate,
                'brand_model' => $this->brand_model,
                'category' => $this->category,
                'year' => $this->year,
                'size' => $this->size,
                'color' => $this->color,
            ]);

            $companyVehicle = $vehicle->companyVehicles()->create([
                'company_id' => $this->company_id,
                'customer_id' => $this->customer_id,
                'nickname' => $this->nickname,
            ]);
            DB::commit();

            $this->reset(['plate', 'brand_model', 'category', 'year', 'size', 'color', 'nickname']);
            $this->dispatch('created');
            $this->dispatch('closeModal');
            $this->toast()->success('Veículo cadastrado com sucesso!')->send();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dialog()->error('Erro ao cadastrar veículo.')->send();
            $this->dispatch('closeModal');
            return;
        }
    }
};
?>

<x-ts-modal title="Adicionar veículo" id="create-vehicle-modal" size="sm">
    <x-ts-errors class="mb-4" />
    <form wire:submit.prevent="save" id="create-vehicle-form" class="grid sm:grid-cols-2 gap-4">
        <x-ts-input label="Placa" wire:model="plate" maxlength="8" />
        <div class="col-span-2">
            <x-ts-input label="Marca/Modelo" wire:model="brand_model" />
        </div>
        <x-ts-input label="Categoria" wire:model="category" />
        <x-ts-input label="Ano" wire:model="year" type="number" min="1900" max="{{ date('Y') + 1 }}" />
        <x-ts-select.styled label="Tamanho" wire:model="size" :options="$sizeOptions"
            select="label:label|value:value|note:description" />
        <x-ts-input label="Cor" wire:model="color" />
    </form>
    <x-slot:footer>
        <x-ts-button text="Salvar" type="submit" form="create-vehicle-form" />
    </x-slot:footer>
</x-ts-modal>

<script>
    this.$on('closeModal', () => {
        $modalClose('create-vehicle-modal');
    });
</script>
