<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Ingredient</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 800px; margin:auto; padding:20px;">

<h1>✏ Edit Ingredient</h1>

@if($errors->any())
    <div style="color:red;">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.ingredients.update', $ingredient) }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label>Name:</label><br>
        <input type="text" name="name" value="{{ old('name', $ingredient->name) }}" required style="width:100%;">
    </div>

    <div>
        <label>Category:</label>
        <select name="category" style="width:100%;">
            <option value="">-- Select Category --</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ (old('category') ?? $ingredient->category) === $cat ? 'selected' : '' }}>
                    {{ ucfirst($cat) }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label>Type:</label>
        <select name="type" style="width:100%;">
            <option value="">-- Select Type --</option>
            @foreach($types as $t)
                <option value="{{ $t }}" {{ (old('type') ?? $ingredient->type) === $t ? 'selected' : '' }}>
                    {{ ucfirst($t) }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label>Is Spice:</label>
        <input type="checkbox" name="is_spice" value="1" {{ old('is_spice', $ingredient->is_spice) ? 'checked' : '' }}>
    </div>

    <button type="submit" style="margin-top:10px;">💾 Update</button>
    <a href="{{ route('admin.ingredients.index') }}">⬅ Cancel</a>
</form>

</body>
</html>
