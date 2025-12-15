<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="MyBridge Admin">

        <title>{{ config('app.name', 'MBI') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
<link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
  
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        @if (request()->routeIs('login') || request()->routeIs('register'))
            <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
        @endif
    </head>
    <body class="font-sans text-gray-900 antialiased">
        @if (request()->routeIs('login') || request()->routeIs('register'))
            <div class="min-h-screen grid grid-cols-1 md:grid-cols-2 bg-white">
                <div class="min-h-screen flex flex-col justify-center px-6 md:px-16">
                    <div class="login-logo mb-6">
                        <a href="https://mybridgeinternational.org">
                            <x-application-logo />
                        </a>
                    </div>
                    <div class="max-w-lg">
                        {{ $slot }}
                    </div>
                    <div class="mt-6 text-sm text-center md:text-left">
                        @if (request()->routeIs('register'))
                            <span>Already a member?</span> <a href="{{ route('login') }}" class="text-[#007AFF]">Sign in</a>
                        @else
                            <span>Don't have an account?</span> <a href="{{ route('register') }}" class="text-[#007AFF]">Create account</a>
                        @endif
                    </div>
                </div>
                <div class="hidden md:block">
                    <img src="{{ asset('assets/images/bg.png') }}" alt="" class="h-full w-full object-cover" />
                </div>
            </div>
        @else
            <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
                <div class="login-logo">
                    <a href="/">
                        <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                    </a>
                </div>
                <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg login-card">
                    {{ $slot }}
                </div>
            </div>
        @endif
    </body>
</html>
