@props(['label', 'value' => null, 'url', 'bool' => null])

<div>
    <dl>
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
                <dd class="break-words">{{ $value }}</dd>
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