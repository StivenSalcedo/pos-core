@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-md shadow-sm border border-slate-300 focus:ring-cyan-400 focus:border-cyan-400']) !!}>
