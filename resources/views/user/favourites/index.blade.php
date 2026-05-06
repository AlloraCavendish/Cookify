<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Favourite Recipes</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 800px; margin:auto; padding:20px;">
    <h1>💖 My Favourite Recipes</h1>

    @if($favourites->isEmpty())
        <p>You don’t have any favourite recipes yet.</p>
    @else
        <ul>
            @foreach($favourites as $fav)
                <li style="margin-bottom:10px;">
                    <strong>{{ optional($fav->recipe)->title ?? 'Recipe not available' }}</strong>
                    <small style="color: gray;">
                        (added {{ $fav->created_at->diffForHumans() }})
                    </small>

                    <form action="{{ route('favourites.destroy', $fav->recipe_id) }}" 
                          method="POST" 
                          style="display:inline; margin-left:10px;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">❌ Remove</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif

    <a href="{{ route('dashboard') }}" class="btn btn-primary">⬅ Back to Dashboard</a>
</body>
</html>
