<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Ingredient</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body style="font-family: Arial, sans-serif; max-width: 800px; margin:auto; padding:20px;">

<h1>➕ Add New Ingredient</h1>

@if($errors->any())
    <div style="color:red;">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.ingredients.store') }}" method="POST" id="ingredient-form">
    @csrf

    <div>
        <label>Name:</label><br>
        <input type="text" name="name" id="name" value="{{ old('name') }}" required style="width:100%;">
        <span id="name-feedback"></span>
    </div>

    <div>
        <label>Category:</label><br>

        <!-- Dropdown of existing categories -->
        <select name="category_dropdown" style="width:100%;">
            <option value="">-- Select Existing Category --</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ old('category_dropdown') == $cat ? 'selected' : '' }}>
                    {{ $cat }}
                </option>
            @endforeach
        </select>

        <br><br>

        <!-- Free text input for new category -->
        <input type="text" name="category_text" placeholder="Or enter new category" 
            value="{{ old('category_text') }}" style="width:100%;">
    </div>


    <div>
        <label>Type:</label><br>
        <select name="type" style="width:100%;">
            <option value="">-- Select Type --</option>
            <option value="main" {{ old('type') == 'main' ? 'selected' : '' }}>Main</option>
            <option value="optional" {{ old('type') == 'optional' ? 'selected' : '' }}>Optional</option>
        </select>
    </div>

    <div>
        <label>Is Spice:</label>
        <input type="checkbox" name="is_spice" value="1" {{ old('is_spice') ? 'checked' : '' }}>
    </div>

    <button type="submit" id="submit-btn" style="margin-top:10px;">💾 Save</button>
    <a href="{{ route('admin.ingredients.index') }}">⬅ Cancel</a>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#name').on('input', function() {
        let name = $(this).val().trim();
        if(name.length === 0) {
            $('#name-feedback').text('');
            $('#submit-btn').prop('disabled', false);
            return;
        }

        $.ajax({
            url: "{{ route('admin.ingredients.checkName') }}",
            method: 'POST',
            data: { name: name, _token: $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                if(response.exists) {
                    $('#name-feedback').text('Ingredient name already exists!').addClass('error').removeClass('success');
                    $('#submit-btn').prop('disabled', true);
                } else {
                    $('#name-feedback').text('Name is available').addClass('success').removeClass('error');
                    $('#submit-btn').prop('disabled', false);
                }
            }
        });
    });
});
</script>

</body>
</html>
