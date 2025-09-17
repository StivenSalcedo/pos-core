<div x-data="mainCustomers()">

    <x-commons.modal-alpine @open-customers.window="show = true" class="lg:hidden">
        <x-wireui.card title="Lista de clientes" cardClasses="relative overflow-hidden" :close="true">

            <div class="flex py-2 space-x-1">
                <input type="text"  x-model="search" class="border-gray-300 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 focus:ring-opacity-50 rounded-md shadow-sm text-sm py-1 px-2 h-8 w-full">
                <select x-model="filter" class="text-sm border-gray-300 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 focus:ring-opacity-50 rounded-md shadow-sm py-1">
                    <template x-for="item in filters">
                        <option :value="item.id" x-text="item.value"></option>
                    </template>
                </select>
            </div>

            <div class="bg-white border border-slate-200 shadow-sm rounded-lg">

                <table class="table-sm">
                    <thead>
                        <tr>
                            <th left>
                                Cédula / NIT
                            </th>
                            <th left>
                                Nombre
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="item in filteredCustomers" :key="item.id">
                            <tr x-on:click="$dispatch('set-customer', item); show=false" >
                                <td left x-text="item.no_identification"></td>

                                <td left x-text="item.names" class="truncate"></td>
                            </tr>
                        </template>
                    <tbody>
                </table>
            </div>

        </x-wireui.card>
    </x-commons.modal-alpine>

    <x-wireui.card title="Lista de clientes" cardClasses="relative overflow-hidden hidden lg:block">

        <div class="flex py-2 space-x-1">
            <input type="text" x-model="search" class="border-gray-300 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 focus:ring-opacity-50 rounded-md shadow-sm text-sm py-1 px-2 w-full h-8 ">
            <select x-model="filter" class="text-sm border-gray-300 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 focus:ring-opacity-50 rounded-md shadow-sm py-1">
                <template x-for="item in filters">
                    <option :value="item.id" x-text="item.value"></option>
                </template>
            </select>
        </div>

        <div class="bg-white border border-slate-200 shadow-sm rounded-lg">

            <table class="table-sm">
                <thead>
                    <tr>
                        <th left>
                            Cédula / NIT
                        </th>
                        <th left>
                            Nombre
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="item in filteredCustomers" :key="item.id">
                        <tr x-on:click="$dispatch('set-customer', item)" >
                            <td left x-text="item.no_identification"></td>

                            <td left x-text="item.names" class="truncate"></td>
                        </tr>
                    </template>
                <tbody>
            </table>
        </div>

    </x-wireui.card>

</div>

@push('js')
    <script>
        function mainCustomers(){
            return {
                customers: @entangle('customers'),
                search: '',
                filters: [{'id': 'names', 'value': 'Nombre'}, {'id': 'no_identification', 'value': 'Identificación'}],
                filter: 'names',

                get filteredCustomers(){
                    results = this.customers.filter((element) => {
                        search = this.search.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

                        return element[this.filter].toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").includes(search);
                    });

                    return results.slice(0, 10);
                }
            }
        }
    </script>
@endpush




