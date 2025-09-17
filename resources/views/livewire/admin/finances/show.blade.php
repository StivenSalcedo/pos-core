<div>
  <x-wireui.modal wire:model.defer="openShow"
    max-width="3xl">

    <x-wireui.card title="Pagos realizados">

      @if ($openShow)

        <div class="flex items-center justify-end space-x-2">
          <x-wireui.label label="Estado:" />

          <div>
            <x-commons.status :status="$finance->status"
              active="Pagado"
              inactive="Pendiente" />
          </div>

        </div>

        <div class="grid grid-cols-2 gap-3">

          <x-wireui.input label="Fecha"
            value="{{ $finance->created_at->format('d-m-Y') }}"
            readonly />

          <x-wireui.input label="N° Factura"
            value="{{ $finance->bill->number }}"
            readonly />

          <x-wireui.input label="Cliente"
            value="{{ $finance->customer->names }}"
            readonly />

          <x-wireui.input label="Teléfono"
            value="{{ $finance->customer->phone }}"
            readonly />

        </div>

        @if ($finance->status === '1')
          <x-wireui.card title="Agregar abono"
            cardClasses="mt-4 border">

            <div class="grid grid-cols-4 gap-3">

              <x-wireui.native-select label="Medio de pago"
                wire:model.defer="payment_method_id"
                optionKeyValue="true"
                placeholder="Seleccionar"
                :options="$paymentMethods"
                class="w-full" />

              <x-wireui.input label="Agregar valor"
                wire:model.debounce.500ms="value"
                onlyNumbers />

              <div>
                <x-wireui.label label="Saldo pendiente"
                  class="mb-1" />

                <div class="relative">
                  <x-wireui.input value="{{ $this->pending }}"
                    readonly />

                  <div wire:loading.flex
                    wire:target="value"
                    class="absolute inset-0 flex items-center justify-center bg-gray-50 bg-opacity-70">Calculando...
                  </div>

                </div>
              </div>

              <div class="flex items-end">
                <x-wireui.button wire:click="store"
                  disabledTarget="value"
                  text="Agregar"
                  load
                  textLoad="Agregando..." />
              </div>

            </div>
            <x-wireui.error for="value" />
          </x-wireui.card>
        @endif

        <x-wireui.card title="Pagos realizados"
          cardClasses="mt-4 border">

          <div class="divide-y">
            <div class="grid grid-cols-4 text-sm">
              <span class="block text-center font-semibold">Fecha de pago</span>
              <span class="block text-center font-semibold">Medio de pago</span>
              <span class="block text-center font-semibold">Valor pagado</span>
              <span class="block text-center font-semibold">Acciones</span>
            </div>
            @forelse ($finance->details as $item)
              <div class="grid grid-cols-4 py-0.5 text-sm"
                wire:key="detail-finance{{ $item->id }}">
                <span class="block text-center">{{ $item->created_at->format('d-m-Y') }}</span>
                <span class="block text-center">{{ $item->paymentMethod->name }}</span>
                <span class="block text-center">@formatToCop($item->value)</span>
                <div class="text-center">
                  <x-buttons.icon icon="pdf"
                    wire:click="showTicket({{ $item->id }})" />
                  <x-buttons.delete wire:click="$emit('deletePayment', {{ $item->id }})" />
                </div>
              </div>
            @empty
              <div class="col-span-full py-2 text-center text-sm">
                No se encontraron pagos
              </div>
            @endforelse
          </div>

        </x-wireui.card>

      @endif

      <x-slot:footer>
        <div class="text-right">

          <x-wireui.button secondary
            x-on:click="show=false"
            text="Cerrar" />

        </div>
      </x-slot:footer>

    </x-wireui.card>
  </x-wireui.modal>

  @include('pdfs.ticket-finance-paid')

</div>

@push('js')
  <script>
    document.addEventListener('livewire:load', function() {

      Livewire.on('deletePayment', id => {
        Swal.fire({
          title: '¿Estas seguro?',
          text: '¿Quires eliminar este pago?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Sí, aceptar',
          cancelButtonText: 'Cancelar',
          showLoaderOnConfirm: true,
          preConfirm: function() {
            return @this.deletePayment(id);
          },
          allowOutsideClick: () => !Swal.isLoading()
        });
      });
    });
  </script>
@endpush
