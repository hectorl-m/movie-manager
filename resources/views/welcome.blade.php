<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Movie Manager') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans text-gray-900 antialiased min-h-screen flex flex-col">

    <header class="w-full px-6 py-4 flex justify-between items-center bg-white shadow-sm border-b border-gray-100">
        <div class="flex items-center gap-2 font-bold text-xl text-indigo-600 tracking-tight">
            <x-application-logo />
            Movie Manager
        </div>

        @if (Route::has('login'))
            <nav class="flex items-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-gray-600 hover:text-indigo-600 transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-indigo-600 transition">
                        Log in
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-sm font-semibold bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition shadow-sm">
                            Register
                        </a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    <main class="flex-grow flex flex-col items-center justify-center text-center px-4 sm:px-6">
        
        <div class="mb-8 p-4 bg-indigo-50 text-indigo-700 rounded-full inline-flex items-center gap-2 text-sm font-semibold shadow-sm">
            Discover, rate, and organize
        </div>

        <h1 class="text-5xl sm:text-6xl font-extrabold text-gray-900 tracking-tight mb-6 leading-tight">
            Your Personal <br>
            <span class="text-indigo-600">Cinematic Universe</span>
        </h1>
        
        <p class="text-lg text-gray-600 max-w-2xl mb-10 leading-relaxed">
            Import data instantly from TMDB, create custom collections, and keep track of every movie you watch. Join the community and share your reviews.
        </p>

        <div class="flex flex-col sm:flex-row gap-4">
            @auth
                <a href="{{ url('/dashboard') }}" class="bg-indigo-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-indigo-700 transition shadow-md text-lg">
                    Go to Dashboard &rarr;
                </a>
            @else
                <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-indigo-700 transition shadow-md text-lg">
                    Get Started Free
                </a>
                <a href="{{ route('login') }}" class="bg-white text-gray-800 border border-gray-300 px-8 py-3 rounded-lg font-bold hover:bg-gray-50 transition shadow-sm text-lg">
                    Sign In
                </a>
            @endauth
        </div>
    </main>

    <footer class="py-6 text-center text-gray-400 text-sm border-t border-gray-200 bg-white">
        {{ date('Y') }} Movie Manager. Built with Laravel.
    </footer>

</body>
</html>