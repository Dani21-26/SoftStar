<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SoftSart</title>

    <!-- Fonts y Tailwind -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-500 to-blue-900 text-white flex flex-col">

    <!-- Header flotante arriba -->
    <header class="w-full px-6 py-4 flex justify-end">
        @if (Route::has('login'))
            <nav class="flex items-center gap-4 text-sm">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-4 py-2 border rounded hover:border-white">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 border border-white rounded hover:bg-white hover:text-blue-900 transition">
                        Login
                    </a>
                @endauth
            </nav>
        @endif
    </header>

    <!-- Contenido centrado -->
    <main class="flex-grow flex items-center justify-center text-center px-4">
        <div>
            <h1 class="text-4xl font-bold mb-4">Bienvenido a SoftSart 👋</h1>
            <p class="text-lg">Por favor inicia sesión para continuar</p>
        </div>
    </main>

</body>

</html>
