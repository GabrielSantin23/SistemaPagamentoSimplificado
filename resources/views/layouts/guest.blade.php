<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
        {{-- Basic styling for demonstration --}}
        <style>
            body { font-family: 'Figtree', sans-serif; background-color: #f3f4f6; }
            .container { min-height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 1.5rem; }
            .content-box { width: 100%; max-width: 28rem; margin-top: 1.5rem; background-color: white; padding: 2rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); border-radius: 0.5rem; }
            .logo { height: 2.5rem; /* Adjust as needed */ }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="container">
            <div>
                <a href="/">
                    {{-- Placeholder for application logo component --}}
                    {{-- <x-application-logo class="logo" /> --}}
                    <h2>{{ config('app.name', 'Laravel') }}</h2> {{-- Simple text logo --}}
                </a>
            </div>

            <div class="content-box">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>

