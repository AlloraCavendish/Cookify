@php
    use Illuminate\Support\Str;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Recipes</title>
</head>
<body style="font-family: Arial; max-width: 900px; margin:auto; padding:20px;">
    <h1>📖 Manage Recipes</h1>

    <a href="{{ route('admin.recipes.create') }}">➕ Add New Recipe</a>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Ingredients</th>
            <th>Description</th>
            <th>Difficulty</th>
            <th>Cuisine</th>
            <th>Image</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        @foreach($recipes as $recipe)
        <tr>
            <td>{{ $recipe->id }}</td>
            <td>{{ $recipe->title }}</td>
            <td>
                @if($recipe->ingredients->count() > 0)
                    <ul style="padding-left: 15px; margin:0;">
                        @foreach($recipe->ingredients as $ingredient)
                            <li>
                                {{ $ingredient->name }}
                                @if($ingredient->pivot->quantity)
                                    ({{ $ingredient->pivot->quantity }} {{ $ingredient->pivot->unit }})
                                @endif
                                @if($ingredient->type == 'optional')
                                    <span style="color: gray;">(optional)</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <span style="color: gray;">No Ingredients</span>
                @endif
            </td>
            <td>{{ \Illuminate\Support\Str::limit($recipe->description, 40) }}</td>
            <td>{{ $recipe->difficulty ?? '-' }}</td>
            <td>{{ $recipe->cuisine ?? '-' }}</td>
            <td>
                @if($recipe->image)
                    <a href="{{ asset('storage/' . $recipe->image) }}" target="_blank">
                        <img src="{{ asset('storage/' . $recipe->image) }}" alt="Recipe Image" width="60" style="border-radius:5px;">
                    </a>
                @else
                    <span style="color: gray;">No Image</span>
                @endif
            </td>
            <td>{{ $recipe->created_at->diffForHumans() }}</td>
            <td>
                <a href="{{ route('admin.recipes.edit', $recipe) }}">✏ Edit</a> | 
                <form action="{{ route('admin.recipes.destroy', $recipe) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Delete this recipe?')">🗑 Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>


    <div style="margin-top: 10px;">
        {{ $recipes->links() }}
    </div>

    <a href="{{ route('dashboard') }}">⬅ Back to Dashboard</a>
</body>
</html>
