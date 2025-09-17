<div id="wire-orders"
  x-data="alpineOrders()"
  class="relative px-4 pt-2">

  <x-loads.panel-fixed id="load-panel"
    text="Cargando mesas..."
    class="no-print z-[999]"
    wire:loading />

  <div class="grid grid-cols-5 gap-4">

    <div class="flex cursor-pointer overflow-hidden rounded border bg-white">

      <div @click="loadOrder()"
        class="flex min-w-0 flex-1 flex-col items-center justify-center overflow-hidden px-2 py-4 duration-200 hover:scale-105">
        <i class="ti ti-receipt-2 text-5xl text-cyan-400"></i>
        <h1 class="font-semibold">Factura en caja</h1>
      </div>

    </div>
    <template x-for="(item, index) in orders"
      :key="index">
      <div class="flex cursor-pointer overflow-hidden rounded border bg-white">

        <div x-show="!item.is_available"
          class="z-20 grid grid-rows-3 divide-y">

          <button @click="$dispatch('open-modal-tables', {order: item, view: 'orders'})"
            class="w-10 rounded bg-cyan-400 text-white hover:bg-cyan-500"
            title="Cambiar mesa">
            <i class="ti ti-replace text-xl"></i>
          </button>

          <div class="m-1 h-8 w-8 rounded-full bg-green-400 flex items-center justify-center text-white" x-show="item.delivery_address">
            <i class="ti ti-motorbike text-xl"></i>
          </div>

          <button @click="printPreBill(item)"
            class="row-start-3 row-end-4 w-10 rounded bg-cyan-400 text-white hover:bg-cyan-500"
            title="Imprimir factura">
            <i class="ti ti-printer text-xl"></i>
          </button>

          <button @click="printPreBill(item, true)"
            class="row-start-3 ml-1 row-end-4 w-10 rounded bg-cyan-400 text-white hover:bg-cyan-500"
            title="Imprimir Comanda">
            <i class="ico icon-payment text-xl"></i>
          </button>

        </div>

        <div @click="loadOrder(item)"
          class="flex min-w-0 flex-1 flex-col items-center justify-start overflow-hidden px-2 py-4 duration-200 hover:scale-105">
          <h1 x-text="item.name"
            class="text-sm"></h1>
          <img src="{{ Storage::url('images/system/table.png') }}"
            class="h-14 object-cover object-center">
          <span x-text="formatToCop(item.total)"
            class="mt-1 block h-4 text-sm font-bold leading-none text-green-500"></span>
          <span x-text="item.customer.names"
            class="block h-4 w-full truncate text-center text-sm font-semibold leading-none"></span>
        </div>

        <div x-show="!item.is_available"
          class="z-20 grid divide-y">

          <button @click="showCustomers(item)"
            class="w-10 bg-cyan-400 text-white hover:bg-cyan-500"
            title="Asignar cliente">
            <i class="ti ti-user text-2xl"></i>
          </button>

          <button @click="change(item, 'orders')"
            class="w-10 bg-cyan-400 text-white hover:bg-cyan-500"
            title="Crear factura">
            <i class="ti ti-receipt-2 text-2xl"></i>
          </button>

          <button @click="deleteOrder(item)"
            class="w-10 bg-cyan-400 text-white hover:bg-cyan-500"
            title="Eliminar">
            <i class="ti ti-trash text-2xl"></i>
          </button>

        </div>

      </div>
    </template>
  </div>

</div>
