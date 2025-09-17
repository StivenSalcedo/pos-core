<div>
  <x-wireui.modal wire:model.defer="openEdit" max-width="3xl">
    <x-wireui.card title="Actualizar terminal">

      <x-wireui.errors />

      <div class="space-y-6">
        <x-wireui.input label="Nombre" wire:model.defer="terminal.name" />
        @if ($isApiFactusEnabled)
        <x-wireui.native-select label="Rango de númeración" wire:model.defer="terminal.factus_numbering_range_id"
          optionKeyValue="true" placeholder="Seleccionar" :options="$factusRanges" class="w-full" />
        @else
        <x-wireui.native-select label="Rango de númeración" wire:model.defer="terminal.numbering_range_id"
          optionKeyValue="true" placeholder="Seleccionar" :options="$ranges" class="w-full" />
        @endif
        <x-buttons.switch wire:model.defer="terminal.status" active="Activada" inactive="Desactivada" />
      </div>

      <div x-data="mainTerminalsEdit()" class="mt-6">
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
          <x-wireui.button wire:click="update" text="Actualizar" load textLoad="Actualizando" />
        </div>
      </x-slot:footer>
    </x-wireui.card>
  </x-wireui.modal>
</div>

@push('js')
<script>
  function mainTerminalsEdit(){
            return {
                users: [],
                usersSelected: @entangle('usersSelected').defer,

                init(){
                    Livewire.on('refresh-edit', users => {
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
