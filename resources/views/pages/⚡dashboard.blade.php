<?php

use App\Models\Company;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public $firstName;
    public $company;

    public function mount()
    {
        $nameParts = explode(' ', auth()->user()->name);
        $this->firstName = $nameParts[0];

        if (auth()->user()->role === 'PARTNER') {
            $this->company = Company::select('id', 'name')->find(auth()->user()->selected_company_id);
        }
    }
};
?>

<div class="space-y-6">
    <h2>Olá {{ $firstName }}</h2>

    @if ($company)
        <x-ts-card>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                <div class="flex-1">
                    <div class="text-lg font-semibold">Estabelecimento</div>
                    <div class="text-xl font-bold">{{ $company->name }}</div>
                </div>
                <div class="text-center">
                    <x-ts-button text="Gerenciar" href="{{ route('companies.own') }}" wire:navigate flat />
                </div>
            </div>
        </x-ts-card>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <livewire:widgets::dashboard.company-customers-count />
        <livewire:widgets::dashboard.company-today-services-count />
    </div>
</div>
