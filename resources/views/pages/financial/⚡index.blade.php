<?php

use App\Models\Transaction;
use App\Models\Wallet;
use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component {
    #[Computed]
    public function wallets()
    {
        return Wallet::get();
    }

    public function with(): array
    {
        return [
            'headers' => [
                ['index' => 'created_at', 'label' => 'Data'],
                ['index' => 'description', 'label' => 'Descrição'],
                ['index' => 'formatted_amount', 'label' => 'Valor'],
            ],
            'rows' => Transaction::latest()->limit(10)->get()->map(function ($transaction) {
                return [
                    'created_at' => $transaction->created_at->format('d/m/Y'),
                    'description' => $transaction->description,
                    'formatted_amount' => $transaction->formatted_amount,
                ];
            }),
        ];
    }
};
?>

<div class="space-y-6">
    <x-ts-stats title="Saldo atual" icon="phosphor.currency-circle-dollar" number="R$ {{ number_format($this->wallets->sum('balance') / 100, 2, ',', '.') }}" outline />

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div>
            <x-ts-card header="Carteiras" class="list">
                @forelse($this->wallets as $wallet)
                    <x-list-item :title="$wallet->name">
                        <x-slot:subtitle>
                            {{ $wallet->formatted_balance }}
                        </x-slot:subtitle>
                    </x-list-item>
                @empty
                    <x-empty title="Nenhuma carteira cadastrada" />
                @endforelse
                <x-slot:footer>
                    <x-ts-button text="Adicionar carteira" x-on:click="$modalOpen('create-wallet-modal')" />
                </x-slot:footer>
            </x-ts-card>
        </div>

        <div class="col-span-2">
            <x-ts-card header="Últimos lançamentos">
                <x-ts-table :$headers :$rows />
            </x-ts-card>
        </div>
    </div>
    <livewire:financial.create-wallet @saved="$refresh" />
</div>
