<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Recipe - {{ $recipe->title }}</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
</head>
<body style="font-family: Arial; max-width: 800px; margin:auto; padding:20px;">

<h1>✏️ Edit Recipe</h1>

@if($errors->any())
    <div style="color:red;">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.recipes.update', $recipe->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div>
        <label>Title:</label><br>
        <input type="text" name="title" value="{{ old('title', $recipe->title) }}" required style="width:100%;">
    </div>

    {{-- INGREDIENTS --}}
    <div style="margin-top:15px;">
        <label>Ingredients:</label><br>

        <!-- ✅ Container for all ingredient rows -->
        <div id="ingredients-container">
            @foreach($recipe->ingredients as $i => $ingredient)
                <div class="ingredient-row" style="margin-bottom:8px;">
                    <input type="text" 
                           class="ingredient-search" 
                           placeholder="🔍 Search ingredient..." 
                           value="{{ $ingredient->name }}" 
                           style="width:200px;">
                    <input type="hidden" 
                           name="ingredients[{{ $i }}][id]" 
                           class="ingredient-id" 
                           value="{{ $ingredient->id }}">

                    <input type="text" 
                        name="ingredients[{{ $i }}][quantity]" 
                        placeholder="Qty" 
                        style="width:70px;" 
                        required
                        pattern="^(\d+(\.\d+)?|\d+\s\d+\/\d+|\d+\/\d+)$"
                        oninput="this.value = this.value.replace(/[^0-9\/\.]/g, '')"
                        value="{{ $ingredient->pivot->quantity }}"
                    >

                    <select name="ingredients[{{ $i }}][unit]" style="width:90px;" required>
                        <option value="">-- Unit --</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit }}" {{ $ingredient->pivot->unit == $unit ? 'selected' : '' }}>
                                {{ $unit }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Ingredient Type -->
                    <select name="ingredients[{{ $i }}][type]" style="width:110px;" required>
                        <option value="main" {{ $ingredient->pivot->type == 'main' ? 'selected' : '' }}>Main</option>
                        <option value="optional" {{ $ingredient->pivot->type == 'optional' ? 'selected' : '' }}>Optional</option>
                    </select>

                    <button type="button" onclick="removeRow(this)">❌</button>
                </div>
            @endforeach
        </div>
        <!-- ✅ End container -->

        <button type="button" onclick="addRow()" style="margin-top:5px;">➕ Add Ingredient</button>
    </div>

    <div style="margin-top:15px;">
        <label>Description:</label><br>
        <textarea name="description" rows="3" style="width:100%;">{{ old('description', $recipe->description) }}</textarea>
    </div>

    <div>
        <label>Steps:</label><br>
        <textarea name="steps" rows="4" style="width:100%;">{{ old('steps', $recipe->steps) }}</textarea>
    </div>

    <div>
        <label>Cooking Time (minutes):</label><br>
        <input type="number" name="cooking_time" value="{{ old('cooking_time', $recipe->cooking_time) }}" style="width:100%;">
    </div>

    <div>
        <label>Difficulty:</label><br>
        <select name="difficulty" style="width:100%;">
            <option value="">-- Select Difficulty --</option>
            <option value="Easy" {{ old('difficulty', $recipe->difficulty)=='Easy' ? 'selected' : '' }}>Easy</option>
            <option value="Medium" {{ old('difficulty', $recipe->difficulty)=='Medium' ? 'selected' : '' }}>Medium</option>
            <option value="Hard" {{ old('difficulty', $recipe->difficulty)=='Hard' ? 'selected' : '' }}>Hard</option>
        </select>
    </div>

    <div>
        <label>Cuisine:</label><br>
        <input type="text" name="cuisine" value="{{ old('cuisine', $recipe->cuisine) }}" style="width:100%;">
    </div>

    <div>
        <label>Recipe Image:</label><br>
        @if($recipe->image)
            <p>Current: <img src="{{ asset('storage/'.$recipe->image) }}" alt="Recipe Image" style="height:80px;"></p>
        @endif
        <input type="file" name="image" accept="image/*">
    </div>

    <button type="submit" style="margin-top:10px;">💾 Update</button>
    <a href="{{ route('admin.recipes.index') }}">⬅ Cancel</a>
</form>

<!-- Load jQuery FIRST, then jQuery UI -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<script>
$(document).ready(function () {
    let rowCount = {{ $recipe->ingredients->count() }};

    function initAutocomplete(row) {
        $(row).find(".ingredient-search").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "{{ route('admin.ingredients.search') }}",
                    data: { q: request.term },
                    success: function (data) {
                        response($.map(data, function (item) {
                            return { label: item.name, value: item.name, id: item.id };
                        }));
                    }
                });
            },
            minLength: 2,
            select: function (event, ui) {
                $(this).siblings(".ingredient-id").val(ui.item.id);
            }
        });
    }

    // Init for existing rows
    $(".ingredient-row").each(function () {
        initAutocomplete($(this));
    });

    // Add row dynamically
    window.addRow = function() {
        const container = $("#ingredients-container");
        const newRow = container.children().first().clone();

        // Reset inputs
        newRow.find("input").val("");
        newRow.find("select").val("");

        // Update names
        newRow.find("input, select").each(function () {
            this.name = this.name.replace(/\d+/, rowCount);
        });

        container.append(newRow);
        initAutocomplete(newRow);
        rowCount++;
    };

    // Remove row
    window.removeRow = function(btn) {
        if ($(".ingredient-row").length > 1) {
            $(btn).closest(".ingredient-row").remove();
        }
    };
});
</script>

</body>
</html>
