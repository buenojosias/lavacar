<?php

use App\Models\Customer;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public $customerId;

    public function mount($customer)
    {
        $this->customerId = $customer;
    }

    #[Computed]
    public function customer()
    {
        $customer = Customer::query()
            ->when(auth()->user()->role === 'ADMIN', function ($query) {
                $query->with('company');
            })
            ->when(auth()->user()->role === 'PARTNER' && auth()->user()->selected_company_role === 'OWNER', function ($query) {
                $query->with('registrar');
            })
            ->find($this->customerId);

        return $customer;
    }
};
?>

<div>
    <div class="page-header">
        <h2>Cliente</h2>
    </div>
    <x-ts-card header="Detalhes do cliente" class="detail g-2">
        <x-detail label="Nome" :value="$this->customer->name" />
        <x-detail label="WhatsApp" :value="$this->customer->whatsapp" />
        <x-detail label="Cadastrado por" :value="$this->customer->registrar->name ?? 'App'" />
        @if (auth()->user()->role === 'ADMIN')
            <x-detail label="Estabelecimento" :value="$this->customer->company->name" url="#" />
        @endif
        <x-detail label="Tem aplicativo" :bool="isset($this->customer->user_id) ? 'Y' : 'N'" />
    </x-ts-card>

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
        @island(lazy: true)
            @placeholder
                <x-placeholder quantity="3" />
            @endplaceholder
            <livewire:customers.vehicles :customer="$this->customer" />
        @endisland
        @island(lazy: true)
            @placeholder
                <x-placeholder quantity="3" />
            @endplaceholder
            <livewire:customers.bookings :customer="$this->customer" />
        @endisland
    </div>
</div>
