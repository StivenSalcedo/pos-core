<div x-data="alpineBill" class="px-4 pt-8 pb-14 text-gray-700">

  <div class="mx-auto max-w-7xl">
    <section class="col-span-full space-y-5">

      <x-wireui.card title="Información del cliente">
        @if (App\Services\FactusConfigurationService::isApiEnabled())
      <div class="flex justify-end -mt-3">
       <x-buttons.switch label="Factura Electrónica" wire:model="electronicInvoice" active="Sí" inactive="No" />
      </div>
      <br>
       @endif
      
     
     
        <div class="flex justify-end -mt-3">
          <livewire:admin.customers.search>
        </div>

        <div class="grid grid-cols-3 gap-3">

          <div class="border-b border-gray-300">
            <label class="text-sm font-bold">Nit/Cédula</label>
            <h1 x-text="customer.no_identification" class="mt-2 h-5 text-sm truncate"></h1>
          </div>
          <div class="border-b border-gray-300">
            <label class="text-sm font-bold">Nombre</label>
            <h1 x-text="customer.names" class="mt-2 h-5 text-sm truncate"></h1>
          </div>
          <div class="border-b border-gray-300">
            <label class="text-sm font-bold">Teléfono</label>
            <h1 x-text="customer.phone" class="mt-2 h-5 text-sm truncate"></h1>
          </div>
        </div>

        @error('customer.id')
          <x-slot:footer>
            <div class="text-right">
              <x-wireui.error for="customer.id" class="mr-2" />
            </div>
          </x-slot:footer>
        @enderror

      </x-wireui.card>

      <x-wireui.card title="Información del producto">

        <div class="flex justify-end -mt-3">
          <livewire:admin.products.search>
        </div>

        <div wire:ignore class="">

          <div class="grid grid-cols-10 gap-6">
            <div class="col-span-3 border-b border-gray-300">
              <label class="text-sm font-bold">Referencia</label>
              <h1 x-text="product.reference" class="h-5 text-sm"></h1>
            </div>

            <div class="col-span-3 border-b border-gray-300">
              <label class="text-sm font-bold">Nombre</label>
              <h1 x-text="product.name" class="h-5 text-sm"></h1>
            </div>

            <div class="col-span-2 border-b border-gray-300">
              <label class="text-sm font-bold">Impuestos</label>
              <h1 x-text="rates" class="h-5 text-sm"></h1>
            </div>

            <div class="col-span-2 border-b border-gray-300">
              <label class="text-sm font-bold">Stock</label>
              <h1 x-text="product.stock" class="h-5 text-sm"></h1>
            </div>
          </div>

          <div class="grid grid-cols-10 gap-6 mt-4">

            <div class="relative col-span-2 border-b border-gray-500">
              <label class="text-sm font-bold">Presentaciones</label>
              <div x-on:click.away="showDropdownPrensentations=false">
                <div x-on:click="showDropdownPrensentations = !showDropdownPrensentations"
                  class="flex items-center cursor-pointer">
                  <span x-text="presentation.name ?? 'No Aplica' " class="text-sm"></span>
                  <i class="ml-auto duration-300 ico icon-arrow-b"
                    :class="showDropdownPrensentations ? 'rotate-180' : ''"></i>
                </div>
                <ul x-show="showDropdownPrensentations"
                  class="absolute w-full text-sm bg-white border divide-y shadow-sm select-none">
                  <template x-for="(item, index) in presentations">
                    <li class="flex px-2 cursor-pointer hover:bg-slate-100"
                      x-html="item.name + '<span class=\'ml-auto\'>' + formatToCop(item.price) + '</span>'"
                      :class="item.id == presentation.id ? 'font-bold text-blue-700' : ''"
                      x-on:click="setPresentation(item)"></li>
                  </template>
                </ul>
              </div>
            </div>

            <div x-ref="total" class="col-span-2 border-b border-gray-300">
              <label class="text-sm font-bold">Precio Unidad</label>
              <h1 x-text="formatToCop(price)" class="h-5 text-sm text-center"></h1>
            </div>

            <div x-ref="cant" class="col-span-2 border-b border-gray-500">
              <label class="text-sm font-bold">Cantidad</label>
              <input x-ref="amount" x-bind="inputAmount" x-model="amount" onkeypress='return onlyNumbers(event)'
                type="text"
                class="py-0 px-0 w-full h-5 text-sm text-center border-none focus:border-transparent focus:ring-0 focus:outline-none">
            </div>

            <div x-ref="desc" class="col-span-2 border-b border-gray-500">
              <label class="text-sm font-bold">Descuento en porcentaje</label>
              @include('livewire.admin.bills.percent')
            </div>

            <div x-ref="desc" class="col-span-2 border-b border-gray-500">
              <label class="text-sm font-bold">Descuento en pesos</label>
              <input x-bind="inputDiscount" x-model="discount" x-bind:disabled="Boolean(percent)"
                onkeypress='return onlyNumbers(event)' type="text"
                class="py-0 px-0 w-full h-5 text-sm text-center border-none focus:border-transparent focus:ring-0 focus:outline-none">
            </div>

          </div>

          <div class="mt-4 text-right">
            <div>
              <label class="text-2xl font-bold">Total</label>
              <h1 x-text="formatToCop(total)" class="text-xl font-bold"></h1>
            </div>
          </div>

        </div>

        <x-slot:footer>
          <div class="flex justify-end items-center space-x-2">
            <x-wireui.error for="products.*.id" class="mr-2" />
            <x-wireui.error for="products.*.discount" class="mr-2" />
            <x-wireui.error for="products" class="mr-2" />

            <div wire:ignore>
              <div x-show="alert" class="inline-flex items-center mr-2 text-sm text-red-500">
                <i class="mr-1 text-2xl ico icon-alert"></i>
                <span x-text="alert"></span>
              </div>

              <div class="lg:hidden">
                <x-wireui.button icon="inventory" x-on:click="$dispatch('open-products')" text="Productos" />
              </div>

              <template x-if="Object.keys(product).length">
                <template x-if="!update">
                  <x-wireui.button x-on:click="addProduct()" text="Agregar" />
                </template>
              </template>

              <div x-show="update">
                <x-wireui.button x-on:click="updateProduct()" text="Actualizar" />
                <x-wireui.button danger x-on:click="cancel()" text="Cancelar" />
              </div>
            </div>
          </div>
        </x-slot:footer>

      </x-wireui.card>

      <div>
        <div class="flex justify-end items-end my-2 space-x-4">
          <x-wireui.native-select wire:model.defer="payment_method_id" optionKeyValue="true"
            placeholder="Medio de pago" :options="$paymentMethods" />
          <x-wireui.native-select x-model="finance" name="finance" optionKeyValue="true"
            placeholder="Método de pago" :options="[0 => 'Contado', 1 => 'Crédito']" />
          <div x-show="finance == 1">
            <x-wireui.input label="Fecha de vencimiento" wire:model.defer="due_date" type="date" />
          </div>
        </div>
        <div class="flex flex-col items-end">
          <x-wireui.error for="payment_method_id" class="mr-2" />
          <x-wireui.error for="finance" class="mr-2" />
          <x-wireui.error for="due_date" class="mr-2" />
        </div>
      </div>

      <div wire:ignore class="overflow-x-auto mt-5 bg-white border shadow-sm scroll border-slate-200">
        <table class="w-full">
          <thead class="text-xs font-semibold uppercase bg-gray-100 border border-slate-200 text-slate-500">
            <tr>
              <th class="py-2 px-2 text-left">
                #
              </th>
              <th class="py-2 px-2 text-left">
                Referencia
              </th>
              <th class="py-2 px-2 text-left">
                Nombre
              </th>
              <th class="py-2 px-2 text-center">
                Cantidad
              </th>
              <th class="py-2 px-2 text-center">
                V. Unidad
              </th>
              <th class="py-2 px-2 text-center">
                Descuento
              </th>
              {{-- <th class="py-2 px-2 text-center">
                Impuesto(%)
              </th> --}}
              {{-- <th class="py-2 px-2 text-center">
                V. Impuesto
              </th> --}}
              <th class="py-2 px-2 text-center">
                Total
              </th>
              <th class="py-2 px-2 text-center">
                Acciones
              </th>
            </tr>
          </thead>
          <tbody class="text-sm divide-y divide-slate-200">
            <template x-for="(item, index) in products" :key="index">
              <tr>

                <td x-text="index+1" class="py-1 px-2 font-semibold text-blue-500"></td>

                <td x-text="item.reference" class="py-1 px-2 font-semibold text-blue-500"></td>

                <template x-if="Object.keys(item.presentation).length">
                  <td x-text="`${item.name} [${item.presentation.name}]`"
                    class="py-1 px-2 whitespace-nowrap text-slate-600"></td>
                </template>

                <template x-if="!Object.keys(item.presentation).length">
                  <td x-text="item.name" class="py-1 px-2 whitespace-nowrap text-slate-600"></td>
                </template>

                <td x-text="item.amount" class="py-1 px-2 font-semibold text-center whitespace-nowrap text-slate-600">
                </td>

                <td x-text="formatToCop(item.price)"
                  class="py-1 px-2 font-semibold text-center whitespace-nowrap text-slate-600"></td>

                <td x-text="formatToCop(item.discount)"
                  class="py-1 px-2 font-semibold text-center whitespace-nowrap text-slate-600"></td>

                <td x-text="formatToCop(item.total)"
                  class="py-1 px-2 font-semibold text-center whitespace-nowrap text-slate-600"></td>

                <td class="py-1 px-4 space-x-1 text-center">
                  <x-buttons.edit x-on:click="editProduct(index)" />
                  <x-buttons.delete x-on:click="deleteProduct(index)" />
                </td>

              </tr>
            </template>

            <template x-if="!Object.keys(products).length">
              <x-commons.table-empty text="No se encontraron productos agregados" />
            </template>

          <tbody>
        </table>
      </div>

      <x-wireui.card title="Observaciones">
        <x-wireui.textarea wire:model.defer="observation"  />
      </x-wireui.card>

      <div class="pr-4 mt-4 space-y-1 text-right">
        <div class="font-bold text-tiny">
          <span>VALOR BRUTO</span>
          <span class="inline-block w-28 text-right" x-text="formatToCop(subtotalT)"></span>
        </div>

        <div class="font-bold text-tiny">
          <span>DESCUENTO</span>
          <span class="inline-block w-28 text-right" x-text="formatToCop(discountT)"></span>
        </div>

        <div>
          <template x-for="(item, index) in taxRatesByTributeT">
            <div class="font-bold text-tiny">
              <span x-text="item.name"></span>
              <span class="inline-block w-28 text-right" x-text="formatToCop(item.value)"></span>
            </div>
          </template>
        </div>

        <div class="font-bold text-tiny">
          <span>TOTAL IMPUESTOS</span>
          <span class="inline-block w-28 text-right" x-text="formatToCop(totalTaxRatesT)"></span>
        </div>

        <div class="font-bold text-tiny">
          <span>TOTAL</span>
          <span class="inline-block w-28 text-right" x-text="formatToCop(totalT)"></span>
        </div>
      </div>

      <div class="flex justify-end items-center pr-4 mt-4">

        @if ($errors->count())
          <span class="mr-2 text-sm text-red-600">La factura contiene errores de validación</span>
        @endif

        <x-wireui.button x-on:click="openChange()" wire:target="store,openChange" text="Guardar" load
          textLoad="Guardando" />
      </div>

      <x-wireui.errors />

    </section>
  </div>

  @include('pdfs.ticket-bill')

  {{-- MODALS --}}
  @include('livewire.admin.bills.presentations')

  <livewire:admin.customers.create>
  <livewire:admin.products.modal-alpine>
  <x-loads.panel-fixed text="Enviando factura" class="no-print z-[999]" wire:loading wire:target='store' />

  @include('livewire.admin.bills.change')

</div>
