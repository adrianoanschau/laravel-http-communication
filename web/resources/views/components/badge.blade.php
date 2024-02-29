@props([
    'bg-color' => 'neutral-50',
    'text-color' => 'primary-700',
])

<span {{
    $attributes->merge([
        'class' => "inline-block whitespace-nowrap rounded-[0.27rem] bg-{$bgColor} px-2 rounded text-[13px] font-black text-center align-baseline text-[0.75em] font-bold leading-none text-{$textColor}"
    ])
}}>
    {{$slot}}
</span>
