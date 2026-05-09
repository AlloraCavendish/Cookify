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
         class="fixed top-6 right-6 z-50 hidden px-5 py-3 rounded-xl shadow-lg text-sm font-medium text-white transition-all">
    </div>

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
            <h1 class="text-3xl font-bold text-gray-900">💖 My Favourite Recipes</h1>
            <p class="text-gray-500 mt-1">Recipes you've saved for later</p>
        </div>

        @if($favourites->isEmpty())
            <div class="bg-white rounded-2xl border border-orange-100 p-12 text-center shadow-sm">
                <div class="text-5xl mb-4">🍽️</div>
                <p class="text-gray-500 text-sm">You don't have any favourite recipes yet.</p>
                <a href="{{ route('search') }}"
                   class="inline-block mt-4 text-sm text-orange-600 hover:text-orange-800 font-medium transition">
                    Find some recipes →
                </a>
            </div>
        @else
            <div id="favourites-list" class="space-y-4">
                @foreach($favourites as $fav)
                    <div id="fav-{{ $fav->recipe_id }}"
                         class="bg-white rounded-2xl border border-orange-100 px-6 py-4 shadow-sm flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-800">
                                {{ optional($fav->recipe)->title ?? 'Recipe not available' }}
                            </h3>
                            <p class="text-xs text-gray-400 mt-0.5">Added {{ $fav->created_at->diffForHumans() }}</p>
                        </div>

                        <button
                            onclick="removeFavourite({{ $fav->recipe_id }}, this)"
                            class="text-sm px-4 py-1.5 rounded-full border border-red-200 text-red-500 hover:bg-red-50 transition">
                            ❌ Remove
                        </button>
                    </div>
                @endforeach
            </div>
        @endif

        <a href="{{ route('user.dashboard') }}"
           class="inline-flex items-center gap-2 text-sm text-orange-700 hover:text-orange-900 font-medium transition mt-8">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Dashboard
        </a>

    </main>

    <script>
        function showToast(message, success = true) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = `fixed top-6 right-6 z-50 px-5 py-3 rounded-xl shadow-lg text-sm font-medium text-white transition-all ${success ? 'bg-green-500' : 'bg-red-500'}`;
            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), 3000);
        }

        async function removeFavourite(recipeId, btn) {
            btn.disabled = true;
            btn.textContent = 'Removing...';

            try {
                const response = await fetch(`/user/favourites/${recipeId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({})
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Remove card from DOM
                    const card = document.getElementById(`fav-${recipeId}`);
                    card.style.transition = 'opacity 0.3s';
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.remove();

                        // Show empty state if no favourites left
                        const list = document.getElementById('favourites-list');
                        if (list && list.children.length === 0) {
                            list.innerHTML = `
                                <div class="bg-white rounded-2xl border border-orange-100 p-12 text-center shadow-sm">
                                    <div class="text-5xl mb-4">🍽️</div>
                                    <p class="text-gray-500 text-sm">You don't have any favourite recipes yet.</p>
                                    <a href="{{ route('search') }}"
                                       class="inline-block mt-4 text-sm text-orange-600 hover:text-orange-800 font-medium transition">
                                        Find some recipes →
                                    </a>
                                </div>
                            `;
                        }
                    }, 300);

                    showToast('💔 Recipe removed from favourites!');
                } else {
                    showToast(data.message || '❌ Failed to remove recipe', false);
                    btn.disabled = false;
                    btn.textContent = '❌ Remove';
                }
            } catch (error) {
                showToast('❌ Something went wrong. Please try again.', false);
                btn.disabled = false;
                btn.textContent = '❌ Remove';
            }
        }
    </script>

</body>
</html>