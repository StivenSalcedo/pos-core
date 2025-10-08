<div>
  <div class="flex justify-between mb-3">
    <h3 class="text-lg font-semibold text-gray-700">Registro fotográfico</h3>
    <x-wireui.button sm icon="camera" primary wire:click="openPhotoUpload" text="Subir foto" />
  </div>

  <div class="grid grid-cols-4 gap-4">
    @forelse($service->attachments as $photo)
      <div class="relative border rounded-lg overflow-hidden group">
        <img src="{{ asset('storage/'.$photo->path) }}" alt="Foto" class="object-cover h-40 w-full">
        <button wire:click="removePhoto({{ $photo->id }})" class="absolute top-2 right-2 bg-white/80 p-1 rounded-full text-red-600 opacity-0 group-hover:opacity-100 transition">
          <x-wireui.icon name="trash" class="w-4 h-4" />
        </button>
      </div>
    @empty
      <p class="text-gray-400">No hay imágenes registradas.</p>
    @endforelse
  </div>
</div>
