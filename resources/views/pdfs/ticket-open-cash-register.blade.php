<div x-data="alpineTicketOpenCashRegister()" class="print">
  <div x-show="show" class="absolute left-0 top-0 -z-40 w-full bg-white px-4 py-10 font-roboto !text-[12px]">

    {{-- Informacion de la factura --}}
    <div class="overflow-hidden">
      <p class="text-sm font-bold text-center">Registro de apertura de caja</p>
      <ul class="mt-6 leading-4 whitespace-nowrap">
        <li>
          <span class="inline-block w-14">Fecha</span>
          :
          <span class="font-medium" x-text="datetime"></span>
        </li>
        <li class="">
          <span class="inline-block w-14">Cajero</span>
          :
          <span class="font-medium">{{ auth()->user()->name }}</span>
        </li>
      </ul>
    </div>

  </div>
</div>
