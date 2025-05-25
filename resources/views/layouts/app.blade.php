<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <script src="https://cdn.tailwindcss.com"></script>

        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: {
                                50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd', 400: '#60a5fa',
                                500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 800: '#1e40af', 900: '#1e3a8a'
                            }
                        }
                    }
                }
            }
        </script>

        <style>
            body { font-family: 'Figtree', sans-serif; background-color: #f3f4f6; margin: 0; }
            .app-layout { display: flex; flex-direction: column; min-height: 100vh; }
            .navbar { background-color: white; padding: 1rem 2rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); display: flex; justify-content: space-between; align-items: center; }
            .navbar a { text-decoration: none; color: #374151; margin-left: 1rem; }
            .navbar a:hover { color: #4f46e5; }
            .navbar .logo { font-weight: 600; font-size: 1.25rem; margin-left: 0; }
            .navbar form button { background: none; border: none; color: #6b7280; cursor: pointer; font-size: 1rem; }
            .navbar form button:hover { color: #ef4444; }
            .main-content { flex-grow: 1; padding: 2rem; }
            .content-wrapper { max-width: 7xl; margin-left: auto; margin-right: auto; background-color: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); }
            .page-header { font-size: 1.5rem; font-weight: 600; margin-bottom: 1.5rem; color: #1f2937; }
            .status-message { margin-bottom: 1rem; padding: 1rem; background-color: #d1fae5; color: #065f46; border-radius: 0.375rem; }
            .error-message { margin-bottom: 1rem; padding: 1rem; background-color: #fee2e2; color: #991b1b; border-radius: 0.375rem; }
            /* Basic Card Styling */
            .card-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; }
            .card { background-color: lightblue; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1.5rem; text-align: center; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out; text-decoration: none; color: #374151; }
            .card:hover { transform: translateY(-5px); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
            .card h3 { margin-top: 0; font-size: 1.125rem; font-weight: 600; }
            .card p { font-size: 0.875rem; color: #6b7280; }
            /* Basic Table Styling */
            table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
            th, td { border: 1px solid #e5e7eb; padding: 0.75rem; text-align: left; }
            th { background-color: #f9fafb; font-weight: 600; color: #374151; }
            tr:nth-child(even) { background-color: #f9fafb; }
            /* Basic Form Styling */
            .form-label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151; }
            .form-input { display: block; width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; margin-bottom: 1rem; box-sizing: border-box; }
            .form-button { background-color: #4f46e5; color: white; padding: 0.5rem 1rem; border: none; border-radius: 0.375rem; cursor: pointer; }
            .form-button-delete { background-color: #ef4444; }
            .form-link { color: #4f46e5; text-decoration: underline; }
            .form-error { color: #ef4444; font-size: 0.875rem; margin-top: -0.75rem; margin-bottom: 1rem; }
        </style>

    </head>
    <body>
        <div class="app-layout">
            <!-- Navigation -->
            <nav class="navbar">
                <a href="{{ route('dashboard') }}" class="logo">{{ config('app.name', 'Laravel') }}</a>
                <div>
                    <span>{{ Auth::user()->name }} ({{ Auth::user()->user_type }}) - Saldo: R$ {{ number_format(Auth::user()->balance, 2, ',', '.') }}</span>
                    <a href="{{ route('profile.edit') }}">Profile</a>
                    <!-- Logout Form -->
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </div>
            </nav>

            <!-- Page Heading -->
            @hasSection('header')
                <header style="background-color: white; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); padding: 1rem 2rem;">
                    <h2 class="page-header" style="margin: 0;">
                        @yield('header')
                    </h2>
                </header>
            @endif

            <!-- Page Content -->
            <main class="main-content">
                 <!-- Session Status Messages -->
                @if (session('success'))
                    <div class="status-message">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="error-message">
                        {{ session('error') }}
                    </div>
                @endif
                 @if (session('status'))
                    <div class="status-message">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="content-wrapper">
                     @yield('content')
                </div>
            </main>
        </div>
    </body>
</html>

