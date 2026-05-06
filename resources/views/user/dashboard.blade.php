<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard - Cookify</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 900px; margin: auto; padding: 20px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2>👤 User Dashboard</h2>
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" style="padding:5px 10px;">Logout</button>
        </form>
    </div>

    <h1>Welcome, {{ Auth::user()->name }}!</h1>

    <h2 style="color:darkgreen;">Your Panel</h2>
    <ul>
        <li><a href="{{ route('favourites.index') }}">❤️ My Favourites</a></li>
        <li><a href="{{ route('search') }}">🔍 Back to Search</a></li>
    </ul>
</body>
</html>
