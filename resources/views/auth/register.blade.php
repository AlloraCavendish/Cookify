<!DOCTYPE html>
<html>
<head>
    <title>Register - Cookify</title>
</head>
<body>
    <h1>Register</h1>

    @if ($errors->any())
        <div style="color:red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/register">
        @csrf
        <input type="text" name="name" placeholder="Name" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <input type="password" name="password_confirmation" placeholder="Confirm Password" required><br><br>
        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="/login">Login here</a></p>
</body>
</html>
