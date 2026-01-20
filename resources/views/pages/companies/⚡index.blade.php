<?php

use App\Models\Company;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;

new #[Title('Estabelecimentos')] class extends Component
{
    use WithPagination;
 
    public ?int $quantity = 10;
 
    public ?string $search = null;

    #[Computed]
    public function rows()
    {
        $companies = Company::query()
            ->with('city')
            ->withCount('customers')
            ->when($this->search, function (Builder $query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('district', 'like', "%{$this->search}%");
            })
            ->paginate($this->quantity)
            ->withQueryString();

        return $companies;
    }
 
    public function with(): array
    {
        return [
            'headers' => [
                ['index' => 'name', 'label' => 'Nome'],
                ['index' => 'district', 'label' => 'Bairro'],
                ['index' => 'city.name', 'label' => 'Cidade'],
                ['index' => 'customers_count', 'label' => 'Clientes'],
                ['index' => 'is_active', 'label' => 'Ativo'],
                ['index' => 'is_visible', 'label' => 'Visível'],
                ['index' => 'action'],
            ],
            'rows' => $this->rows,
            'type' => 'data',
        ];
    }
};
?>

<div>
    <h2>Estabelecimentos</h2>
    <x-ts-table :$headers :$rows filter paginate id="companies">
        @interact('column_name', $row)
            <a href="#">{{ $row->name }}</a>
        @endinteract
        @interact('column_is_active', $row)
            <x-ts-icon :name="$row->is_active ? 'phosphor.check-circle-bold' : 'phosphor.x-circle-bold'" class="h-5 w-5 {{ $row->is_active ? 'text-green-500' : 'text-red-500' }}" />
        @endinteract
        @interact('column_is_visible', $row)
            <x-ts-icon :name="$row->is_visible ? 'phosphor.check-circle-bold' : 'phosphor.x-circle-bold'" class="h-5 w-5 {{ $row->is_visible ? 'text-green-500' : 'text-red-500' }}" />
        @endinteract
        @interact('column_action', $row)
            {{-- <livewire:delete.customer :customer="$row" :key="uniqid()" />  --}}
        @endinteract
    </x-ts-table>
</div>