<div>
    <x-wireui.modal wire:model.defer="openShow" >
        <x-wireui.card title="N° Pago {{ $output->id }}">

            <div class="space-y-4">

                @if ($output->id)
                    <x-wireui.input label="Fecha" value="{{ $output->created_at->format('d-m-Y') }}" readonly />
                    <x-wireui.input label="Responsable" value="{{ $output->user->name }}" readonly />
                    <x-wireui.input label="Nombre" value="{{ $output->reason }}" readonly />
                @endif

                <x-wireui.input label="Valor" value="{{$output->price}}" placeholder="Ejemplo: 40000" readonly />
                <x-wireui.textarea label="Descripción" readonly>
                   {{ $output->description }}
                </x-wireui.textarea>
            </div>

            <x-slot:footer>
                <div class="text-right space-x-3">
                    <x-wireui.button secondary x-on:click="show=false" text="Cerrar" />

                    <x-wireui.button wire:click="download" load text="Descargar" textLoad="Decargando..." />
                </div>
            </x-slot:footer>
        </x-wireui.card>
    </x-wireui.modal>
</div>

