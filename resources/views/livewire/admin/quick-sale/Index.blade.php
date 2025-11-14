<x-app-layout>

  <div x-data="{ toggleView: false, order: {} }"
    x-init="$watch('toggleView', value => { if (!value) order = {} })"
    @toggle-view.window="toggleView=$event.detail"
    @current-order.window="order=$event.detail"
    class="pb-10">

    <x-loads.panel-fixed id="load-panel"
      text="Cargando..."
      class="z-[999] hidden" />

    <livewire:admin.quick-sale.customers />

    <livewire:admin.quick-sale.change />

    @include('livewire.admin.quick-sale.modal-tables')

    @include('pdfs.ticket-bill')

    @include('pdfs.ticket-pre-bill')

    @include('pdfs.ticket-command-bill')

    <template x-if="Object.keys(order).length">
      <div
        class="sticky top-14 z-20 mb-2 flex w-full items-center justify-between space-x-4 border-b bg-white py-1 shadow">

        <button @click="$dispatch('verify-block-order')"
          class="rounded bg-sky-400 px-4 py-2 font-bold text-white hover:bg-sky-500">
          <i class="ico icon-arrow-l"></i>
          Mesas
        </button>

        <a @click="$dispatch('open-modal-tables', {order: order, view: 'order'})"
          class="cursor-pointer hover:text-sky-400 hover:underline">
          <i class="ti ti-replace"></i>
          <span x-text="order.name"
            class="font-semibold"></span>
        </a>

        <div class="flex space-x-1 pr-4">
          <span>Cliente:</span>
          <span x-text="order.customer.names"
            class="font-semibold"></span>
        </div>

      </div>
    </template>

    <div x-show="!toggleView">
      <livewire:admin.quick-sale.orders />
    </div>

    <div x-show="toggleView"
      class="flex space-x-4">

      <div class="w-full">
        <livewire:admin.quick-sale.products />
        @include('livewire.admin.quick-sale.presentations')
      </div>

      <div class="w-[40%] xl:w-[33%]">
        @include('livewire.admin.quick-sale.cart')
      </div>

    </div>
  </div>

</x-app-layout>
