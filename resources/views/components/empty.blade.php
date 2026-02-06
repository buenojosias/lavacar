@props([
    'icon' => null,
    'title' => null,
    'description' => null,
])

<div
    class="text-center py-10 space-y-2 bg-primary-800/5 dark:bg-primary-800/5 border border-primary-800/10 dark:border-primary-700/20 rounded-lg">
    @if ($icon)
        {{ svg('phosphor-' . $icon . '-light', 'w-12 h-12 mx-auto text-secondary-800/50 dark:text-secondary-200/50') }}
    @endif
    <p class="font-medium text-lg text-secondary-700 dark:text-secondary-200">{{ $title ?? 'Nenhum registro encontrado' }}</p>
    @if ($description)
        <p class="text-sm text-secondary-500 dark:text-secondary-400">{{ $description }}</p>
    @endif
</div>