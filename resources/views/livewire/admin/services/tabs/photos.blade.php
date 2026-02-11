<div>
    <div class="flex items-center justify-between border-b pb-4 mb-4">
        <h3 class="font-medium whitespace-normal text-lg">Registro fotográfico</h3>
        <div class="text-right">
            <x-wireui.button primary wire:click="openPhotoUpload" id="photo" text="Agregar fotografía" icon="add"
                spinner="update" />
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse($service->attachments as $photoShow)
            <div class="relative w-52 border rounded-lg overflow-hidden group">
                <img src="{{ asset('storage/' . $photoShow->path) }}" alt="{{ $photoShow->filename }}"
                    class="object-cover h-40 w-full cursor-pointer"
                    x-on:click="$dispatch('open-photo-preview', { src: '{{ asset('storage/' . $photoShow->path) }}' })">

                <x-wireui.button sm icon="trash" red wire:click="removePhoto({{ $photoShow->id }})"
                    tooltip="Eliminar foto" />

            </div>
        @empty
            <div class="col-span-4 text-center">
                <small class="text-gray-400">No hay imágenes registradas.</small>
            </div>
        @endforelse
    </div>

    <!-- Modal para subir imagen -->
    <x-wireui.modal wire:model.defer="openUploadModal" max-width="lg">
        <x-wireui.card title="Subir imagen del servicio">
            <x-wireui.errors />

            <div class="space-y-4">
                <x-wireui.input type="file" label="Seleccionar imagen" wire:model="photo" accept="image/*" />

                @if ($photo)
                    <div class="text-center"> <img src="{{ $photo->temporaryUrl() }}"
                            class="mx-auto h-40 rounded-lg shadow"> </div>
                @endif
            </div>

            <x-slot:footer>
                <div class="flex justify-end space-x-3">
                    <x-wireui.button secondary text="Cancelar" x-on:click="$wire.openUploadModal = false" />
                    <x-wireui.button primary text="Guardar" wire:click="savePhoto" />
                </div>
            </x-slot:footer>
        </x-wireui.card>
    </x-wireui.modal>

    <!-- Modal de previsualización -->
    <div x-data="{ open: false, src: '' }" x-on:open-photo-preview.window="open = true; src = $event.detail.src">
        <x-wireui.modal x-model="open" max-width="4xl">
            <div class="text-center">
                <img :src="src" class="rounded-lg mx-auto max-h-[80vh]">
            </div>
        </x-wireui.modal>
    </div>
</div>
