@props(['active' => false])

<li><a {{ $attributes->class([
    'hover:text-blue-500',
    'text-red-800' => $active,
    'underline' => $active,
    'font-bold' => $active]) }} >
    {{ $slot }}
</a></li>