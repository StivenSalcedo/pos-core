<div>
    <x-wireui.modal wire:model.defer="open" max-width="xl">
        <x-wireui.card title="Registrar Pago">

            <x-wireui.errors />

            <div class="grid sm:grid-cols-2 gap-6">
                <x-wireui.input 
                    label="Monto"
                    prefix="$"
                    type="number"
                    min="100"
                    step="100"
                    wire:model.defer="amount"
                />

                <x-wireui.native-select 
                    label="Método de Pago"
                    placeholder="Seleccione un método"
                    :options="$paymentMethods"
                    wire:model.defer="payment_method_id"
                />

                <x-wireui.input 
                    label="Referencia (opcional)"
                    placeholder="N° comprobante, transacción, etc."
                    wire:model.defer="reference"
                    class="sm:col-span-2"
                />
            </div>

            <x-slot:footer>
                <div class="flex justify-end space-x-3">
                    <x-wireui.button flat label="Cancelar" x-on:click="$set('open', false)" />
                    <x-wireui.button primary label="Guardar Pago" wire:click="save" />
                </div>
            </x-slot:footer>

        </x-wireui.card>
    </x-wireui.modal>
</div>
