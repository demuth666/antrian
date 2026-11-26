@props([
    'active' => true,
])

<span @class(['status', 'is-inactive' => ! $active])>
    {{ $active ? __('common.active') : __('common.inactive') }}
</span>
