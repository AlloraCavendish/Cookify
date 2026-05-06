<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Ingredients</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 900px; margin:auto; padding:20px;">
    <h1>🌿 Manage Ingredients</h1>

    <a href="{{ route('admin.ingredients.create') }}">➕ Add New Ingredient</a>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <table border="1" cellpadding="8" cellspacing="0" width="100%" style="margin-top:10px;">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Type</th>
            <th>Is Spice</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        @foreach($ingredients as $ingredient)
        <tr>
            <td>{{ $ingredient->id }}</td>
            <td>{{ $ingredient->name }}</td>
            <td>{{ $ingredient->category ?? '-' }}</td>
            <td>{{ $ingredient->type ?? '-' }}</td>
            <td>{{ $ingredient->is_spice ? 'Yes' : 'No' }}</td>
            <td>{{ $ingredient->created_at->diffForHumans() }}</td>
            <td>
                <a href="{{ route('admin.ingredients.edit', $ingredient) }}">✏ Edit</a> | 
                <form action="{{ route('admin.ingredients.destroy', $ingredient) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Delete this ingredient?')">🗑 Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>

    <div style="margin-top:10px;">
        {{ $ingredients->links() }}
    </div>

    <a href="{{ route('admin.dashboard') }}">⬅ Back to Dashboard</a>
</body>
</html>
