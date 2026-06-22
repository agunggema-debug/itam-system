<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#2563eb">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="ITAM">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <title>{{ $title ?? 'ITAM System' }} - IT Asset Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-14">
                <a href="/" class="font-bold text-lg">ITAM System</a>
                <div class="flex items-center space-x-4 text-sm">
                    @auth
                    <span class="hidden md:inline text-blue-200">{{ auth()->user()->name }}</span>
                    <a href="{{ route('scanner') }}" class="hover:bg-blue-700 px-3 py-2 rounded">Scanner</a>
                    <a href="{{ route('admin.dashboard') }}" class="hover:bg-blue-700 px-3 py-2 rounded">Dashboard</a>
                    <a href="{{ route('admin.assets') }}" class="hover:bg-blue-700 px-3 py-2 rounded">Assets</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="hover:bg-blue-700 px-3 py-2 rounded">Logout</button>
                    </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    <main class="max-w-7xl mx-auto px-4 py-6">
        {{ $slot }}
    </main>
    @livewireScripts
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/service-worker.js');
            });
        }
    </script>
</body>
</html>
