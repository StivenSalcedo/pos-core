<div x-data="alpineSearchProduct()" class="fixed top-14  right-0 inset-y-0 bg-white border shadow-md z-30 transition-[width]" :class="show ? 'w-64' : 'w-0' ">

    <button x-on:click="show=!show" class="absolute bg-slate-900 bg-opacity-80 text-white w-8 h-8 transform -translate-x-full rounded-l">
        <i class="ico" :class="show ? 'icon-arrow-r' : 'icon-arrow-l' "></i>
    </button>

    <div x-show="show"
        x-on:click.away="show=false"
        class="relative h-full pt-4 px-2 w-64"
        x-transition:enter="transition transform ease-out duration-300"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full">

        <div class="relative " x-on:click.away="focus=false">
            <div class="flex flex-col items-end">

                <div class="flex justify-end mb-1">
                    <x-buttons.switch wire:model="useBarcode" active="Pistola" inactive="Manual"/>
                </div>

                <x-commons.search 
                    id="searchProduct"
                    placeholder="Buscar producto" 
                    x-ref="search"
                    x-model="search"
                    x-on:focus="focus=true" 
                    x-on:keyup.escape="$refs.search.blur(); focus=false"
                    x-on:keyup.down="nextItem()"
                    x-on:keyup.up="previewItem()"
                    x-on:keyup.enter="selectItem()"
                    class="w-60"
                    autocomplete="off"
                />

            </div>
            <div 
                x-show="focus"
                class="absolute w-full mr-2">
                <ul x-ref="contentItems" class="bg-white text-xs border shadow max-h-40 overflow-y-auto py-1 divide-y" >
                    <template x-for="(item, index) in filteredItems">
                        <li 
                            :id="index" 
                            x-on:click="addItem(item)"
                            class="pl-6 pr-2 hover:bg-slate-100 cursor-pointer py-0.5" 
                            :class="index === current ? 'bg-slate-100' : ''" 
                            ::key="index">
                        <div :class="itemsSelected.some(element=> element.id === item.id) ? 'text-blue-600' : '' ">
                                <span class="block whitespace-nowrap truncate font-semibold leading-3" x-text="item.reference"></span>
                                <span class="block whitespace-nowrap truncate leading-3" x-text="item.name"></span>
                            </div>
                        </li>
                    </template>
                </ul>
            </div>
        </div>

        <div class="mt-4">
            <h1 class="font-bold">Productos filtrados</h1>
            <ul>
                <template x-for="(item, index) in itemsSelected">
                    <li class="text-xs flex items-center py-1">

                        <span class="text-2xl font-semibold mr-1 cursor-pointer leading-3" x-on:click="removeItem(item)">&times;</span>

                        <div>
                            <span class="block whitespace-nowrap truncate font-semibold leading-3" x-text="item.reference"></span>
                            <span class="block whitespace-nowrap truncate leading-3" x-text="item.name"></span>
                        </div>

                    </li>
                </template>
            </ul>
        </div>
    </div>

</div>


@push('js')
    <script>
        function alpineSearchProduct(){

            return {
                show            :false,
                focus           :false,
                current         :-1,
                search          :'',
                items           :[],
                itemsSelected   :@entangle('productsSelected'),
                results         :[],
                useBarcode      :@entangle('useBarcode'),

                init(){

                    this.$watch('focus', value => {
                        this.current = -1
                        this.$refs.contentItems.scrollTop = 0;
                    });

                    this.items = this.$wire.get('productsArray');

                    this.$refs.search.addEventListener('keydown', e => {
                        if(e.keyCode === 9){
                            this.focus=false;
                            this.search='';
                        }
                    });
                },

                get filteredItems(){
                    this.current = -1;
                    this.$refs.contentItems.scrollTop = 0;

                    search = this.search.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

                    if (!search) return this.results = this.items.slice(0, 10);

                    if (!this.useBarcode && search) {
                        this.results = this.items.filter((element) => {
                            return element.reference.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "") === search;                            
                        });
                        if(this.results.length){
                            this.addItem(this.results[0]);
                        }
                    }else{
                        this.results = this.items.filter((element) => {
                            if(element.reference.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").includes(search)) return true;
                            return element.name.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").includes(search);
                        });
                    }
                    
                    return this.results.slice(0, 10);

                },

                nextItem(){
                    if (this.current < (this.results.length - 1)) {
                        this.current ++;
                        this.scrollItem();                     
                    }
                },

                previewItem(){
                    if (this.current > 0) {
                        this.current --;
                        this.scrollItem();
                    }
                },

                selectItem(){
                    if(this.current in this.results){
                        item = this.results[this.current];
                        this.addItem(item);
                    }
                },

                addItem(item){

                    if (this.itemsSelected.some(element=> element.id === item.id)) {
                        this.removeItem(item)
                    }else{
                        this.itemsSelected.push(item);
                    }
                    
                },

                removeItem(item){
                    this.itemsSelected = this.itemsSelected.filter(value => value.id !== item.id);
                },

                scrollItem(){
                    this.$refs.contentItems.scrollTop = 21 * this.current;
                }
            }
        }
    </script>
@endpush