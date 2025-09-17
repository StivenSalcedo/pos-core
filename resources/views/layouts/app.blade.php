@php
  $config = [
      'customer' => App\Models\Customer::default()->toArray(),
      'change' => session('config')->change == '0',
      'print' => session('config')->print == '0',
      'width_ticket' => session('config')->width_ticket,
      'format_percentage_tip' => session('config')->format_percentage_tip,
      'percentage_tip' => session('config')->percentage_tip,
  ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  @isset($title)
    <title>{{ $title }}</title>
  @else
    <title>@yield('title')</title>
  @endisset

  <!-- Fonts and styles -->
  <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">
  <link rel="stylesheet" href="{{ asset('vendor/icomoon-v1.0/style.css') }}?v9">
  <style id="page-rule">
    @page {
      size: 80mm 178mm;
      margin: 0cm;
    }
  </style>

  <!-- Scripts -->
  <script src="{{ asset('ts/app.js') }}" defer></script>

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  @livewireStyles

</head>

<body class="antialiased scroll-smooth font-manrope">

  <div class="min-h-screen bg-gray-100 no-print text-slate-800">

    <div x-data x-init='$store.config.set({{ json_encode($config) }});'></div>

    <x-loads.alpine />

    <livewire:admin.menu>

      <main class="{{ request()->routeIs('admin.quick-sales.create') ? 'pl-14' : 'pl-52' }} pt-14 min-h-screen">
        {{ $slot }}
      </main>

  </div>

  @include('pdfs.ticket-open-cash-register')
  @include('partials.footer')

  @stack('html')

  @stack('js')

  @livewireScripts
</body>

</html>
