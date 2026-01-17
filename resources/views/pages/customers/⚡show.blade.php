<?php

use App\Models\Customer;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
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
    <x-ts-card header="Detalhes do cliente" class="detail g-2">
        <x-detail label="Nome" :value="$this->customer->name"></x-detail>
        <x-detail label="WhatsApp" :value="$this->customer->whatsapp"></x-detail>
        <x-detail label="Cadastrado por" :value="$this->customer->registrar->name ?? 'App'"></x-detail>
        @if (auth()->user()->role === 'ADMIN')
            <x-detail label="Estabelecimento" :value="$this->customer->company->name"></x-detail>
        @endif
        <x-detail label="Tem aplicativo" :bool="isset($this->customer->user_id) ? 'Y' : 'N'" />
    </x-ts-card>

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-ts-card header="Veículos do cliente"></x-ts-card>
        <x-ts-card header="Agendamentos do cliente"></x-ts-card>
    </div>
</div>