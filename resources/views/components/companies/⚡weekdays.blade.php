<?php

use App\Models\Company;
use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Enums\WeekdayEnum;

new class extends Component {
    public Company $company;
    public $weekdays;

    public function mount()
    {
        $this->weekdays = WeekdayEnum::cases();
    }

    #[Computed]
    public function hours()
    {
        return $this->company->hours()->orderBy('weekday')->get();
    }
};
?>

<div>
    <x-ts-card header="Horários de atendimento">
        <div class="list">
            @foreach ($this->weekdays as $weekday)
                <livewire:companies.hours :company="$company" :$weekday :hours="$this->hours->where('weekday', $weekday->value)" />
            @endforeach
        </div>
    </x-ts-card>

    <x-ts-modal id="add-hour-modal">
        {{ $weekday }}
    </x-ts-modal>
</div>
