<div wire:island="customer">
    <div class="page-header">
        <h2>Agendar serviço</h2>
        @dump(time())
    </div>

    @dump($customer_id)

    <x-ts-card>
        <form wire:submit="save" id="booking-form" class="grid grid-cols-1 md:grid-cols-2 gap-4">

            @island(name: 'customers')
                @dump(rand(1, 1000))
                <!-- Carregamento dos clientes do estabelecimento selecionado via API -->
                <x-ts-select.styled label="Cliente" wire:model.live="customer_id" :request="route('api.customers', ['company_id' => auth()->user()->selected_company_id])"
                    placeholder="Selecione um cliente..." searchable>
                    <x-slot name="loading">
                        <div class="flex items-center space-x-2">
                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-primary-600"></div>
                            <span>Carregando clientes...</span>
                        </div>
                    </x-slot>
                </x-ts-select.styled>
            @endisland

            <div wire:key="vehicles-{{ $customer_id ?? 'none' }}">
                @island(name: 'vehicles', always: true)
                    <!-- Popular este select com os veículos do cliente selecionado -->
                    @dump($this->vehicles, time())
                    <x-ts-select.native label="Veículo" wire:model.live="company_vehicle_id" :options="$this->vehicles"
                        :disabled="!$customer_id"
                        placeholder="{{ $customer_id ? 'Selecione um veículo...' : 'Selecione um cliente primeiro' }}">
                        <x-slot name="loading" wire:loading wire:target="customer_id">
                            <div class="flex items-center space-x-2">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-primary-600"></div>
                                <span>Carregando veículos...</span>
                            </div>
                        </x-slot>
                    </x-ts-select.native>
                @endisland
            </div>


            <!-- Popular este select com a lista de variações de serviços do estabelecimento cujo size corresponda com o veículo selecionado -->
            {{-- <x-ts-select.native
                label="Serviço"
                wire:model.live="service_variant_id"
                :options="$serviceOptions"
                :disabled="!$company_vehicle_id"
                placeholder="{{ $company_vehicle_id ? 'Selecione um serviço...' : 'Selecione um veículo primeiro' }}"
                clearable
                :key="'service-select-' . $serviceSelectKey"
            >
                <x-slot name="loading" wire:loading wire:target="company_vehicle_id">
                    <div class="flex items-center space-x-2">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-primary-600"></div>
                        <span>Carregando serviços...</span>
                    </div>
                </x-slot>
            </x-ts-select.native> --}}

            <!-- Preencher este campo automaticamente, de acordo com o service_variant_id -->
            {{-- <x-ts-currency
                label="Custo"
                wire:model="price"
                readonly
                locale="pt-BR"
                symbol
                placeholder="Selecione um serviço..."
            >
                <x-slot name="loading" wire:loading wire:target="service_variant_id">
                    <div class="flex items-center space-x-2">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-primary-600"></div>
                        <span>Calculando preço...</span>
                    </div>
                </x-slot>
            </x-ts-currency> --}}

            <!-- Campo para selecão livre -->
            {{-- <x-ts-date
                label="Data"
                wire:model.live="scheduled_date"
                :min-date="now()"
                format="DD/MM/YYYY"
                placeholder="Selecione uma data..."
            /> --}}

            <!-- Limitar este campo aos horários disponíveis, de acordo com o dia da semana -->
            {{-- <x-ts-time
                label="Horário"
                wire:model="time"
                :min-hour="$minHour"
                :max-hour="$maxHour"
                :min-minute="$minMinute"
                :max-minute="$maxMinute"
                :disabled="!$scheduled_date"
                placeholder="{{ $scheduled_date ? 'Selecione um horário...' : 'Selecione uma data primeiro' }}"
                step-minute="5"
                format="24"
                :key="'time-select-' . $timeSelectKey"
            /> --}}

        </form>
        {{-- <x-slot name="footer">
            <x-ts-button
                type="submit"
                form="booking-form"
                :disabled="!$customer_id || !$company_vehicle_id || !$service_variant_id || !$scheduled_date || !$time"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-not-allowed"
            >
                <span wire:loading.remove wire:target="save">Agendar</span>
                <span wire:loading wire:target="save">
                    <div class="flex items-center space-x-2">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                        <span>Agendando...</span>
                    </div>
                </span>
            </x-ts-button>
        </x-slot> --}}
    </x-ts-card>
</div>
