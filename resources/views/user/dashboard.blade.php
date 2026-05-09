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
            <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🍳</span>
                    <span class="text-xl font-bold tracking-tight text-orange-900">Cookify</span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-500">
                        Welcome, <span class="font-semibold text-orange-800">{{ Auth::user()->name }}</span>
                    </span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="text-sm px-4 py-1.5 rounded-full border border-orange-300 text-orange-700 hover:bg-orange-100 transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="max-w-5xl mx-auto px-6 py-12">

            <div class="mb-8">
                <span class="inline-flex items-center gap-1.5 bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full mb-3">
                    👤 User
                </span>
                <h1 class="text-3xl font-bold text-gray-900">Your Dashboard</h1>
                <p class="text-gray-500 mt-1">Everything you need in one place</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-10">

                <a href="{{ route('user.favourites.index') }}"
                   class="group bg-white rounded-2xl border border-orange-100 p-6 shadow-sm hover:shadow-md hover:border-orange-300 transition-all">
                    <div class="text-3xl mb-3">❤️</div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-orange-700 transition">My Favourites</h3>
                    <p class="text-sm text-gray-400 mt-1">View your saved recipes</p>
                </a>

                <a href="{{ route('search') }}"
                   class="group bg-orange-600 rounded-2xl p-6 shadow-sm hover:bg-orange-700 transition-all">
                    <div class="text-3xl mb-3">🔍</div>
                    <h3 class="font-semibold text-white">Search Recipes</h3>
                    <p class="text-sm text-orange-200 mt-1">Find something delicious</p>
                </a>

            </div>

            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 text-sm text-orange-700 hover:text-orange-900 font-medium transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Home
            </a>

        </main>
    </div>

</body>
</html>