{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
<script src="{{ asset('js/app.js') }}" defer></script> <!-- only if you built js separately -->

  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Walive – WhatsApp Link Generator & QR Maker</title>
<meta name="description" content="Create WhatsApp links instantly with Walive Link — generate wa.me-style short links, custom pre-filled messages, and QR codes. No signup required.">
<meta name="keywords" content="WhatsApp link generator, wa.link alternative, WhatsApp QR code, WhatsApp short link, click to chat, WhatsApp link maker">
<meta name="robots" content="index, follow">


<!-- Open Graph -->
<meta property="og:title" content="Walive – WhatsApp Link Generator & QR Maker">
<meta property="og:description" content="Generate WhatsApp short links, custom messages, and QR codes instantly. The best free Click-to-Chat tool.">
<meta property="og:url" content="https://walive.link/">
<meta property="og:type" content="website">
<meta property="og:image" content="/assets/og-image.png">


<!-- Schema (basic) -->
<script type="application/ld+json">
{
"@context":"https://schema.org",
"@type":"WebSite",
"name":"Walive",
"url":"https://walive.link/",
"description":"Create WhatsApp short links, custom messages, and QR codes instantly. A perfect alternative to wa.link."
}
</script>

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
