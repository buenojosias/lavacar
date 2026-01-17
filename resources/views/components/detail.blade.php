@props(['label', 'value' => null, 'note' => null, 'url' => null, 'bool' => null])

<div>
    <dl class="flex-1">
        <dt>{{ $label }}</dt>
        @if (is_array($value))
            <dd>
                @foreach ($value as $val)
                    {{ $val }}
                    @if (!$loop->last)
                        /
                    @endif
                @endforeach
            </dd>
        @else
            @if ($value)
                <dd class="break-words">
                    @if ($url)
                        <a href="{{ $url }}" class="underline decoration-dashed flex items-center gap-1">
                            {{ $value }}
                            <x-ts-icon name="phosphor.link-bold" class="h-4 w-4" />
                        </a>
                    @else
                        {{ $value }}
                    @endif
                </dd>
                @if ($note)
                    <p class="note">{{ $note }}</p>
                @endif
            @elseif ($bool)
                <dd>
                    <x-ts-icon
                        :name="$bool === 'Y' ? 'phosphor.check-circle-bold' : 'phosphor.x-circle-bold'"
                        class="h-5 w-5 {{ $bool === 'Y' ? 'text-green-500' : 'text-red-500' }}" />
                </dd>
            @endif
        @endif
    </dl>
    @if ($slot)
        <div>
            {{ $slot }}
        </div>
    @endif
</div>