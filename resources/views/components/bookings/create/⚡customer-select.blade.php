<?php

use Livewire\Component;

new class extends Component {
    public $customerId;

    public function updatedCustomerId($value)
    {
        if ($value) {
            return;
        }
        $this->dispatch('changed-customer', null);
    }
};
?>

<div>
    <x-ts-select.styled label="Cliente" wire:model.live="customerId"
        x-on:select="$dispatch('changed-customer', { customerId: event.detail.select.value })" :request="route('api.customers', ['company_id' => auth()->user()->selected_company_id])" />
</div>

{{-- <script>
    this.$on('changed-customer', (data) => {
        console.log(data);
    })
</script> --}}
