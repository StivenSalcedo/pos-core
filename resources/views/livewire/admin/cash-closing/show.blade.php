<div>
    <x-wireui.modal wire:model.defer="openShow" max-width="4xl">
        <x-wireui.card title="Cierre de caja {{\Carbon\Carbon::parse($cashClosing->closing_date)->format('m/d/y')}}">

            @if ($cashClosing->user)
                <div class="grid grid-cols-2 gap-6">
                    <x-wireui.input label="Responsable" :value="$cashClosing->user->name" readonly />
                    <x-wireui.input label="Sede" :value="$cashClosing->terminal->name" readonly />
                </div>
            @endif

            <div class="grid grid-cols-2 gap-20 mt-4">
                <section>
                    <p class="text-center font-semibold uppercase">
                        Dinero recibido
                    </p>
                    <ul class="mt-2 divide-y-2 text-sm font-semibold">
                        <li class="flex justify-between py-1.5">
                            <span>Efectivo</span>
                            <span>@formatToCop($cash)</span>
                        </li>
                        <li class="flex justify-between py-1.5">
                            <span>Tarjeta crédito</span>
                            <span>@formatToCop($credit_card)</span>
                        </li>
                        <li class="flex justify-between py-1.5">
                            <span>Tarjeta Débito</span>
                            <span>@formatToCop($debit_card)</span>
                        </li>
                        <li class="flex justify-between py-1.5">
                            <span>Transferencia</span>
                            <span>@formatToCop($transfer)</span>
                        </li>
                    </ul>

                    <p class="text-center font-semibold uppercase">
                        Totales
                    </p>

                    <ul class="mt-2 divide-y-2 text-sm font-semibold">

                        <li class="flex justify-between py-1.5">
                            <span>
                                Total propinas
                            </span>
                            <span class="text-right">
                                @formatToCop($tip)
                            </span>
                        </li>

                        <li class="flex justify-between py-1.5">
                            <span>Total egresos</span>
                            <span>@formatToCop($outputs)</span>
                        </li>

                        <li class="flex justify-between py-1.5">
                            <span>Total ventas</span>
                            <span>@formatToCop($total_sales)</span>
                        </li>

                        
                    </ul>
                </section>

                <section>
                    <li class="flex justify-between py-1.5 font-bold text-xl">
                        <span>Dinero esperado en caja</span>
                        <span>@formatToCop($cashRegister)</span>
                    </li>

                    <div class="space-y-3">
                        <x-wireui.input label="Base inicial" wire:model.defer="base" wire:model.debounce.500ms="base"  :readonly="!$isEdited" />
                        <x-wireui.input label="Dinero real en caja" wire:model.defer="price"  :readonly="!$isEdited" />
                        <x-wireui.textarea label="Observaciones"  wire:model.defer="observations" :readonly="!$isEdited" rows="3">
                            {{ $cashClosing->observations }}
                        </x-wireui.textarea>
                    </div>
                </section>
            </div>

            <x-slot:footer>
                <div class="flex justify-end gap-3 mt-4">

                    <x-wireui.button secondary x-on:click="show=false" text="Cancelar" />

                    @if ($isConfirmed)
                        <x-wireui.button primary wire:click="save" text="Guardar cambios" />
                    @else
                        <x-wireui.button positive wire:click="save" text="Confirmar cierre" />
                    @endif

                </div>
            </x-slot:footer>

        </x-wireui.card>
    </x-wireui.modal>
</div>
