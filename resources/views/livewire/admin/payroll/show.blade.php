<div>
    <x-wireui.modal wire:model.defer="openShow" >
        <x-wireui.card title="N° Pago {{ $payroll->id }}">

            <div class="space-y-4">

                @if ($payroll->id)
                    <x-wireui.input label="Fecha" value="{{ $payroll->created_at->format('d-m-Y') }}" readonly />
                    <div class="grid grid-cols-2 gap-3">
                        <x-wireui.input label="No Identificación" value="{{ $payroll->staff->no_identification }}" readonly />
                        <x-wireui.input label="Nombre" value="{{ $payroll->staff->names }}" readonly />
                    </div>
                @endif

                <x-wireui.input label="Cantidad formato numérico" value="{{$payroll->price}}" placeholder="Ejemplo: 40000" readonly />
                <x-wireui.textarea label="Descripción" readonly>
                   {{ $payroll->description}}
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
