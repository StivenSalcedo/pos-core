<div>
    <x-wireui.modal wire:model.defer="openChange" x-on:open-change.window="show=true" max-width="sm">

        <div x-data="mainChange()">

            <x-wireui.card title="RECIBIR EFECTIVO">

                <div class="space-y-6  bg-white p-5">

                    <div>
                        <label class="block font-semibold mb-1">TOTAL</label>
                        <input type="text" readonly x-bind:value="formatToCop(total)" class="placeholder-slate-400 border border-slate-300 focus:ring-cyan-400 focus:border-cyan-400 block w-full text-lg font-semibold py-1 sm:py-2 rounded-md transition ease-in-out duration-100 focus:outline-none read-only:bg-slate-50 shadow-sm text-right">
                    </div>

                    <div>
                        <label class="block font-semibold mb-1">EFECTIVO RECIBIDO</label>
                        <input inputmode="numeric" onkeypress='return onlyNumbers(event)' type="text" x-ref="cash" x-model="cash" x-on:keyup.enter="store()" class="placeholder-slate-400 border border-slate-300 focus:ring-cyan-400 focus:border-cyan-400 block w-full text-lg font-semibold py-1 sm:py-2 rounded-md transition ease-in-out duration-100 focus:outline-none read-only:bg-slate-50 shadow-sm text-right">
                        <div x-show="alert" class="text-red-500 text-sm inline-flex items-center mr-2">
                            <span x-text="alert"></span>
                        </div>
                    </div>

                    <div class="flex justify-between border-b-4">
                        <label class="block font-bold mb-1 text-xl">CAMBIO</label>
                        <span x-text="formatToCop(cambio)" class="font-bold text-2xl"></span>
                    </div>
                </div>

                <x-slot:footer>
                    <div class="text-right">
                        <x-wireui.button secondary x-on:click="show=false" text="Cancelar" />
                        <x-wireui.button x-on:click="store()" text="Registrar" />
                    </div>
                </x-slot:footer>

        </div>

        </x-wireui.card>


    </x-wireui.modal>
</div>

@push('js')
    <script>
        function mainChange(){

            return {

                total   :0,
                cash    : '',
                alert   :'',

                init(){

                    window.addEventListener('open-change', event => {
                        this.total = event.detail;
                        this.$nextTick(() => {
                            this.$refs.cash.focus() ;
                            this.cash = '';
                        });

                    });

                },

                formatToCop(value){
                    return numberFormat.format(value)
                },

                get cambio(){

                    this.alert = '';
                    let cash = parseInt(this.cash);

                    if (!Number.isInteger(cash)  || cash < 1 ) {
                        return -this.total;
                    }

                    return cash - this.total;

                },

                store(){

                    let cash = parseInt(this.cash);

                    if (!Number.isInteger(cash)  || cash < 1 ) {
                        this.$refs.cash.focus();
                        return this.alert = 'Falta ' + this.formatToCop(this.cambio);
                    }

                    if (this.cambio < 0) {
                        this.$refs.cash.focus();
                        return this.alert = 'Falta ' + this.formatToCop(this.cambio);
                    }

                    this.show=false;

                    this.$dispatch('store', this.cash)

                }

            }

        }
    </script>
@endpush
