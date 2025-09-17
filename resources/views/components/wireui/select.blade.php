@props(['options', 'width' => '15'])
<div x-data="{
        open:false,
        selected: @entangle($attributes->wire('model')),
        options: @js($options),
        text: '',
        top:'',

        init(){
            for (var i in this.options) {
                if(this.selected === i){
                    this.text = this.options[i];
                }
            }
        }
    }"
    id="{{$attributes->wire('model')->value}}"
    x-init="document.getElementById('dropdown{{$attributes->wire('model')->value}}').style.top = document.getElementById('{{$attributes->wire('model')->value}}').clientHeight + 'px'"
    x-on:click.away="open=false"
    {{ $attributes->whereDoesntStartWith('wire:model')->merge(['class' => 'relative flex items-center select-none']) }}>
    <div x-on:click="open=!open" class="cursor-pointer border rounded px-4 py-2 flex items-center" style="min-width: {{$width}}rem">
        <span x-text="text"></span>
        <i class="ico icon-arrow-b ml-auto transition duration-300" :class="open ? 'rotate-180' : '' "></i>
    </div>
    <input type="text" x-ref="dropdown" value="ssd" class="hidden">
    <ul id="dropdown{{$attributes->wire('model')->value}}"  x-show="open" class="absolute bg-white shadow border rounded left-0 min-w-full whitespace-nowrap">
        <template  x-for="(option, index) in options" :key="index">
            <li x-on:click="selected=index; text=option; open=false" x-text="option" :class="index==selected ? 'font-semibold' : '' " class="px-4 cursor-pointer hover:bg-gray-100"></li>
        </template>
    </ul>
</div>
