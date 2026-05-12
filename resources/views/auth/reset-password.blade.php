<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Cookify</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-orange-50 min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md">

        <div class="text-center mb-8">
            <span class="text-4xl md:text-5xl">🍳</span>
            <h1 class="text-2xl md:text-3xl font-bold text-orange-900 mt-2">Reset Password</h1>
            <p class="text-gray-500 text-sm mt-1">Enter your new password below</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-orange-100 p-6 md:p-8">

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                    <ul class="text-sm text-red-600 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" placeholder="you@example.com"
                        value="{{ old('email') }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent text-sm transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input type="password" name="password" placeholder="••••••••" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent text-sm transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" placeholder="••••••••" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent text-sm transition">
                </div>

                <button type="submit"
                    class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2.5 rounded-xl transition text-sm">
                    Reset Password
                </button>
            </form>
        </div>

    </div>

</body>
</html>