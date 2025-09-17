<div wire:ignore x-data="alpineSearchProduct()" x-on:click.away="focus=false" class="min-w-min max-w-min relative z-10">

  <div class="">

    @if ($showBarcode)
      <div class="flex justify-end mb-1">
        <x-alpine.switch :model="$useBarcode" nameEvent='set-use-barcode' active='Pistola' inactive='Manual' />
      </div>
    @endif

    <x-commons.search id="searchProduct" placeholder="Buscar producto" x-ref="search" x-model.debounce.200ms="search"
      x-on:focus="focus=true" x-on:keyup.escape="$refs.search.blur(); focus=false" x-on:keyup.down="nextItem()"
      x-on:keyup.up="previewItem()" x-on:keyup.enter="selectItem()" class="transition-[width] duration-300"
      ::class="focus ? 'w-96' : 'w-48'" autocomplete="off" />
  </div>
  <div x-show="focus" class="absolute w-full">
    <ul x-ref="contentItems" class="bg-white text-sm border shadow max-h-40 overflow-y-auto py-1">
      <template x-for="(item, index) in filteredItems">
        <ul :id="index"
          x-html="`<span class='font-semibold inline-block'>${item.reference}</span> - ${item.name}`"
          x-on:click="setItem(item)" class="pl-6 pr-2 hover:bg-slate-100 cursor-pointer"
          :class="index === current ? 'bg-slate-100' : ''" ::key="index"></ul>
      </template>
    </ul>
  </div>
</div>

@push('js')
  <script>
    function alpineSearchProduct() {
      return {
        focus: false,
        current: -1,
        search: '',
        items: @entangle('productsArray').defer,
        presentations: [],
        results: [],
        useBarcode: {{ $useBarcode }},
        init() {
          window.addEventListener('set-use-barcode', ({detail}) => {
            this.useBarcode = detail
          })

          this.$watch('focus', value => {
            this.current = -1
            this.$refs.contentItems.scrollTop = 0;
          });

          this.presentations = this.$wire.get('presentations');

          this.$refs.search.addEventListener('keydown', e => {
            if (e.keyCode === 9) {
              this.focus = false;
              this.search = '';
            }
          });
        },

        get filteredItems() {

          this.current = -1;
          this.$refs.contentItems.scrollTop = 0;

          search = this.search.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

          if (!search) return this.results = this.items.slice(0, 100);

          if (!this.useBarcode && search) {
            this.results = this.items.filter((element) => {
              return element.barcode.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "") === search;
            });
            if (this.results.length) {
              this.setItem(this.results[0]);
            }
          } else {
            this.results = this.items.filter((element) => {
              if (element.reference.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").includes(search))
                return true;
              return element.name.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").includes(search);
            });
          }

          return this.results.slice(0, 100);

        },

        getPresentations(product_id) {
          return this.presentations.filter((element) => {
            return element['product_id'] === product_id;
          });
        },

        nextItem() {
          if (this.current < (this.results.length - 1)) {
            this.current++;
            this.scrollItem();
          }
        },

        previewItem() {
          if (this.current > 0) {
            this.current--;
            this.scrollItem();
          }
        },

        selectItem() {
          if (this.current in this.results) {
            item = this.results[this.current];
            this.setItem(item);
          }
        },

        setItem(item) {

          data = {
            'product': item,
            'presentations': this.getPresentations(item.id)
          };

          document.getElementById('searchProduct').value = '';
          this.$dispatch('set-product', data)

          this.$nextTick(() => {
            this.search = '';
            this.focus = false;
          });
        },

        scrollItem() {
          this.$refs.contentItems.scrollTop = 21 * this.current;
        }
      }
    }
  </script>
@endpush
