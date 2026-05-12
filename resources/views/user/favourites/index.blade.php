<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Favourite Recipes - Cookify</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-orange-50 min-h-screen">

    {{-- Toast --}}
    <div id="toast"
         class="fixed top-4 right-4 md:top-6 md:right-6 z-50 hidden px-4 md:px-5 py-2.5 md:py-3 rounded-xl shadow-lg text-xs md:text-sm font-medium text-white transition-all max-w-xs">
    </div>

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
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">💖 My Favourite Recipes</h1>
            <p class="text-sm md:text-base text-gray-500 mt-1">Recipes you've saved for later</p>
        </div>

        @if($favourites->isEmpty())
            <div class="bg-white rounded-2xl border border-orange-100 p-8 md:p-12 text-center shadow-sm">
                <div class="text-4xl md:text-5xl mb-4">🍽️</div>
                <p class="text-gray-500 text-sm">You don't have any favourite recipes yet.</p>
                <a href="{{ route('search') }}"
                   class="inline-block mt-4 text-sm text-orange-600 hover:text-orange-800 font-medium transition">
                    Find some recipes →
                </a>
            </div>
        @else
            <div id="favourites-list" class="space-y-3 md:space-y-4">
                @foreach($favourites as $fav)
                    <div id="fav-{{ $fav->recipe_id }}"
                         class="bg-white rounded-2xl border border-orange-100 px-4 md:px-6 py-3 md:py-4 shadow-sm flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <h3 class="font-semibold text-sm md:text-base text-gray-800 truncate">
                                {{ optional($fav->recipe)->title ?? 'Recipe not available' }}
                            </h3>
                            <p class="text-xs text-gray-400 mt-0.5">Added {{ $fav->created_at->diffForHumans() }}</p>
                        </div>

                        <button
                            onclick="removeFavourite({{ $fav->recipe_id }}, this)"
                            class="flex-shrink-0 text-xs md:text-sm px-3 md:px-4 py-1.5 rounded-full border border-red-200 text-red-500 hover:bg-red-50 transition">
                            ❌ Remove
                        </button>
                    </div>
                @endforeach
            </div>
        @endif

        <a href="{{ route('user.dashboard') }}"
           class="inline-flex items-center gap-2 text-xs md:text-sm text-orange-700 hover:text-orange-900 font-medium transition mt-6 md:mt-8">
            <svg class="w-3.5 h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Dashboard
        </a>

    </main>

    <script>
        window.cookify = {
            searchUrl: "{{ route('search') }}"
        };
    </script>
    <script src="{{ asset('js/favourites.js') }}"></script>

</body>
</html>