<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/x-icon" href="{{ route('home')  }}/favicon.ico">
        <title inertia>{{ config('app.name', 'Laravel') }}</title>
        <!--
        /*
         * Разработка PanddaDev
         * https://pandda.dev/
         */
        -->
        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js','resources/css/app.css',"resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased min-h-svh">
        @inertia
    </body>
</html>
