<div>
  <x-commons.modal-alpine x-on:open-change.window="show=true">
    <div x-data="alpineChange()">

      <x-wireui.card title="RECIBIR EFECTIVO" cardClasses="max-w-sm mx-auto">

        <div class="space-y-6 bg-white p-5">


          <div>
            <label class="mb-1 block font-semibold">TOTAL</label>
            <input type="text" readonly x-bind:value="formatToCop(total + tip)"
              class="block w-full rounded-md border border-slate-300 py-1 text-right text-lg font-semibold placeholder-slate-400 shadow-sm transition duration-100 ease-in-out read-only:bg-slate-50 focus:border-cyan-400 focus:outline-none focus:ring-cyan-400 sm:py-2">
          </div>

          <div>
            <div class="flex justify-between">
              <label class="mb-1 block font-semibold">PROPINA</label>

              <div x-data="{
                  text: '',
                  textActive: 'con propina',
                  textInactive: 'sin propina',
              }" x-init="text = payTip === true ? textActive : textInactive;
              $watch('payTip', value => {
                  text = value ? textActive : textInactive
                  percentageTip = value ? $store.config.percentageTip : 0
                  calcTip(value ? $store.config.formatPercentageTip : 0.0)
              })" class="flex items-center">
                <span x-text="text" class="mr-1 text-xs font-semibold leading-none sm:text-sm"></span>
                <div x-on:click="payTip=!payTip;" :class="payTip == true ? 'bg-blue-600' : 'bg-gray-400'"
                  class="flex h-4 w-8 cursor-pointer items-center rounded-full px-1 transition-colors duration-300 sm:h-5 sm:w-12">
                  <span class="h-3 w-3 transform rounded-full bg-white duration-300 sm:h-4 sm:w-4"
                    :class="payTip === false ? 'translate-x-3.5 sm:translate-x-6' : ''"></span>
                </div>
              </div>

            </div>

            <div class="relative">
              <input type="text" :readonly="!payTip" x-model.number="tip" inputmode="numeric"
                onkeypress='return onlyNumbers(event)'
                class="block w-full rounded-md border border-slate-300 py-1 text-right text-lg font-semibold placeholder-slate-400 shadow-sm transition duration-100 ease-in-out read-only:bg-slate-50 focus:border-cyan-400 focus:outline-none focus:ring-cyan-400 sm:py-2">

              <button @click="if (payTip) showPercentage=true"
                class="absolute inset-y-0 top-0 flex w-16 items-center justify-between rounded-l-md bg-cyan-400 px-1.5 font-semibold text-white">
                <div class="inline">
                  <span x-text="percentageTip"></span>
                  <i class="ti ti-percentage -ml-1"></i>
                </div>
                <i class="ti ti-selector"></i>
              </button>

              <div x-show="showPercentage" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute z-50 mt-1 w-full rounded-md shadow-lg" style="display: none;"
                @click.away="showPercentage=false">

                <div class="rounded-md bg-white shadow ring-1 ring-black ring-opacity-5">
                  <ul class="grid grid-cols-5">
                    <template x-for="i in 20">
                      <li x-text="i + '%'" @click="calcTip(i /100); percentageTip=i; showPercentage=false"
                        class="ronded flex cursor-pointer items-center justify-center border py-1.5 text-sm hover:bg-slate-200 hover:font-semibold">
                      </li>
                    </template>
                  </ul>
                </div>

              </div>

            </div>
          </div>

          <div>
            <label class="mb-1 block font-semibold">MEDIO DE PAGO</label>
            <x-wireui.native-select label="" optionKeyValue x-model="paymentMethod" :options="$paymentMethods"
              class="min-w-full" x-on:change="calculateTotalCash()" />
          </div>

          <div>
            <label class="mb-1 block font-semibold">DINERO RECIBIDO</label>
            <input id="cash" inputmode="numeric" onkeypress='return onlyNumbers(event)' type="text" x-ref="cash"
              x-model="cash" x-on:keyup.enter="store()"
              class="block w-full rounded-md border border-slate-300 py-1 text-right text-lg font-semibold placeholder-slate-400 shadow-sm transition duration-100 ease-in-out read-only:bg-slate-50 focus:border-cyan-400 focus:outline-none focus:ring-cyan-400 sm:py-2">
            <div x-show="alert" class="mr-2 inline-flex items-center text-sm text-red-500">
              <span x-text="alert"></span>
            </div>
          </div>

          <div class="flex justify-between border-b-4">
            <label class="mb-1 block text-xl font-bold">CAMBIO</label>
            <span x-text="formatToCop(cambio)" class="text-2xl font-bold"></span>
          </div>
        </div>

        <x-slot:footer>
          <div class="text-right">
            <x-wireui.button secondary x-on:click="show=false" text="Cancelar" />
            <x-wireui.button x-on:click="store()" text="Registrar" />
          </div>
        </x-slot:footer>

      </x-wireui.card>

    </div>

  </x-commons.modal-alpine>
</div>
