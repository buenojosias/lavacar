<?php

use App\Models\Company;
use Livewire\Component;

new class extends Component {
    public $company;
    public $customers_count;
    public $hours = [];

    public function mount(Company $company)
    {
        $this->company = $company;
        $this->hours = $company->hours()->orderBy('weekday')->get();
    }
};
?>

<div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 space-y-6">
            <x-ts-card header="Informações do estabelecimento" class="detail g-2">
                <x-detail label="Nome" value="{{ $company->name }}" />
                <x-detail label="CNPJ" value="{{ $company->cnpj }}" />
                <x-detail label="Telefone" value="{{ $company->whatsapp }}" />
                <x-detail label="Serviços simultâneos" value="{{ $company->simultaneous_services }}" />
            </x-ts-card>
            <x-ts-card header="Localização" class="detail g-2">
                <x-detail label="Endereço" value="{{ $company->address }}" />
                <x-detail label="Bairro" value="{{ $company->district }}" />
                <x-detail label="Cidade" value="{{ $company->city->name }}" />
                <x-detail label="CEP" value="{{ $company->zipcode }}" />
                <x-detail label="Latitude" value="{{ $company->latitude }}" />
                <x-detail label="Longitude" value="{{ $company->longitude }}" />
                <div class="col-span-2 text-red-500">
                    MAPA AQUI
                </div>
            </x-ts-card>
            <x-ts-card header="Serviço disponíveis" class="detail">
                @foreach ($company->serviceTypes()->get() as $service)
                    <x-detail :label="$service->name" :value="$service->description" />
                @endforeach
            </x-ts-card>
        </div>
        <div class="space-y-6">
            <x-ts-card header="Horários de atendimento">
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
            </x-ts-card>
            <x-ts-card header="Estatísticas">
                <div class="detail">
                    <x-detail label="Clientes" :value="$company->customers->count()" />
                </div>
                <div class="grid grid-cols-2 detail my-4">
                    <x-detail label="Ativo" :bool="$company->is_active ? 'Y' : 'N'" />
                    <x-detail label="Visível" :bool="$company->is_visible ? 'Y' : 'N'" />
                </div>
                <div class="detail">
                    <x-detail label="Total de serviços" value="XXX" />
                    <x-detail label="Avaliações" value="{{ $company->rating_count }} ({{ $company->rating_avg }})" />
                </div>
            </x-ts-card>
        </div>
    </div>
</div>
