@php
$livewireData = get_defined_vars();
unset($livewireData['data']);
$livewireData = array_merge($data ?? [], $livewireData)
@endphp

@livewire($component, $livewireData)