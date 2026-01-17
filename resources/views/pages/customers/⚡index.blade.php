<?php

use App\Models\Customer;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;

new #[Title('Clientes')] class extends Component
{
    use WithPagination;
 
    public ?int $quantity = 10;
 
    public ?string $search = null;

    #[Computed]
    public function rows()
    {
        $customers = Customer::query()
            ->when($this->search, function (Builder $query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('whatsapp', 'like', "%{$this->search}%");
            })
            ->paginate($this->quantity)
            ->withQueryString();

        return $customers;
    }
 
    public function with(): array
    {
        return [
            'headers' => [
                ['index' => 'name', 'label' => 'Nome'],
                ['index' => 'whatsapp', 'label' => 'WhatsApp'],
                ['index' => 'app', 'label' => 'Aplicativo'],
                ['index' => 'action'],
            ],
            'rows' => $this->rows,
            'type' => 'data',
        ];
    }
};
?>

<div>
    <h2>Clientes</h2>
    <x-ts-table :$headers :$rows filter paginate id="customers">
        @interact('column_name', $row)
            <a href="{{ route('customers.show', $row) }}">{{ $row->name }}</a>
        @endinteract
        @interact('column_app', $row)
            <x-ts-icon :name="$row->user_id ? 'phosphor.check-circle-bold' : 'phosphor.x-circle-bold'" class="h-5 w-5 {{ $row->user_id ? 'text-green-500' : 'text-red-500' }}" />
        @endinteract
        @interact('column_action', $row)
            {{-- <livewire:delete.customer :customer="$row" :key="uniqid()" />  --}}
        @endinteract
    </x-ts-table>
</div>