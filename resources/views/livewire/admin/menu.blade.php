@php
  $links = [
      [
          'name' => 'Dashboard',
          'route' => route('admin.home'),
          'active' => request()->routeIs('admin.home'),
          'icon' => 'dashboard text-xl',
          'can' => 'dashboard',
      ],
      [
          'name' => 'Usuarios',
          'route' => route('admin.users.index'),
          'active' => request()->routeIs('admin.users.index'),
          'icon' => 'user-config text-xl',
          'can' => 'usuarios',
      ],
      [
          'name' => 'Clientes',
          'route' => route('admin.customers.index'),
          'active' => request()->routeIs('admin.customers.index'),
          'icon' => 'users text-xl',
          'can' => 'clientes',
      ],
      [
          'name' => 'Proveedores',
          'route' => route('admin.providers.index'),
          'active' => request()->routeIs('admin.providers.index'),
          'icon' => 'provider text-xl',
          'can' => 'proveedores',
      ],
      [
          'name' => 'Productos',
          'route' => route('admin.products.index'),
          'active' => request()->routeIs('admin.products.index'),
          'icon' => 'inventory text-xl',
          'can' => 'productos',
      ],
      [
          'name' => 'Facturas',
          'route' => route('admin.bills.index'),
          'active' => request()->routeIs('admin.bills.*'),
          'icon' => 'quote text-xl',
          'can' => 'facturas',
      ],
      [
          'name' => 'Servicios',
          'route' => route('admin.services.index'),
          'active' => request()->routeIs('admin.services.*'),
          'icon' => 'computer text-xl',
          'can' => 'servicios',
      ],
      [
          'name' => 'Ventas rápidas',
          'route' => route('admin.quick-sales.create'),
          'active' => request()->routeIs('admin.quick-sales.*'),
          'icon' => 'new-order text-xl',
          'can' => 'ventas rapidas',
      ],
      [
          'name' => 'Cierre de caja',
          'route' => route('admin.cash-closing.index'),
          'active' => request()->routeIs('admin.cash-closing.*'),
          'icon' => 'payment text-xl',
          'can' => 'cierre de caja',
      ],
      [
          'name' => 'Financiaciones',
          'route' => route('admin.finances.index'),
          'active' => request()->routeIs('admin.finances.*'),
          'icon' => 'collect text-xl',
          'can' => 'financiaciones',
      ],
      [
          'name' => 'Compras',
          'route' => route('admin.purchases.index'),
          'active' => request()->routeIs('admin.purchases.*'),
          'icon' => 'purchases text-xl',
          'can' => 'compras',
      ],
      [
          'name' => 'Empleados',
          'route' => route('admin.staff.index'),
          'active' => request()->routeIs('admin.staff.index'),
          'icon' => 'users text-xl',
          'can' => 'empleados',
      ],
      [
          'name' => 'Nomina',
          'route' => route('admin.payroll.index'),
          'active' => request()->routeIs('admin.payroll.index'),
          'icon' => 'money text-xl',
          'can' => 'nomina',
      ],
      [
          'name' => 'Egresos',
          'route' => route('admin.outputs.index'),
          'active' => request()->routeIs('admin.outputs.index'),
          'icon' => 'wallet text-xl',
          'can' => 'egresos',
      ],
      [
          'name' => 'Productos vendidos',
          'route' => route('admin.sold-products.index'),
          'active' => request()->routeIs('admin.sold-products.index'),
          'icon' => 'folder-download text-xl',
          'can' => 'productos vendidos',
      ],
      [
          'name' => 'Ventas diarias',
          'route' => route('admin.daily-sales.index'),
          'active' => request()->routeIs('admin.daily-sales.index'),
          'icon' => 'excel text-xl',
          'can' => 'reporte de ventas diarias',
      ],
  ];

