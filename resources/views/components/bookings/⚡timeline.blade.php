<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public $booking;

    public function mount($booking)
    {
        $this->booking = $booking;
    }

    #[On('updated')]
    public function refresh()
    {
        //
    }

    #[Computed]
    public function steps()
    {
        return $this->booking->statusHistories()->orderBy('created_at', 'asc')->get();
    }
};
?>

<div>
    @dump(time())
    <x-ts-card header="Timeline">
        <div class="relative ml-3 border-l-2 border-gray-200 dark:border-gray-600">
            @foreach ($this->steps as $step)
                <div class="mb-6 ml-6">
                    <span
                        class="absolute -left-2 mt-1 flex h-4 w-4 items-center justify-center rounded-full bg-blue-100 ring-4 ring-white dark:bg-slate-500 dark:ring-slate-700">
                        <div class="h-2 w-2 rounded-full bg-blue-600 dark:bg-white"></div>
                    </span>
                    <p class="mb-1 flex items-center text-sm font-semibold text-gray-900 dark:text-white">
                        {{ $step->status->label() }}
                    </p>
                    <time class="mb-2 block text-xs font-normal leading-none text-gray-400 dark:text-gray-400">
                        {{ $step->created_at->format('d/m/Y H:i') }}
                    </time>
                </div>
            @endforeach
        </div>
    </x-ts-card>
</div>
