@props(['options' => null])
@php

$optionsDefault = [
0 => 'Todos',
1 => 'Hoy',
2 => 'Esta semana',
3 => 'Ultimos 7 días',
4 => 'La semana pasada',
5 => 'Hace 15 días',
6 => 'Este mes',
7 => 'El mes pasado',
8 => 'Rango de fechas'];

$options = $options ?? $optionsDefault;

@endphp
<div x-data="{
        open:false,
        date: @entangle($attributes->wire('model')),
        text: '',
        filters: '',

        init() {
          this.filters = @js($options);
            this.text = this.filters[this.date];
        },

        selected(index, item) {
            this.text = item;
            this.date = index;
            this.open=false;
        }
    }" class="relative w-44 border border-slate-200 bg-white select-none z-10">
  <div x-on:click="open = !open" class="flex w-full h-full items-center px-3 py-2 cursor-pointer ">
    <i class="ico icon-calendar mr-1"></i>
    <h1 x-text="text" class="font-semibold text-slate-600 text-sm"></h1>
  </div>
  <ul x-cloak x-show="open" x-on:click.away="open=false"
    class="absolute bg-white border border-slate-200 py-0.5 w-44 text-sm text-slate-600 mt-2">
    <template x-for="(item, index) in filters" :key=" index">
      <li x-on:click="selected(index, item)" :class="date == index && 'text-indigo-600 font-semibold'"
        class="hover:bg-slate-100 px-2 py-1 cursor-pointer">
        <i class="ico text-sm inline-flex w-3.5" :class="date == index && 'icon-check'"></i>
        <span x-text="item"></span>
      </li>
    </template>
  </ul>
</div>
