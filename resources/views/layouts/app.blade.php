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
<!-- Primary meta tags -->

<meta name="description" content="Walive: Free WhatsApp link generator and QR code maker. Create wa.me style short links, custom pre-filled messages and QR codes — share on Instagram, websites, product pages and marketing." />
<meta name="keywords" content="WhatsApp link generator, WhatsApp short link, wa.me, wa.link alternative, WhatsApp QR code, WhatsApp click to chat, WhatsApp link maker, Walive" />
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
<link rel="canonical" href="https://walive.link/" />

<!-- Viewport & mobile -->
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="format-detection" content="telephone=no">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website" />
<meta property="og:title" content="Walive – WhatsApp Link Generator & QR Code Maker" />
<meta property="og:description" content="Create wa.me-style WhatsApp links with custom pre-filled messages and QR codes. Fast, free, no signup." />
<meta property="og:url" content="https://walive.link/" />
<meta property="og:image" content="https://walive.link/assets/og-image.png" />
<meta property="og:image:alt" content="Walive - WhatsApp Link Generator & QR Code Maker" />
<meta property="og:site_name" content="Walive" />

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="@Walive" />
<meta name="twitter:creator" content="@Walive" />
<meta name="twitter:title" content="Walive – WhatsApp Link Generator & QR Code Maker" />
<meta name="twitter:description" content="Create WhatsApp links with custom messages and QR codes. Perfect for businesses, sellers and creators." />
<meta name="twitter:image" content="https://walive.link/assets/og-image.png" />

<!-- Canonical alternatives / hreflang (example for English + Hindi) -->
<link rel="alternate" href="https://walive.link/" hreflang="en" />
<link rel="alternate" href="https://walive.link/hi/" hreflang="hi" />
<link rel="alternate" href="https://walive.link/" hreflang="x-default" />

<!-- Favicons -->
<link rel="icon" href="/favicon.ico" />
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">

<!-- Sitemap (helps crawlers find pages) -->
<link rel="sitemap" type="application/xml" title="Sitemap" href="https://walive.link/sitemap.xml" />



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
