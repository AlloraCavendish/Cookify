<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cookify: Smart Recipe Recommendation System</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 900px; margin: auto; padding: 20px;">

    <!-- 🔹 Navigation -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2>🍳 Cookify</h2>
        <div>
            @auth
                <a href="{{ route('dashboard') }}" style="margin-right:10px;">Dashboard</a>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" style="padding:5px 10px;">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" style="margin-right:10px;">Login</a>
                <a href="{{ route('register') }}">Register</a>
            @endauth
        </div>
    </div>

    <!-- 🔹 Search Section -->
    <h1 style="text-align:center;">Find Recipes from Your Fridge</h1>

    <form id="ingredientForm" style="text-align:center; margin-bottom:20px;">
        <input type="text" id="ingredients" placeholder="Enter ingredients (comma separated)" style="width:60%; padding:8px;" required>
        <button type="submit" style="padding:8px 16px;">Search</button>
    </form>

    <div id="results"></div>

    <div id="pagination" style="text-align:center; margin-top:20px; display:none;">
        <button id="prevPage" style="padding:5px 10px;">Previous</button>
        <span id="currentPage" style="margin: 0 10px;">1</span>
        <button id="nextPage" style="padding:5px 10px;">Next</button>
    </div>

    <script>
        const isAuthenticated = @json(auth()->check());
        const userFavourites = @json($favourites ?? []);
        let currentPage = 1;
        let totalPages = 1;

        function renderRecipeCard(r, type = "ready") {
            let cardColor = type === "ready" ? "green" : "orange";
            let bgColor   = type === "ready" ? "#e8f5e9" : "#fff3e0";

            // Check if recipe already favourited
            let isFav = userFavourites.includes(r.id);
            let favBtn = "";

            if (isAuthenticated) {
                favBtn = `
                    <form method="POST" action="/favourites/${r.id}" style="display:inline; margin-left:10px;" 
                        onsubmit="event.preventDefault(); toggleFavourite(${r.id}, ${isFav});">
                        <button type="submit" style="background:none; border:none; color:${isFav ? 'darkred' : 'red'}; cursor:pointer;">
                            ${isFav ? '💔 Remove' : '❤️ Save'}
                        </button>
                    </form>
                `;
            }

            return `
                <div style="border:1px solid ${cardColor}; padding:10px; margin-bottom:10px; border-radius:5px; background:${bgColor};">
                    <strong>${r.title}</strong>
                    ${favBtn}
                </div>
            `;
        }

        async function toggleFavourite(recipeId, isFav) {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const url = `/favourites/${recipeId}`;
            const method = isFav ? "DELETE" : "POST";

            const response = await fetch(url, {
                method: method,
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                },
                body: JSON.stringify({}) // required for POST/DELETE to avoid 419
            });

            if (response.ok) {
                // Update local list
                if (isFav) {
                    userFavourites = userFavourites.filter(id => id !== recipeId);
                    alert("💔 Removed from favourites!");
                } else {
                    userFavourites.push(recipeId);
                    alert("❤️ Added to favourites!");
                }
                fetchRecipes(currentPage); // refresh list
            } else {
                alert("❌ Failed to update favourites");
            }
        }

        async function fetchRecipes(page = 1) {
            const ingredients = document.getElementById('ingredients').value;

            const response = await fetch("http://cookify.test/api/recommendations", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                },
                body: JSON.stringify({ ingredients: ingredients, page: page })
            });

            const data = await response.json();

            totalPages = data.total_pages || 1;
            currentPage = data.page || 1;
            document.getElementById('currentPage').textContent = currentPage;

            const resultsDiv = document.getElementById('results');
            resultsDiv.innerHTML = '';

            // ✅ Ready recipes
            if (data.ready && data.ready.length > 0) {
                resultsDiv.innerHTML += '<h2 style="color:green;">✅ Ready to Cook</h2>';
                data.ready.forEach(r => {
                    resultsDiv.innerHTML += renderRecipeCard(r, "ready");
                });
            }

            // ✅ Suggested recipes
            if (data.suggested && data.suggested.length > 0) {
                resultsDiv.innerHTML += '<h2 style="color:orange;">⚠️ Almost Ready (Buy These)</h2>';
                data.suggested.forEach(r => {
                    let missingHtml = '';
                    if (r.missing_main && r.missing_main.length > 0) {
                        missingHtml += `<div><strong>Main:</strong> ${r.missing_main.join(', ')}</div>`;
                    }
                    if (r.missing_optional && r.missing_optional.length > 0) {
                        missingHtml += `<div><strong>Optional:</strong> ${r.missing_optional.join(', ')}</div>`;
                    }

                    resultsDiv.innerHTML += renderRecipeCard(r, "suggested") + missingHtml;
                });
            }

            // ❌ No recipes found
            if ((!data.ready || !data.ready.length) && (!data.suggested || !data.suggested.length)) {
                resultsDiv.innerHTML = '<p style="text-align:center;">No recipes found 😢</p>';
            }

            // ✅ Show/hide pagination
            const paginationDiv = document.getElementById('pagination');
            paginationDiv.style.display = totalPages > 1 ? 'block' : 'none';

            // Disable buttons if on first/last page
            document.getElementById('prevPage').disabled = currentPage <= 1;
            document.getElementById('nextPage').disabled = currentPage >= totalPages;
        }

        document.getElementById('ingredientForm').addEventListener('submit', function(e) {
            e.preventDefault();
            currentPage = 1; // reset to first page on new search
            fetchRecipes(currentPage);
        });

        document.getElementById('prevPage').addEventListener('click', function() {
            if (currentPage > 1) fetchRecipes(--currentPage);
        });

        document.getElementById('nextPage').addEventListener('click', function() {
            if (currentPage < totalPages) fetchRecipes(++currentPage);
        });
    </script>
</body>
</html>
