<div>
    <x-wireui.modal wire:model.defer="openShow" max-width="4xl" >
        <x-wireui.card title="Cierre de caja">

            @if ($cashClosing->user)
              <div class="grid grid-cols-2 gap-6">
                <x-wireui.input label="Responsable" :value="$cashClosing->user->name" readonly />
                <x-wireui.input label="Terminal" :value="$cashClosing->terminal->name" readonly />
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
                       <span>@formatToCop($cashClosing->cash)</span>
                   </li>
                   <li class="flex justify-between py-1.5">
                       <span>Tarjeta crédito</span>
                       <span>@formatToCop($cashClosing->credit_card)</span>
                   </li>
                   <li class="flex justify-between py-1.5">
                       <span>Tarjeta Débito</span>
                       <span>@formatToCop($cashClosing->debit_card)</span>
                   </li>
                   <li class="flex justify-between py-1.5">
                       <span>Transferencia</span>
                       <span>@formatToCop($cashClosing->transfer)</span>
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
                      @formatToCop($cashClosing->tip)
                    </span>
                  </li>

                  <li class="flex justify-between py-1.5">
                    <span>Total egresos</span>
                    <span>@formatToCop($cashClosing->outputs)</span>
                  </li>

                   <li class="flex justify-between py-1.5">
                       <span>Total ventas</span>
                       <span>@formatToCop($cashClosing->total_sales)</span>
                   </li>

                   <li class="flex justify-between py-1.5">
                      <span>Total cierre</span>
                      @if ($cashClosing->price - $cashClosing->cash_register >= 0)
                          <span class="text-green-600">
                              @formatToCop($cashClosing->price - $cashClosing->cash_register)
                          </span>
                      @else
                          <span class="text-red-600">
                              @formatToCop($cashClosing->price - $cashClosing->cash_register)
                          </span>
                      @endif
                   </li>
                </ul>
              </section>

              <section>
                <li class="flex justify-between py-1.5 font-bold text-xl">
                    <span>Dinero esperado en caja</span>
                    <span>@formatToCop($cashClosing->cash_register)</span>
                </li>

                <div class="space-y-3">
                    <x-wireui.input label="Base inicial" :value="formatToCop($cashClosing->base)" readonly />
                    <x-wireui.input label="Dinero real en caja" :value="formatToCop($cashClosing->price)" readonly />
                    <x-wireui.textarea label="Observaciones" readonly rows="3" >
                        {{ $cashClosing->observations }}
                    </x-wireui.textarea>
                </div>
              </section>
            </div>

            <x-slot:footer>
                <div class="text-right mt-3">
                    <x-wireui.button secondary x-on:click="show=false" text="Cerrar" />
                </div>
            </x-slot:footer>

        </x-wireui.card>
    </x-wireui.modal>
</div>