@endphp
<div class="">

  <nav class="fixed inset-y-0 h-auto overflow-hidden" style="top: 50px; z-index: 30;">

    {{-- <div class="{{ request()->routeIs('admin.quick-sales.create') ? '' : 'w-52' }} flex h-full flex-col border bg-white pt-1 shadow shadow-gray-400"> --}}
    <div class="w-full md:w-52 flex h-full flex-col border bg-white pt-1 shadow shadow-gray-400">

      {{-- links menu --}}
      <div class="overflow-hidden overflow-y-auto pb-3 pt-2">
        <ul class="space-y-1.5 px-2">
          @foreach ($links as $link)
            @can('isEnabled', [App\Models\Module::class, $link['can']])
              <li>
                <x-menu.nav-link :route="$link['route']" :name="$link['name']" :icon="$link['icon']" :active="$link['active']" />
              </li>
            @endcan
          @endforeach
        </ul>
      </div>
    </div>
    

  </nav>

  <nav id="menu-top" style="padding-left: 0" class="{{ request()->routeIs('admin.quick-sales.create') ? 'pl-14' : 'pl-52' }} fixed top-0 z-40 flex h-14 w-full border bg-white shadow">

    <div class="flex w-full px-2">

      {{-- Accesos directos --}}
      {{-- <div class="flex items-center">
        <ul class="flex space-x-3">
          @can('isEnabled', [App\Models\Module::class, 'facturas'])
            <li class="text-gray-600">
              <a href="{{ route('admin.bills.create') }}" class="flex flex-col items-center">
                <i class="ico icon-quote text-xl"></i>
                <span class="mt-1 text-xs">Crear factura</span>
              </a>
            </li>
          @endcan

          @can('isEnabled', [App\Models\Module::class, 'ventas rapidas'])
            <li class="text-gray-600">
              <a href="{{ route('admin.quick-sales.create') }}" class="flex flex-col items-center">
                <i class="ico icon-new-order text-xl"></i>
                <span class="mt-1 text-xs">Ventas rápidas</span>
              </a>
            </li>
          @endcan

          @can('isEnabled', [App\Models\Module::class, 'productos'])
            <li class="text-gray-600">
              <a href="{{ route('admin.products.index') }}" class="flex flex-col items-center">
                <i class="ico icon-inventory text-xl"></i>
                <span class="mt-1 text-xs">Productos</span>
              </a>
            </li>
          @endcan

        </ul>
      </div> --}}

      <div class="ml-auto flex h-full items-center space-x-3">

        @if (hasTerminal())
          <div class="hidden text-sm font-semibold text-slate-600 sm:block">
            Sede: {{ getTerminal()->name }}
          </div>
        @endif

        <div class="flex h-full items-center" title="Abrir caja registradora">
          <button wire:click='openCashRegister'>
            <i class="ico icon-payment text-xl text-green-600"></i>
          </button>
        </div>

       <!--   @if (App\Services\FactusConfigurationService::isApiEnabled())
          <div x-data="authenticatedFactus()">
            <button class="flex h-full items-center" title="Abrir Factus">
              <x-icons.factus class="h-6 w-6 text-indigo-800" title="Factus activo" />
            </button>
          </div>
        @endif

      @if (isRoot()) solo un icono que muestra si es root
          <div class="flex h-full items-center">
            <i class="ico icon-user-config text-2xl text-red-700"></i>
          </div>
        @endif-->

        <x-dropdown align="right" width="56">
          <x-slot name="trigger">
            <button
              class="flex items-center whitespace-nowrap text-sm font-medium text-gray-500 transition duration-150 ease-in-out hover:border-gray-300 hover:text-cyan-400 focus:border-gray-300 focus:text-gray-700 focus:outline-none"
              title="Perfil">
              <div class="ml-1 min-h-max min-w-max">
                <img class="h-9 w-9 rounded-full" src="{{ auth()->user()->profile_photo_url }}">
              </div>
            </button>
          </x-slot>

          <x-slot name="content">
            <span class="px-2 text-sm font-semibold text-gray-500">
              {{ Str::limit(Auth::user()->name, 20, '...') }}
            </span>
            <form method="POST" action="{{ route('logout') }}">
              @csrf

              @can('isEnabled', [App\Models\Module::class, 'roles y permisos'])
                <x-dropdown-link :href="route('admin.roles.index')" class="flex items-center">
                  <i class="ico icon-user-lock mr-2 text-lg"></i>
                  Roles y permisos
                </x-dropdown-link>
              @endcan

              @can('isEnabled', [App\Models\Module::class, 'rangos de numeración'])
                <x-dropdown-link :href="route('admin.numbering-ranges.index')" class="flex items-center">
                  <i class="ico icon-order-2 mr-2 text-lg"></i>
                  Rangos de numeración
                </x-dropdown-link>
              @endcan

              @can('isEnabled', [App\Models\Module::class, 'impuestos'])
                <x-dropdown-link :href="route('admin.tax-rates.index')" class="flex items-center">
                  <i class="ti ti-percentage mr-2 text-lg"></i>
                  Impuestos
                </x-dropdown-link>
              @endcan

              @can('isEnabled', [App\Models\Module::class, 'terminales'])
                <x-dropdown-link :href="route('admin.terminals.index')" class="flex items-center">
                  <i class="ico icon-terminal mr-2"></i>
                  Sedes
                </x-dropdown-link>
              @endcan

             @can('HavePermissionTo', [App\Models\Module::class, 'activar o desactivar modulos'])
                <x-dropdown-link :href="route('admin.modules.index')" class="flex items-center">
                  <i class="ti ti-layout-dashboard mr-2"></i>
                  Módulos
                </x-dropdown-link>
               
            @endcan
               
                <x-dropdown-link :href="route('admin.factus.connection')" class="flex items-center">
                  <i class="ti ti-api mr-2"></i>
                  Factus
                </x-dropdown-link>

              @can('isEnabled', [App\Models\Module::class, 'configuraciones'])
                <x-dropdown-link :href="route('admin.companies.settings')" class="flex items-center">
                  <i class="ico icon-settings mr-2"></i>
                  Configuración
                </x-dropdown-link>
              @endcan

              <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center">
                <i class="ico icon-logout mr-2"></i>
                Cerrar sesión
              </x-dropdown-link>
            </form>
          </x-slot>
        </x-dropdown>
      </div>
    </div>
  </nav>
</div>
@push('js')
  <script>
    function authenticatedFactus() {
      return {

        async openFactus() {
          response = await this.getTokenFactus();
          console.log('response',response);
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
