const isAuthenticated = window.cookify.isAuthenticated;
let userFavourites = window.cookify.userFavourites;
let currentPage = 1;
let totalPages = 1;

// Toast notification
function showToast(message, success = true) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `fixed top-6 right-6 z-50 px-5 py-3 rounded-xl shadow-lg text-sm font-medium text-white transition-all ${success ? 'bg-green-500' : 'bg-red-500'}`;
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 3000);
}

function renderRecipeCard(r, type = "ready") {
    const isReady = type === "ready";
    const isFav = userFavourites.includes(r.id);

    const favBtn = isAuthenticated ? `
        <button onclick="toggleFavourite(${r.id}, ${isFav})" id="fav-btn-${r.id}"
            class="text-sm px-3 py-1 rounded-full border ${isFav
                ? 'border-red-200 text-red-500 hover:bg-red-50'
                : 'border-orange-200 text-orange-500 hover:bg-orange-50'} transition">
            ${isFav ? '💔 Remove' : '❤️ Save'}
        </button>
    ` : '';

    return `
        <div class="bg-white rounded-2xl border ${isReady ? 'border-green-200' : 'border-orange-200'} px-6 py-4 shadow-sm flex items-center justify-between mb-3">
            <div>
                <span class="inline-block text-xs font-semibold px-2 py-0.5 rounded-full mb-1 ${isReady ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700'}">
                    ${isReady ? '✅ Ready to Cook' : '⚠️ Almost Ready'}
                </span>
                <h3 class="font-semibold text-gray-800">${r.title}</h3>
                ${!isReady ? `
                    <p class="text-xs text-gray-400 mt-1">
                        ${r.missing_main?.length ? `<span class="text-red-400">Missing: ${r.missing_main.join(', ')}</span>` : ''}
                        ${r.missing_optional?.length ? ` · Optional: ${r.missing_optional.join(', ')}` : ''}
                    </p>
                ` : ''}
            </div>
            ${favBtn}
        </div>
    `;
}

async function toggleFavourite(recipeId, isFav) {
    const url = `/user/favourites/${recipeId}`;
    const method = isFav ? "DELETE" : "POST";

    const response = await fetch(url, {
        method: method,
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            "Content-Type": "application/json",
            "Accept": "application/json",
        },
        body: JSON.stringify({})
    });

    const data = await response.json();

    if (response.ok && data.success) {
        if (isFav) {
            userFavourites = userFavourites.filter(id => id !== recipeId);
            showToast('💔 Removed from favourites!');
        } else {
            userFavourites.push(recipeId);
            showToast('❤️ Added to favourites!');
        }
        fetchRecipes(currentPage);
    } else {
        showToast(data.message || '❌ Failed to update favourites', false);
    }
}

async function fetchRecipes(page = 1) {
    const ingredients = document.getElementById('ingredients').value;

    const response = await fetch("/api/recommendations", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
        },
        body: JSON.stringify({ ingredients, page })
    });

    const data = await response.json();

    totalPages = data.total_pages || 1;
    currentPage = data.page || 1;
    document.getElementById('currentPage').textContent = currentPage;

    const resultsDiv = document.getElementById('results');
    resultsDiv.innerHTML = '';

    if (data.ready?.length > 0) {
        data.ready.forEach(r => {
            resultsDiv.innerHTML += renderRecipeCard(r, "ready");
        });
    }

    if (data.suggested?.length > 0) {
        data.suggested.forEach(r => {
            resultsDiv.innerHTML += renderRecipeCard(r, "suggested");
        });
    }

    if ((!data.ready?.length) && (!data.suggested?.length)) {
        resultsDiv.innerHTML = `
            <div class="text-center py-16">
                <div class="text-5xl mb-4">😢</div>
                <p class="text-gray-500 text-sm">No recipes found. Try different ingredients!</p>
            </div>
        `;
    }

    const paginationDiv = document.getElementById('pagination');
    paginationDiv.classList.toggle('hidden', totalPages <= 1);
    document.getElementById('prevPage').disabled = currentPage <= 1;
    document.getElementById('nextPage').disabled = currentPage >= totalPages;
}

document.getElementById('ingredientForm').addEventListener('submit', function(e) {
    e.preventDefault();
    currentPage = 1;
    fetchRecipes(currentPage);
});

document.getElementById('prevPage').addEventListener('click', () => {
    if (currentPage > 1) fetchRecipes(--currentPage);
});

document.getElementById('nextPage').addEventListener('click', () => {
    if (currentPage < totalPages) fetchRecipes(++currentPage);
});