<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gradient-to-b from-gray-50 to-white flex flex-col relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-teal-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
            <div class="absolute top-1/3 right-1/4 w-96 h-96 bg-emerald-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-1/4 left-1/3 w-96 h-96 bg-green-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>

    <main>
{{--        TODO!! --}}
    </main>
    </body>
</html>
