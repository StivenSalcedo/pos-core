<div x-data="mainProducts()">

    <x-commons.modal-alpine @open-products.window="show = true" class="lg:hidden">

            <x-wireui.card title="Lista de productos" cardClasses="relative overflow-hidden" :close="true">

                <div class="flex py-2 space-x-1">
                    <input type="text" x-model="search" class="border-gray-300 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 focus:ring-opacity-50 rounded-md shadow-sm text-sm py-1 px-2 h-8 w-full">
                    <select x-model="filter" class="text-sm border-gray-300 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 focus:ring-opacity-50 rounded-md shadow-sm py-1">
                        <template x-for="item in filters">
                            <option :value="item.id" x-text="item.value"></option>
                        </template>
                    </select>
                </div>

                <div class="bg-white border border-slate-200 shadow-sm rounded-lg">
                    <table class="table-sm">
                        <thead >
                            <tr>
                                <th left>
                                    Referencia
                                </th>
                                <th left>
                                    Nombre
                                </th>
                                <th>
                                    Stock
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="item in filteredProducts" :key="item.id">
                                <tr x-on:click="$dispatch('set-product', item); show=false" >
                                    <td left x-text="item.reference" ></td>
                                    <td left x-text="item.name" ></td>
                                    <td x-text="item.stock" ></td>
                                </tr>
                            </template>
                        <tbody>
                    </table>
                </div>

                <x-loads.panel text="Cargando..." wire:loading />

            </x-wireui.card>

    </x-commons.modal-alpine>

    <x-wireui.card title="Lista de productos" cardClasses="relative overflow-hidden hidden lg:block">

        <div class="flex py-2 space-x-1">
            <input type="text" id="ivan"  x-model="search" class="border-gray-300 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 focus:ring-opacity-50 rounded-md shadow-sm text-sm py-1 px-2 h-8 w-full">
            <select x-model="filter" class="text-sm border-gray-300 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 focus:ring-opacity-50 rounded-md shadow-sm py-1">
                <template x-for="item in filters">
                    <option :value="item.id" x-text="item.value"></option>
                </template>
            </select>
        </div>

        <div class="bg-white border border-slate-200 shadow-sm rounded-lg">

            <table class="table-sm">
                <thead >
                    <tr>
                        <th left>
                            Referencia
                        </th>
                        <th left>
                            Nombre
                        </th>
                        <th>
                            Stock
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="item in filteredProducts" :key="item.id">
                        <tr x-on:click="$dispatch('set-product', getData(item))" >
                            <td left x-text="item.reference" ></td>
                            <td left x-text="item.name" ></td>
                            <td x-text="item.stock" ></td>
                        </tr>
                    </template>
                <tbody>
            </table>
        </div>

        <x-loads.panel text="Cargando..." wire:loading />

    </x-wireui.card>

</div>

@push('js')
    <script>

        function getFilters(){
                if(@js($barcode) == 1){
                    return [{'id': 'name', 'value': 'Nombre'}, {'id': 'reference', 'value': 'Referencia'}];
                }else{
                    return [{'id': 'reference', 'value': 'Referencia'}, {'id': 'name', 'value': 'Nombre'}];
                }
        }

        function mainProducts(){

            let ivan = document.getElementById("ivan");

            return {
                products: @entangle('products'),
                presentations: @entangle('presentations'),
                search: '',
                filters: getFilters(),
                filter: getFilters()[0].id,
                barcode: @js($barcode),

                get filteredProducts(){
                    results = this.resultsSearch();

                    if ( this.barcode != 1 && results.length === 1 && results[0].reference === this.search) {

                        this.$dispatch('add-product', results[0]);
                        this.search = '';
                        ivan.value = "";
                        results = this.resultsSearch();

                    }

                    return results.slice(0, 10);
                },

                resultsSearch(){
                    results = this.products.filter((element) => {
                        search = this.search.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

                        return element[this.filter].toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").includes(search);
                    });

                    return results.slice(0, 10);
                },

                getData(product){
                    data = {
                        'product': product,
                        'presentations': this.getPresentations(product.id)
                    };
                    return data;
                },

                getPresentations(product_id){
                    return this.presentations.filter((element) => {
                        return element['product_id'] === product_id;
                    });
                }
            }
        }
    </script>
@endpush




