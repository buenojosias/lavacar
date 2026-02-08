<?php

use App\Models\Company;
use Livewire\Component;

new class extends Component {
    public $company;
    public $customers_count;
    public $hours = [];

    public function mount($company = null)
    {
        $this->company = Company::findOrFail($company ?? auth()->user()->selected_company_id);
    }
};
?>

<div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 space-y-6">
            @island
                <livewire:companies.info :company="$company" />
            @endisland
            @island
                <livewire:companies.address :company="$company" />
            @endisland
            @island(lazy: true)
                @placeholder
                    <x-placeholder />
                @endplaceholder
                <livewire:companies.services :company="$company" />
            @endisland
        </div>
        <div class="space-y-6">
            @island
                <livewire:companies.weekdays :company="$company" />
            @endisland
            @island(lazy: true)
                @placeholder
                    <x-placeholder />
                @endplaceholder
                <livewire:companies.stats :company="$company" @saved="$refresh" />
            @endisland
            <div>
                <x-ts-button text="Ver agendamentos" :href="route('companies.bookings', $company)" wire:navigate class="w-full" color="slate" />
            </div>
        </div>
    </div>
</div>
