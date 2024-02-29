@props([
    'color' => 'bg-primary',
    'modal'
])

<button
    {{
        $attributes->merge([
            'type' => 'button',
            'class' => "flex items-center rounded {$color} px-4 py-2 mr-2 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:{$color} hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:{$color} focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:{$color} active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)]"
        ])
    }}
    data-te-ripple-init
    data-te-ripple-color="light"
    {!! isset($modal) ? "data-te-toggle='modal' data-te-target='{$modal}'" : "" !!}>
    {{ $slot }}
</button>
