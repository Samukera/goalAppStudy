<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name', 'Metas Pessoais') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
    <nav class="bg-white shadow mb-6">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between">
            <div>
                <a href="{{ route('goals.index') }}" class="text-lg font-bold text-blue-600">Metas</a>
            </div>
            <div>
                @auth
                    <span class="mr-4">{{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button class="text-red-600 hover:underline">Sair</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="px-4">
        @yield('content')
    </main>
</body>
</html>
