@props(['key'])

<div x-cloak
    x-data="mainMenuModal()"
    id="menu"
    class="select-none max-w-min"
    x-on:click.outside="open = false"
    x-on:close.stop="open = false">

    <div id="btn{{$key}}"
        x-on:click="$nextTick(() => { if(!open){ openMenu({{ $key }}) }else{ open=!open } })"
        class="fas fa-ellipsis-h text-gray-600 text-2xl cursor-pointer px-2 flex items-center justify-center rounded-full hover:bg-slate-100"
        style="width: 2.5rem; height: 2.5rem;">
        {{ $trigger }}
    </div>

    <ul id="{{$key}}"
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed z-50 bg-white border border-gray-300 rounded overflow-hidden py-1"
        style="max-width: 11rem; min-width: 11rem">

        {{$slot}}

    </ul>
</div>

@push('js')
    <script>
        function mainMenuModal(){

            return {

                open: false,

                openMenu: function(key){

                    btn = document.getElementById('btn' + key);
                    menu = document.getElementById(key);
                    cordenadas = btn.getBoundingClientRect();
                    menu.style.top= (cordenadas.top + 40) + "px" ;
                    menu.style.left= (cordenadas.left - 170 + 20) + "px" ;
                    this.open = true;
                }
            }
        }
    </script>
@endpush

