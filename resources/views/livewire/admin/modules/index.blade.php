<div class="container pt-8 relative">
    <x-wireui.card title="Módulos" cardClasses="max-w-6xl mx-auto">

        <x-slot:header>
            <div x-data class="px-4 py-1 flex justify-between items-center border-b">
                <h3 class="font-semibold whitespace-normal text-lg">Módulos</h3>
                <x-wireui.button x-on:click="$wire.emit('enableAllModules')" text="Habilitar todos los módulos" sm />
            </div>
        </x-slot:header>

        <ul class="grid grid-cols-4">
            @foreach ($modules as $item)
                <li>
                    <a class="text-center py-1 cursor-pointer select-none h-20 flex items-center justify-center border {{ $item->is_enabled ? 'font-semibold' : 'line-through bg-slate-100' }}"
                        wire:click="$emit('togglePermission', '{{$item->id}}', {{ (int)$item->is_enabled }}, {{ (int)$item->is_functionality }})">
                        {{ $item->name }}
                    </a>
                </li>
            @endforeach
        </ul>


    </x-wireui.card>

    <x-wireui.card title="Funciones" cardClasses="max-w-6xl mx-auto mt-4">

        <x-slot:header>
            <div class="px-4 py-1 flex justify-between items-center border-b">
                <h3 class="font-semibold whitespace-normal text-lg">Funcionalidades</h3>
            </div>
        </x-slot:header>

        <ul class="grid grid-cols-4">
            @foreach ($functionalities as $item)
                <li>
                    <a class="text-center py-1 cursor-pointer select-none h-20 flex items-center justify-center border {{ $item->is_enabled ? 'font-semibold' : 'line-through bg-slate-100' }}"
                        wire:click="$emit('togglePermission', '{{$item->id}}', '{{ $item->is_enabled }}', '{{ $item->is_functionality }}')">
                        {{ $item->name }}
                    </a>
                </li>
            @endforeach
        </ul>


    </x-wireui.card>
    <x-loads.panel-fixed wire:loading wire:target='togglePermission'  text="cargando" class="z-50"/>
</div>

@push('js')

    <script>
        document.addEventListener('livewire:load', function () {

            Livewire.on('togglePermission', (id, is_enabled, is_functionality) => {
                let text1 = Boolean(is_functionality) ? 'esta funcionalidad' : 'este módulo'
                let text2 = Boolean(is_enabled) ? `¿Quieres desactivar ${text1}?` : `¿Quieres activar ${text1}?`;

                Swal.fire({
                    title: '¿Estas seguro?',
                    text: text2,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, aceptar',
                    cancelButtonText: 'Cancelar',
                    showLoaderOnConfirm: true,
                    preConfirm: function() {
                        return @this.togglePermission(id);
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                });
            });

            Livewire.on('enableAllModules', () => {
                Swal.fire({
                    title: '¿Estas seguro?',
                    text: '¿Quieres habilitar todos los módulos?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, aceptar',
                    cancelButtonText: 'Cancelar',
                    showLoaderOnConfirm: true,
                    preConfirm: function() {
                        return @this.enableAllModules();
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                });
            });
        });
    </script>
@endpush

