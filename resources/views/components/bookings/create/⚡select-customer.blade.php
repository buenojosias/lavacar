<?php

use App\Models\Customer;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component
{
    #[Computed]
    public function customers()
    {
        $customers = Customer::all()
            ->map(fn($customer) => [
                'label' => $customer->name,
                'value' => $customer->id,
            ]);

        return $customers;
    }
};
?>

<div>
    <x-ts-select.styled label="Cliente" :options="$this->customers"
        x-on:select="$dispatch('customer-selected', { id: $event.detail.select.value })"
        x-on:remove="$dispatch('customer-selected', { id: null })" />
</div>