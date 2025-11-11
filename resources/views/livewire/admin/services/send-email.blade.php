<x-wireui.modal wire:model.defer="open" max-width="2xl">
    <x-wireui.card title="Enviar detalle del servicio">

        <x-wireui.input 
            label="Correo destinatario" 
            wire:model.defer="emailTo"
            placeholder="cliente@correo.com" 
        />

        <x-wireui.textarea 
            label="Mensaje adicional"
            wire:model.defer="emailMessage"
            placeholder="Agregue una nota al correo..." 
        />

        <x-wireui.checkbox 
            label="Adjuntar PDF del servicio"
            wire:model="attachPdf"
        />

        <x-slot:footer>
            <div class="flex justify-end space-x-2">
                <x-wireui.button flat text="Cancelar" x-on:click="$wire.open=false" />
                <x-wireui.button primary wire:click="sendEmail" text="Enviar" spinner="sendEmail" />
            </div>
        </x-slot:footer>

    </x-wireui.card>
</x-wireui.modal>
