@push('html')
<div x-data="alpineTicketCommandBill()" class="print">
  <div x-show="show" class="absolute -z-40 font-roboto top-0 left-0 bg-white py-10 !text-[12px] px-4 w-full">

    <strong style="font-size: 60px; border: 2px solid; border-style: dashed; padding: 5px 10px;" x-text="bill.place"></strong>

    <hr class="border border-slate-400 my-3">
      {{-- Informacion de la factura --}}
      <div class="overflow-hidden">
          <ul class="leading-4 whitespace-nowrap">
              <li>
                  <span class="w-14 inline-block">Fecha</span>
                  :
                  <span class="font-medium" x-text="bill.format_created_at"></span>
              </li>

          </ul>
      </div>

      <hr class="border border-slate-400 my-3">

      {{-- Productos --}}
      <table class="w-full">
          <thead>
              <tr>
                  <th width="70%"  class="text-left font-medium">

                  </th>
                  <th width="10%">
                      Cant
                  </th>
              </tr>
          </thead>
          <tbody>
              <template x-for="item in products">
                  <tr class="border-b border-slate-200">
                      <td class="text-left">
                          <strong x-text="item.name" class="text-[14px]"></strong>
                          <br>
                          <span x-text="item.comment" class="text-xs"></span>
                      </td>
                      <td class="text-center text-lg">
                          <span x-text="item.amount"></span>
                      </td>
                  </tr>
              </template>
          </tbody>
      </table>
  </div>
</div>
@endpush
