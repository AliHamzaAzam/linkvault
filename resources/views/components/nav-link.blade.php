@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-2.5 rounded-lg bg-red-50 text-red-700 text-sm font-medium transition duration-150 ease-in-out border border-red-100 shadow-sm'
            : 'flex items-center px-4 py-2.5 rounded-lg border border-transparent text-sm font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
