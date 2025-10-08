<div class="grid grid-cols-2 gap-4">
  <x-wireui.input label="Fecha de ingreso" type="date" wire:model.defer="service.date_entry" />
  <x-wireui.input label="Fecha de vencimiento" type="date" wire:model.defer="service.date_due" />

  <x-wireui.native-select label="Cliente" :options="$customers" wire:model.defer="service.customer_id" optionKeyValue="true" />
  <x-wireui.native-select label="Responsable" :options="$responsibles" wire:model.defer="service.responsible_id" optionKeyValue="true" />

  <x-wireui.native-select label="Técnico asignado" :options="$technicians" wire:model.defer="service.tech_assigned_id" optionKeyValue="true" />
  <x-wireui.native-select label="Estado" :options="$states" wire:model.defer="service.state_id" optionKeyValue="true" />

  <x-wireui.native-select label="Tipo de equipo" :options="$equipmentTypes" wire:model.defer="service.equipment_type_id" optionKeyValue="true" />
  <x-wireui.native-select label="Marca" :options="$brands" wire:model.defer="service.brand_id" optionKeyValue="true" />

  <x-wireui.input label="Modelo" wire:model.defer="service.model" />
  <x-wireui.input label="Usuario" wire:model.defer="service.user_account" />
  <x-wireui.input label="Clave" wire:model.defer="service.password" />
  <x-wireui.input label="Accesorios" wire:model.defer="service.accessories" />

  <x-wireui.textarea label="Descripción del problema" wire:model.defer="service.description" />
  <x-wireui.textarea label="Diagnóstico" wire:model.defer="service.diagnosis" />
</div>

<div class="mt-6 text-right">
  <x-wireui.button primary wire:click="save" text="Guardar cambios" />
</div>
