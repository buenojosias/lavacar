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
        $this->hours = $this->company->hours()->orderBy('weekday')->get();
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
            {{-- <x-ts-card header="Horários de atendimento">
                <ul class="space-y-2">
                    @foreach ($hours->groupBy('weekday') as $day => $hours)
                        <li class="flex justify-between items-center gap-2">
                            <div class="flex-1">
                                <b>{{ \App\Enums\WeekdayEnum::from($day)->label() }}</b><br>
                                <ul class="ml-2">
                                    @foreach ($hours as $hour)
                                        <li>{{ $hour->opens_at->format('H:i') }} -
                                            {{ $hour->closes_at->format('H:i') }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div>ABC</div>
                        </li>
                    @endforeach
                </ul>
            </x-ts-card> --}}
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
