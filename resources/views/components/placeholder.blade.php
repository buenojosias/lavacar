
@props(['quantity' => 2, 'avatar' => false])
<div class="bg-white/60 dark:bg-slate-600/60 border border-gray-200 dark:border-slate-800 p-4 rounded-lg">
    <div class="w-full animate-pulse flex justify-between gap-4">
        @if ($avatar)
            <div class="bg-gray-300 dark:bg-slate-500 rounded-lg w-20 h-20"></div>
        @endif
        <div class="flex-1 flex flex-col justify-center gap-2">
            @for ($i = 0; $i < $quantity; $i++)
                @php $width = rand(1, 4).'/4'; @endphp
                <div class="h-3 bg-gray-300 dark:bg-slate-500 rounded w-{{ $width }}"></div>
            @endfor
        </div>
    </div>
</div>