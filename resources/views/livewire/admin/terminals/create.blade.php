<div>
    <x-wireui.modal wire:model.defer="openCreate" max-width="3xl" >
        <x-wireui.card title="Nueva terminal">
            <x-wireui.errors />
            <div class="space-y-6">
                <x-wireui.input label="Nombre" wire:model.defer="name" />
                @if ($isApiFactusEnabled)
                  <x-wireui.native-select label="Rango de númeración" wire:model.defer="factus_numbering_range_id"  optionKeyValue="true" placeholder="Seleccionar" :options="$factusRanges"  class="w-full" />
                @else
                  <x-wireui.native-select label="Rango de númeración" wire:model.defer="numbering_range_id"  optionKeyValue="true" placeholder="Seleccionar" :options="$ranges"  class="w-full" />
                @endif
            </div>

            <div x-data="mainTerminalsCreate()" class="mt-6">
                <x-wireui.label label="Selecciona los usuarios que usaran esta terminal" />
                <ul class="mt-3 pl-4">
                    <template x-for="(item, index) in users ">
                        <ul x-on:click="selected(index)" class="hover:font-semibold cursor-pointer">
                            <i class="ico " :class="exists(index) ? 'text-blue-600 icon-check-square' : 'icon-square' "></i>
                            <span x-text="item" :class="exists(index) ? 'text-blue-600' : '' " :key="index"></span>
                        </ul>
                    </template>
                </ul>
            </div>

            <x-slot:footer>
                <div class="text-right">
                    <x-wireui.button secondary x-on:click="show=false" text="Cancelar" />
                    <x-wireui.button wire:click="store" text="Guardar" load textLoad="Guardando" />
                </div>
            </x-slot:footer>
        </x-wireui.card>
    </x-wireui.modal>
</div>

@push('js')
    <script>
        function mainTerminalsCreate(){
            return {
                users: [],
                usersSelected: @entangle('usersSelected').defer,

                init(){
                    Livewire.on('refresh', users => {
                        this.users= users;
                    });
                },

                selected(id){
                    if(this.exists(id)){
                        this.usersSelected = this.usersSelected.filter((value) => value != id );
                    }else{
                        this.usersSelected.push(id);
                    }
                },

                exists(id){
                    return this.usersSelected.some(item => item == id)
                }
            }
        }
    </script>
@endpush
