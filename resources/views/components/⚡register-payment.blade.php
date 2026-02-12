<?php

use App\Models\Booking;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Enums\PaymentMethodEnum;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;
use TallStackUi\Traits\Interactions;

new class extends Component {
    use Interactions;

    public Booking $booking;
    public $wallets = [];
    public $methods = [];

    public $payment_method;
    public $wallet_id;
    public $amount;
    public $notes;

    public bool $modal = false;

    #[On('open-payment-modal')]
    public function openPaymentModal()
    {
        $this->amount = $this->booking->price / 100;
        $this->wallets = Wallet::orderBy('name', 'asc')
            ->get()
            ->map(function ($wallet) {
                return [
                    'label' => $wallet->name,
                    'value' => $wallet->id,
                ];
            })
            ->toArray();
        $this->methods = collect(PaymentMethodEnum::cases())->map(function ($paymentMethod) {
            return [
                'label' => $paymentMethod->label(),
                'value' => $paymentMethod->value,
            ];
        });
        array_unshift($this->wallets, [
            'label' => 'Selecione',
            'value' => null,
        ]);
        $this->methods->prepend([
            'label' => 'Selecione',
            'value' => null,
        ]);

        $this->modal = true;
    }

    public function submit()
    {
        $bookingData = $this->validate([
            'payment_method' => 'required|in:' . $this->methods->pluck('value')->implode(','),
            'wallet_id' => 'required|exists:wallets,id',
            'amount' => 'required|integer',
        ]);
        $bookingData['payment_status'] = 'paid';
        $bookingData['paid_at'] = now();
        $bookingData['amount'] = $this->amount * 100;

        $transactionData = [
            'company_id' => $this->booking->company_id,
            'wallet_id' => $this->wallet_id,
            'registered_by_user_id' => auth()->id(),
            'amount' => $this->amount,
            'description' => 'Pagamento referente ao agendamento #' . $this->booking->id,
            'transactionable_type' => Booking::class,
            'transactionable_id' => $this->booking->id,
        ];

        DB::beginTransaction();
        try {
            $wallet = Wallet::findOrFail($this->wallet_id);
            $transaction = Transaction::create($transactionData);
            $updatedWallet = $wallet->increment('balance', $this->amount);
            $updatedBooking = $this->booking->update($bookingData);

            if ($transaction && $updatedBooking && $updatedWallet) {
                DB::commit();
                $this->toast()->success('Pagamento registrado com sucesso')->send();
                $this->dispatch('saved');
                $this->modal = false;
                $this->reset();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }
};
?>

<x-ts-modal title="Registrar pagamento" wire size="sm">
    <p class="text-red-500">NENHUMA CARTEIRA</p>
    <form wire:submit="submit" id="register-payment-form" class="space-y-4">
        <x-ts-select.native label="Método de pagamento *" :options="$methods" wire:model="payment_method" />
        <x-ts-select.native label="Carteira *" :options="$wallets" wire:model="wallet_id" />
        <x-ts-currency label="Valor *" wire:model="amount" locale="pt-BR" symbol />
        <x-ts-input label="Observação" wire:model="notes" />
    </form>
    <x-slot:footer>
        <x-ts-button type="submit" form="register-payment-form">Registrar</x-ts-button>
    </x-slot:footer>
</x-ts-modal>
