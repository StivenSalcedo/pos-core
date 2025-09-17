<div x-data="presentationsAlpine()">
    <div x-show="showPresentations" 
        class="fixed inset-0 bg-slate-200 bg-opacity-60 flex items-center justify-center z-30">
        
        <div class="bg-white pt-2 border shadow">
            <h1 class="mx-6 text-sm font-bold">PRESENTACIONES</h1>
            <hr>
            
            <ul class="bg-white border rounded mt-3 divide-y mx-6 mb-6">
                <template x-for="(item, index) in presentations">
                    <li 
                    class="px-2 py-1 text-center cursor-pointer" 
                    x-on:click="setPresentation(item)" 
                    x-text="item.name"

                    :class="index === current ? 'bg-slate-100' : '' " 
                    ></li>
                </template>
            </ul>
        </div>
    </div>

</div>

@push('js')
    <script>
        function presentationsAlpine(){

            return {
                showPresentations   :false,
                current             : -1,
                body                :null,
                

                init(){

                    document.onkeydown = evt => { 

                        if(this.showPresentations){
                            if(evt.keyCode == 40 && this.current < (this.presentations.length - 1)) this.current ++;
                            if(evt.keyCode == 38 && this.current > 0) this.current --;
                            if(evt.keyCode == 13 && this.current in this.presentations){
                                this.setPresentation(this.presentations[this.current])
                            }
                        }
                    }

                    this.body =document.body;
                    window.addEventListener('set-product', event => {

                        this.presentations = event.detail.presentations;
                        if (this.presentations.length) {
                            this.showPresentations=true;
                            this.current=0;
                            this.body.classList.add('overflow-hidden');
                        }else{
                            this.presentationName = 'No Aplica';
                        }

                    });
                },
                
                setPresentation(item){
                    this.presentation = item;
                    this.presentation_id = item.id;
                    this.presentationName = item.name;
                    this.body.classList.remove('overflow-hidden');
                    this.showPresentations = false;
                    this.$refs.amount.focus();
                },
            }
        }
    </script>
@endpush