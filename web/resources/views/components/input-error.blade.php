@props(['messages'])

<?php
    $classes = "text-sm text-red-600 dark:text-red-400 space-y-1";
    if ($messages) $classes .= " hidden";
?>

<ul {{
    $attributes->merge([
            'class' => $classes
        ])
    }}>
    @if($messages)
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    @endif
</ul>
