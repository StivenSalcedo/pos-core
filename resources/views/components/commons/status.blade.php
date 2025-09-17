@props(['status', 'active' => 'Activo', 'inactive' => 'Inactivo'])

@if ($status)
    <span class="hidden text-white px-2 bg-red-600 rounded-full text-xs md:inline-flex">{{ $inactive }}</span>
    <span class="w-3 h-3 bg-red-600 inline-block rounded-full md:hidden"></span>
@else
    <span class="hidden text-white px-2 bg-green-600 rounded-full text-xs md:inline-flex">{{ $active }}</span>
    <span class="w-3 h-3 bg-green-600 inline-block rounded-full md:hidden"></span>
@endif
