<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Cookify</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-orange-50">

    <div class="min-h-screen">
        <header class="bg-white border-b border-orange-100 shadow-sm">
            <div class="max-w-5xl mx-auto px-4 md:px-6 py-3 md:py-4 flex items-center justify-between">
                <div class="flex items-center gap-2 md:gap-3">
                    <span class="text-xl md:text-2xl">🍳</span>
                    <span class="text-lg md:text-xl font-bold tracking-tight text-orange-900">Cookify</span>
                </div>
                <div class="flex items-center gap-2 md:gap-4">
                    <span class="hidden sm:inline text-xs md:text-sm text-gray-500">
                        Welcome, <span class="font-semibold text-orange-800">{{ Auth::user()->name }}</span>
                    </span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="text-xs md:text-sm px-3 md:px-4 py-1.5 rounded-full border border-orange-300 text-orange-700 hover:bg-orange-100 transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="max-w-5xl mx-auto px-4 md:px-6 py-8 md:py-12">

            <div class="mb-6 md:mb-8">
                <span class="inline-flex items-center gap-1.5 bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full mb-3">
                    👤 User
                </span>
                {{-- Show name on mobile since header hides it --}}
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                    Hey, {{ Auth::user()->name }}! 👋
                </h1>
                <p class="text-sm md:text-base text-gray-500 mt-1">Everything you need in one place</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4 mb-8 md:mb-10">

                <a href="{{ route('user.favourites.index') }}"
                   class="group bg-white rounded-2xl border border-orange-100 p-4 md:p-6 shadow-sm hover:shadow-md hover:border-orange-300 transition-all">
                    <div class="text-2xl md:text-3xl mb-2 md:mb-3">❤️</div>
                    <h3 class="font-semibold text-sm md:text-base text-gray-800 group-hover:text-orange-700 transition">My Favourites</h3>
                    <p class="text-xs md:text-sm text-gray-400 mt-1">View your saved recipes</p>
                </a>

                <a href="{{ route('search') }}"
                   class="group bg-orange-600 rounded-2xl p-4 md:p-6 shadow-sm hover:bg-orange-700 transition-all">
                    <div class="text-2xl md:text-3xl mb-2 md:mb-3">🔍</div>
                    <h3 class="font-semibold text-sm md:text-base text-white">Search Recipes</h3>
                    <p class="text-xs md:text-sm text-orange-200 mt-1">Find something delicious</p>
                </a>

            </div>

            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 text-xs md:text-sm text-orange-700 hover:text-orange-900 font-medium transition">
                <svg class="w-3.5 h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Home
            </a>

        </main>
    </div>

</body>
</html>