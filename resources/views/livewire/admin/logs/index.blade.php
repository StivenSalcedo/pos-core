<div class="container">

    <x-commons.header>
        <x-wireui.button :href="route('admin.logs.file')" text="Logs" /> 
    </x-commons.header>

    <x-commons.table-responsive>

        <x-slot:top title="Logs">
        </x-slot:top>

        <table class="table">
            <thead>
                <tr>
                    <th left>
                        Fecha
                    </th>
                    <th left>
                        Nivel
                    </th>
                    <th left>
                        Mensaje
                    </th>
                    <th>
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $item)
                    <tr wire:key="logs-{{ $item->id }}">
                        <td left>
                            {{ $item->created_at->format('d-m-Y g:i a') }}
                        </td>
                        <td left>
                            {{ $item->level }}
                        </td>
                        <td left>
                            {{ $item->message }}
                        </td>
                        <td actions>
                            <x-buttons.show wire:click="openShow({{ $item->id }})" />
                            <x-buttons.delete wire:click="destroy({{ $item->id }})" />
                        </td>
                    </tr>
                @empty
                    <x-commons.table-empty />
                @endforelse
            <tbody>
        </table>
    </x-commons.table-responsive>

    <x-wireui.modal wire:model.defer="openShow" max-width="3xl" >
        <x-wireui.card title="Log">
            <div>
                @if ($openShow)

                    <div>
                        <span class="font-semibold">Fecha: </span>
                        <p class="pl-4">{{ $log->created_at->format('d-m-Y g:i a') }}</p>
                    </div>
                    
                    <div class="mt-4">
                        <span class="font-semibold">Mensaje: </span>
                        <p class="pl-4">{{ $log->message }}</p>
                    </div>

                    <div class="mt-4">
                        <span class="font-semibold">Datos:</span>
                        <ul class="pl-4">
                            @foreach ($log->data as $key => $item)

                                @if (is_array($item))

                                    <ul class="ml-4">   

                                        @foreach ($item as $key => $value)
                                            @if (!is_array($value))
                                                <li wire:key="log-selected2-{{$key}}">
                                                    <span class="font-semibold">{{ $key }}</span>: 
                                                    {{ $value }}
                                                </li>
                                            @endif
                                        @endforeach

                                    </ul>

                                @else

                                    <li wire:key="log-selected-{{$key}}">
                                        <span class="font-semibold">{{ $key }}</span>: 
                                        {{ $item }}
                                    </li>

                                @endif

                            @endforeach
                        </ul>
                    </div>

                @endif
            </div>

            <x-slot:footer>
                <div class="text-right">
                    <x-wireui.button secondary x-on:click="show=false" text="Cerra" /> 
                </div>
            </x-slot:footer>

        </x-wireui.card>
    </x-wireui.modal>

</div>