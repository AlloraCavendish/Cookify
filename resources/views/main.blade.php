<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cookify: Smart Recipe Recommendation System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-orange-50 min-h-screen">

    {{-- Header --}}
    <header class="bg-white border-b border-orange-100 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 md:px-6 py-3 md:py-4 flex items-center justify-between">
            <div class="flex items-center gap-2 md:gap-3">
                <span class="text-xl md:text-2xl">🍳</span>
                <span class="text-lg md:text-xl font-bold tracking-tight text-orange-900">Cookify</span>
            </div>
            <div class="flex items-center gap-2 md:gap-4">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="text-xs md:text-sm text-orange-700 hover:text-orange-900 font-medium transition">
                        Dashboard
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="text-xs md:text-sm px-3 md:px-4 py-1.5 rounded-full border border-orange-300 text-orange-700 hover:bg-orange-100 transition">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="text-xs md:text-sm text-orange-700 hover:text-orange-900 font-medium transition">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="text-xs md:text-sm px-3 md:px-4 py-1.5 rounded-full bg-orange-600 text-white hover:bg-orange-700 transition">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- Hero Search --}}
    <section class="max-w-5xl mx-auto px-4 md:px-6 py-10 md:py-16 text-center">
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-2 md:mb-3">
            Find Recipes from Your Fridge
        </h1>
        <p class="text-sm md:text-base text-gray-500 mb-6 md:mb-8">
            Enter what you have and we'll find what you can cook 🥘
        </p>

        <form id="ingredientForm" class="flex flex-col sm:flex-row gap-2 md:gap-3 max-w-xl mx-auto">
            <input type="text" id="ingredients"
                placeholder="e.g. eggs, tomato, cheese"
                class="flex-1 px-4 py-3 rounded-xl border border-orange-200 focus:outline-none focus:ring-2 focus:ring-orange-300 text-sm shadow-sm w-full"
                required>
            <button type="submit"
                class="w-full sm:w-auto px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-xl text-sm transition shadow-sm">
                Search
            </button>
        </form>
    </section>

    {{-- Toast Notification --}}
    <div id="toast"
         class="fixed top-4 right-4 md:top-6 md:right-6 z-50 hidden px-4 md:px-5 py-2.5 md:py-3 rounded-xl shadow-lg text-xs md:text-sm font-medium text-white transition-all max-w-xs md:max-w-sm">
    </div>

    {{-- Results --}}
    <section class="max-w-5xl mx-auto px-4 md:px-6 pb-16">
        <div id="results"></div>

        {{-- Pagination --}}
        <div id="pagination" class="hidden flex items-center justify-center gap-2 md:gap-4 mt-6 md:mt-8">
            <button id="prevPage"
                class="px-3 md:px-4 py-2 rounded-xl border border-orange-200 text-orange-700 hover:bg-orange-100 text-xs md:text-sm transition disabled:opacity-40">
                ← Prev
            </button>
            <span class="text-xs md:text-sm text-gray-500">
                Page <span id="currentPage">1</span>
            </span>
            <button id="nextPage"
                class="px-3 md:px-4 py-2 rounded-xl border border-orange-200 text-orange-700 hover:bg-orange-100 text-xs md:text-sm transition disabled:opacity-40">
                Next →
            </button>
        </div>
    </section>

    <script>
        window.cookify = {
            isAuthenticated: @json(auth()->check()),
            userFavourites: @json($favourites ?? []),
            csrfToken: "{{ csrf_token() }}"
        };
    </script>
    <script src="{{ asset('js/main.js') }}"></script>

</body>
</html>