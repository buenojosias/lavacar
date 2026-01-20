<?php

use App\Models\Customer;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    public function customers()
    {
        return Customer::count();
    }
};
?>

<x-ts-stats :number="$this->customers" title="Clientes" />