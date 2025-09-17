<div x-data="authenticatedFactus()" x-init="openFactus" class="h-screen bg-slate-200">
  <div class="flex h-screen items-center justify-center">
    <button @click="location.reload()" class="flex flex-col items-center">
        <x-icons.factus class="h-14 w-14 text-indigo-800" title="Factus activo" />
        <span class="mt-2 font-semibold text-lg">
          Abrir Factus
        </span>
    </button>
  </div>
</div>

@push('js')
  <script>
    function authenticatedFactus() {
      return {
        async openFactus() {
          response = await this.getTokenFactus();

          if (response.token) {
            params = new URLSearchParams({
              token: response.token,
              redirect_url: response.redirect_url
            }).toString();

            window.open(`${response.domain}/external-authentication?${params}`, '_blank');
          } else {
            alert('Ha ocurrido un error al abrir Factus');
          }
        },

        async getTokenFactus() {
          credentials = await this.$wire.call('getTokenFactus');
          return credentials
        },
      }
    }
  </script>
@endpush
