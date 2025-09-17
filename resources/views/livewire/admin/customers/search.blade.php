<div x-data="alpineSearch()" x-on:click.away="focus=false" class="min-w-min max-w-min relative z-20 ">
    <div class="flex">

        <x-commons.search 
            placeholder="Buscar cliente" 
            x-ref="search"
            x-model="search"
            x-on:focus="focus=true" 
            x-on:keyup.escape="$refs.search.blur(); focus=false"
            x-on:keyup.down="nextItem()"
            x-on:keyup.up="previewItem();"
            x-on:keyup.enter="selectItem()"
            class="transition-[width] duration-300"
            ::class="focus ? 'w-96' : 'w-40'"
            autocomplete="off"
        />
        <button 
            title="Registrar cliente"
            x-on:click="$wire.emitTo('admin.customers.create', 'openCreate')"
            class="px-2 border rounded shadow ml-2">
            <i class="ico icon-add-user text-xl"></i>
        </button>
    </div>
    <div 
        x-show="focus"
        class="absolute w-full">
        <ul x-ref="contentItems" class="bg-white text-sm border shadow max-h-40 overflow-y-auto py-1" >
            <template x-for="(item, index) in filteredItems">
                <ul 
                    :id="index" 
                    x-html="`<span class='font-semibold inline-block'>${item.no_identification}</span> - ${item.names}`" 
                    x-on:click="setItem(item)"
                    class="pl-6 pr-2 hover:bg-slate-100 cursor-pointer" 
                    :class="index === current ? 'bg-slate-100' : '' " 
                    ::key="index"></ul>
            </template>
        </ul>
    </div>
</div>

@push('js')
    <script>
        function alpineSearch(){
            return {
                focus:false,
                current:0,
                search:'',
                items:[],
                results:[],

                init(){

                    this.$watch('focus', value => {
                        this.current = 0
                        this.$refs.contentItems.scrollTop = 0;
                    });

                    this.items = this.$wire.get('customers');

                    this.$nextTick(() => { this.$refs.search.focus(); })
                    
                    this.focus=true;

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

                    this.results = this.items.filter((element) => {
                        search = this.search.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

                        if(element.no_identification.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").includes(search)){
                            return true;
                        }

                        return element.names.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").includes(search);
                    });

                    return this.results;

                },

                nextItem(){
                    if (this.current < (this.results.length - 1)) {
                        this.current ++;
                        this.results[this.current]; 
                        this.scrollItem();                     
                    }
                },

                previewItem(){
                    if (this.current > 0) {
                        this.current --;
                        this.results[this.current];
                        this.scrollItem();
                    }
                },

                selectItem(){
                    if(this.current in this.results){
                        item = this.results[this.current];
                        this.setItem(item);
                    }
                },

                setItem(item){
                    this.$dispatch('set-customer', item)
                    this.focus = false;
                    
                    this.$nextTick(() => {
                        searchProduct = document.getElementById('searchProduct');
                        searchProduct.focus();
                        searchProduct.scrollIntoView({block: "center"});
                        this.search = '';
                    });
                },

                scrollItem(){
                    this.$refs.contentItems.scrollTop = 21 * this.current;
                }
            }
        }
    </script>
@endpush