<div
  class="no-print fixed inset-x-0 bottom-0 border-t border-slate-300 bg-slate-100 py-1 text-sm font-semibold text-slate-900">
  <div class="grid grid-cols-3">

    <div class="{{ request()->routeIs('admin.quick-sales.create') ? 'pl-16' : 'pl-56' }}">
      <a href="https://halltec.co/" target="_blank" class="hover:text-blue-600">
        <span class="mr-1">Hecho con </span>
        <i class="ico icon-heart text-sm text-red-700"></i>
        <span class="ml-1">por</span>
        <span class="font-bold text-red-700"> HALLTEC</span>
      </a>
    </div>

    <div class="flex items-center justify-center text-blue-600">
      <a href="https://hallpos.com.co/terminos-y-condiciones.html" target="_blank"
        class="border-b border-blue-600">Términos y condiciones</a>
      <span class="border-l-2 border-slate-400 mx-4 h-full"></span>
      <a href="https://hallpos.com.co/politica-de-tratamiento-de-datos.html" target="_blank"
        class="border-b border-blue-600">Política de privacidad</a>
    </div>
  </div>
</div>
