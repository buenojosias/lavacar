@props(['title', 'subtitle' => null, 'description' => null, 'href' => null])

<div
    class="flex justify-between gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm hover:bg-gray-200/30 dark:hover:bg-gray-600/30 transition-colors">
    <div class="flex-1">
        @if ($href)
            <a href="{{ $href }}" class="flex-1">
        @endif
        <p class="font-semibold dark:text-gray-200 leading-6">{{ $title }}</p>
        @if ($subtitle)
            <p class="text-sm dark:text-gray-400 text-gray-500">{{ $subtitle }}</p>
        @endif
        @if ($description)
            <p class="text-xs dark:text-gray-400 text-gray-400">{{ $description }}</p>
        @endif
        @if ($href)
            </a>
        @endif
    </div>
    @if ($slot)
        <div class="flex flex-col justify-center gap-2">
            {{ $slot }}
        </div>
    @endif
</div>
