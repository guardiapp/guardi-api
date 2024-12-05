<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
    {{-- <link rel="stylesheet" href="/assets/css/tailwind.output.css" /> --}}
    <!-- Vite CSS -->
    @vite('resources/css/app.css')
    {{-- <script
      src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"
      defer
    ></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.2/dist/cdn.min.js" defer></script>

    <script src="/assets/js/init-alpine.js"></script>
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"
      defer
    ></script>
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
  </head>
    @inertia
</html>
