<?php

use App\Models\Company;
use Livewire\Attributes\Computed;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

new class extends Component {
    use Interactions;

    public Company $company;

    public bool $renderModal = false;

    #[Computed]
    public function services()
    {
        return $this->company->serviceTypes()->get();
    }
};
?>

<div>
    <x-ts-card header="Serviço disponíveis" class="list">
        @foreach ($this->services as $service)
            <x-list-item :title="$service->name" :subtitle="$service->description" :href="route('services.show', $service)" :navigate="true" />
        @endforeach
    </x-ts-card>
</div>
