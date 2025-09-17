<div class="space-y-6 container pt-8">

  <livewire:admin.company.settings />

  <livewire:admin.company.bill />

  @can('isEnabled', [App\Models\Module::class, 'ventas rapidas'])
    <livewire:admin.company.quick-sales />
  @endcan

  <x-wireui.card title="Configuraciones generales">
    <livewire:admin.company.cash-register>
  </x-wireui.card>

</div>
