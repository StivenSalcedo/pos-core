<div>
  <x-wireui.modal wire:model.defer="openModal" max-width="md">
    <x-wireui.card title="Enviar notificación por WhatsApp">
      
      <x-wireui.errors />

      <div class="space-y-4">
        <x-wireui.input label="Número de teléfono" 
                        placeholder="Ej: 573144502241"
                        wire:model.defer="phone" />

        <x-wireui.input label="Mensaje adicional (opcional)" 
                        placeholder="Texto que se agregará al mensaje"
                        wire:model.defer="message" />

        
      </div>

      <x-slot:footer>
        <div class="flex justify-end space-x-3">
          <x-wireui.button flat text="Cancelar" x-on:click="$wire.openModal=false" />
          <x-wireui.button primary text="Enviar" wire:click="sendMessage" spinner="sendMessage" />
        </div>
      </x-slot:footer>

    </x-wireui.card>
  </x-wireui.modal>
</div>
