@props(['name'])

@php
    $filePath = public_path("svg/{$name}.svg");
    $svgContent = file_exists($filePath) ? file_get_contents($filePath) : '<!-- SVG not found -->';
@endphp

{!! str_replace('<svg', '<svg ' . $attributes->merge(['class' => 'w-5 h-5']), $svgContent) !!}
