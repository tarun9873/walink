{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
<script src="{{ asset('js/app.js') }}" defer></script> <!-- only if you built js separately -->

  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>{{ config('app.name', 'Laravel') }}</title>

  {{-- Load assets --}}
  @if (file_exists(public_path('mix-manifest.json')))
    <link rel="stylesheet" href="{{ mix('css/app.css') }}" />
    <script src="{{ mix('js/app.js') }}" defer></script>
  @else
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  @endif
</head>
<body class="font-sans antialiased bg-gray-100">
  <div class="min-h-screen">

    {{-- navigation if available --}}
    @if (View::exists('layouts.navigation'))
      @include('layouts.navigation')
    @endif

    {{-- HEADER: either section('header') for @extends OR $header slot for components --}}
    @if (View::hasSection('header'))
      <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
          @yield('header')
        </div>
      </header>
    @elseif (isset($header))
      <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
          {{ $header }}
        </div>
      </header>
    @endif

    {{-- MAIN: prefer $slot when component-style, otherwise yield('content') --}}
    <main class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (isset($slot))
          {{ $slot }}
        @else
          @yield('content')
        @endif
      </div>
    </main>
  </div>
</body>
</html>
