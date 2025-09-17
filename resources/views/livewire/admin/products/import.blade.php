<div>
    <x-wireui.modal wire:model.defer="openImport" >
        <x-wireui.card title="Importar productos desde excel">
            <div>
                <div class="flex flex-col justify-center items-center">

                    <img class="object-cover object-center {{ $file ? '' : 'opacity-40' }} " src="{{ Storage::url('images/system/excel.png') }}">

                    @if ($file)
                        <span class="text-sm">{{ Str::limit($file->getClientOriginalName(), 30, '...')}}</span>
                    @endif
                </div>

                <div x-data="loadFile()" x-bind="loading" class="mt-3 flex flex-col max-w-xs mx-auto">
                    @error('preFiles')
                        {{ $message }}
                    @enderror
                    <div x-cloak x-show="isUploading" class="w-full bg-gray-200 h-2 mb-1 rounded-full overflow-hidden">
                        <div class="bg-blue-600 h-2" :style="`width: ${progress}%;`"></div>
                    </div>

                    <input type="file" x-ref="file" wire:model="preFile" class="hidden">

                    <button x-on:click="$refs.file.click()" class=" px-4 py-2 bg-blue-500 text-white rounded disabled:opacity-70">
                        Seleccionar Excel
                    </button>
                </div>

                <div class="text-sm text-red-500">
                    <span class="font-bold">
                        Nota:
                    </span>
                    <p>
                        Esta opción borrará todos los productos agregados anteriormente de la base de datos
                    </p>
                </div>

                <div class="mt-4">
                    <a wire:click="downloadExample" class="text-sm underline text-blue-700 cursor-pointer">
                        <i class="ico icon-download"></i>
                        Descargar plantilla
                    </a>
                </div>

                <x-wireui.errors />

            </div>

            <x-slot:footer>
                <div class="flex justify-end items-center space-x-3">

                    <x-wireui.button secondary x-on:click="show=false" text="Cancelar" />
                    @if ($file)
                        <x-wireui.button wire:click="loadProducts" text="Cargar productos" load textLoad="Cargando..." wire:key="button1" />
                    @else
                        <x-wireui.button disabled text="Cargar productos" wire:key="button2" />
                    @endif
                </div>
            </x-slot:footer>
        </x-wireui.card>
    </x-wireui.modal>
</div>




