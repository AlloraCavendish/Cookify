function showToast(message, success = true) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `fixed top-4 right-4 md:top-6 md:right-6 z-50 px-4 md:px-5 py-2.5 md:py-3 rounded-xl shadow-lg text-xs md:text-sm font-medium text-white transition-all max-w-xs ${success ? 'bg-green-500' : 'bg-red-500'}`;
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
            const card = document.getElementById(`fav-${recipeId}`);
            card.style.transition = 'opacity 0.3s';
            card.style.opacity = '0';
            setTimeout(() => {
                card.remove();

                const list = document.getElementById('favourites-list');
                if (list && list.children.length === 0) {
                    list.outerHTML = `
                        <div class="bg-white rounded-2xl border border-orange-100 p-8 md:p-12 text-center shadow-sm">
                            <div class="text-4xl md:text-5xl mb-4">🍽️</div>
                            <p class="text-gray-500 text-sm">You don't have any favourite recipes yet.</p>
                            <a href="${window.cookify.searchUrl}"
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