<div x-data="alpineSearchPercent()" x-on:click.away="focus=false" class="w-full relative">
    
            <input 
                x-show="!itemSelected.text"
                onkeypress='return onlyNumbers(event)' 
                type="text" 
                class="h-5 w-full border-none focus:outline-none focus:border-transparent px-0 focus:ring-0 text-center text-sm py-0"
                id="searchPercent"
                x-ref="search"
                x-model="search"
                x-on:focus="focus=true" 
                x-on:keyup.escape="$refs.search.blur(); focus=false"
                x-on:keyup.down="nextItem()"
                x-on:keyup.up="previewItem()"
                x-on:keyup.enter="selectItem()"
                ::class="focus ? 'w-10' : 'w-10'"
                autocomplete="off"
            >

            <div 
                x-show="itemSelected.text"
                class="relative"
            >
                <h1 x-text="itemSelected.text" class="w-full text-center text-sm"></h1>
                <a x-on:click="clearPercent()" class="absolute inset-y-0 flex items-center right-0 cursor-pointer hover:font-bold text-3xl text-blue-700">&times;</a>
            </div>
        
    <div 
        x-show="focus"
        class="absolute w-full">
        <ul x-ref="contentItems" class="bg-white text-sm border shadow max-h-40 overflow-y-auto py-1" >
            <template x-for="(item, index) in filteredItems">
                <ul 
                    :id="index" 
                    x-html="`<span class='font-semibold inline-block'>${item.text}</span>`" 
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
        function alpineSearchPercent(){
            return {
                focus           :false,
                current         :-1,
                search          :'',
                itemSelected    :{},
                items           :[
                    {
                        value: 0.01,
                        text: '1%',
                    },
                    {
                        value: 0.02,
                        text: '2%',
                    },
                    {
                        value: 0.03,
                        text: '3%',
                    },
                    {
                        value: 0.04,
                        text: '4%',
                    },
                    {
                        value: 0.05,
                        text: '5%',
                    },
                    {
                        value: 0.06,
                        text: '6%',
                    },
                    {
                        value: 0.07,
                        text: '7%',
                    },
                    {
                        value: 0.08,
                        text: '8%',
                    },
                    {
                        value: 0.09,
                        text: '9%',
                    },
                    {
                        value: 0.10,
                        text: '10%',
                    },
                    {
                        value: 0.11,
                        text: '11%',
                    },
                    {
                        value: 0.12,
                        text: '12%',
                    },
                    {
                        value: 0.13,
                        text: '13%',
                    },
                    {
                        value: 0.14,
                        text: '14%',
                    },
                    {
                        value: 0.15,
                        text: '15%',
                    },
                    {
                        value: 0.16,
                        text: '16%',
                    },
                    {
                        value: 0.17,
                        text: '17%',
                    },
                    {
                        value: 0.18,
                        text: '18%',
                    },
                    {
                        value: 0.19,
                        text: '19%',
                    },
                    {
                        value: 0.20,
                        text: '20%',
                    },
                    {
                        value: 0.21,
                        text: '21%',
                    },
                    {
                        value: 0.22,
                        text: '22%',
                    },
                    {
                        value: 0.23,
                        text: '23%',
                    },
                    {
                        value: 0.24,
                        text: '24%',
                    },
                    {
                        value: 0.25,
                        text: '25%',
                    },
                    {
                        value: 0.26,
                        text: '26%',
                    },
                    {
                        value: 0.27,
                        text: '27%',
                    },
                    {
                        value: 0.28,
                        text: '28%',
                    },
                    {
                        value: 0.29,
                        text: '29%',
                    },
                    {
                        value: 0.30,
                        text: '30%',
                    },
                    {
                        value: 0.31,
                        text: '31%',
                    },
                    {
                        value: 0.32,
                        text: '32%',
                    },
                    {
                        value: 0.33,
                        text: '33%',
                    },
                    {
                        value: 0.34,
                        text: '34%',
                    },
                    {
                        value: 0.35,
                        text: '35%',
                    },
                    {
                        value: 0.36,
                        text: '36%',
                    },
                    {
                        value: 0.37,
                        text: '37%',
                    },
                    {
                        value: 0.38,
                        text: '38%',
                    },
                    {
                        value: 0.39,
                        text: '39%',
                    },
                    {
                        value: 0.40,
                        text: '40%',
                    },
                    {
                        value: 0.41,
                        text: '41%',
                    },
                    {
                        value: 0.42,
                        text: '42%',
                    },
                    {
                        value: 0.43,
                        text: '43%',
                    },
                    {
                        value: 0.44,
                        text: '44%',
                    },
                    {
                        value: 0.45,
                        text: '45%',
                    },
                    {
                        value: 0.46,
                        text: '46%',
                    },
                    {
                        value: 0.47,
                        text: '47%',
                    },
                    {
                        value: 0.48,
                        text: '48%',
                    },
                    {
                        value: 0.49,
                        text: '49%',
                    },
                    {
                        value: 0.50,
                        text: '50%',
                    },
                    {
                        value: 0.51,
                        text: '51%',
                    },
                    {
                        value: 0.52,
                        text: '52%',
                    },
                    {
                        value: 0.53,
                        text: '53%',
                    },
                    {
                        value: 0.54,
                        text: '54%',
                    },
                    {
                        value: 0.55,
                        text: '55%',
                    },
                    {
                        value: 0.56,
                        text: '56%',
                    },
                    {
                        value: 0.57,
                        text: '57%',
                    },
                    {
                        value: 0.58,
                        text: '58%',
                    },
                    {
                        value: 0.59,
                        text: '59%',
                    },
                    {
                        value: 0.60,
                        text: '60%',
                    },
                    {
                        value: 0.61,
                        text: '61%',
                    },
                    {
                        value: 0.62,
                        text: '62%',
                    },
                    {
                        value: 0.63,
                        text: '63%',
                    },
                    {
                        value: 0.64,
                        text: '64%',
                    },
                    {
                        value: 0.65,
                        text: '65%',
                    },
                    {
                        value: 0.66,
                        text: '66%',
                    },
                    {
                        value: 0.67,
                        text: '67%',
                    },
                    {
                        value: 0.68,
                        text: '68%',
                    },
                    {
                        value: 0.69,
                        text: '69%',
                    },
                    {
                        value: 0.70,
                        text: '70%',
                    },
                    {
                        value: 0.71,
                        text: '71%',
                    },
                    {
                        value: 0.72,
                        text: '72%',
                    },
                    {
                        value: 0.73,
                        text: '73%',
                    },
                    {
                        value: 0.74,
                        text: '74%',
                    },
                    {
                        value: 0.75,
                        text: '75%',
                    },
                    {
                        value: 0.76,
                        text: '76%',
                    },
                    {
                        value: 0.77,
                        text: '77%',
                    },
                    {
                        value: 0.78,
                        text: '78%',
                    },
                    {
                        value: 0.79,
                        text: '79%',
                    },
                    {
                        value: 0.80,
                        text: '80%',
                    },
                    {
                        value: 0.81,
                        text: '81%',
                    },
                    {
                        value: 0.82,
                        text: '82%',
                    },
                    {
                        value: 0.83,
                        text: '83%',
                    },
                    {
                        value: 0.84,
                        text: '84%',
                    },
                    {
                        value: 0.85,
                        text: '85%',
                    },
                    {
                        value: 0.86,
                        text: '86%',
                    },
                    {
                        value: 0.87,
                        text: '87%',
                    },
                    {
                        value: 0.88,
                        text: '88%',
                    },
                    {
                        value: 0.89,
                        text: '89%',
                    },
                    {
                        value: 0.90,
                        text: '90%',
                    },
                    {
                        value: 0.91,
                        text: '91%',
                    },
                    {
                        value: 0.92,
                        text: '92%',
                    },
                    {
                        value: 0.93,
                        text: '93%',
                    },
                    {
                        value: 0.94,
                        text: '94%',
                    },
                    {
                        value: 0.95,
                        text: '95%',
                    },
                    {
                        value: 0.96,
                        text: '96%',
                    },
                    {
                        value: 0.97,
                        text: '97%',
                    },
                    {
                        value: 0.98,
                        text: '98%',
                    },
                    {
                        value: 0.99,
                        text: '99%',
                    },
                    {
                        value: 0.100,
                        text: '100%',
                    },
                ],
                results         :[],

                init(){

                    this.$watch('focus', value => {
                        this.current = -1
                        this.$refs.contentItems.scrollTop = 0;
                    });

                    this.$refs.search.addEventListener('keydown', e => {
                        if(e.keyCode === 9){
                            this.focus=false;
                            this.search='';
                        }
                    });

                    window.addEventListener('reset-percent', event => {
                        this.itemSelected = {};
                    });
                },

                get filteredItems(){
                    this.current = -1;
                    this.$refs.contentItems.scrollTop = 0;

                    search = this.search.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

                    if (!search) return this.results = this.items;

                   
                    this.results = this.items.filter((element) => {
                        if(element.text.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").includes(search)) return true;
                    });
                    
                    return this.results;

                },

                getPresentations(product_id){
                    return this.presentations.filter((element) => {
                        return element['product_id'] === product_id;
                    });
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
                        this.setItem(item);
                    }
                },

                setItem(item){

                    document.getElementById('searchPercent').value='';
                    this.$dispatch('set-percent', item.value)

                    this.$nextTick(() => {
                        this.search = '';
                        this.focus = false;
                    });

                    this.itemSelected = item;
                },

                clearPercent(){
                    document.getElementById('searchPercent').value='';
                    this.$dispatch('set-percent', 0);
                    this.itemSelected = {};
                    this.$nextTick(() => {
                        this.$refs.search.focus();
                    });

                },

                scrollItem(){
                    this.$refs.contentItems.scrollTop = 21 * this.current;
                }
            }
        }
    </script>
@endpush