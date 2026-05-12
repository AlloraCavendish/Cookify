<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Cookify</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-orange-50 min-h-screen flex items-center justify-center px-4 py-8">

    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-6 md:mb-8">
            <span class="text-4xl md:text-5xl">🍳</span>
            <h1 class="text-2xl md:text-3xl font-bold text-orange-900 mt-2">Cookify</h1>
            <p class="text-gray-500 text-sm mt-1">Create your account to get started</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-orange-100 p-6 md:p-8">

            {{-- Errors --}}
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                    <ul class="text-sm text-red-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="/register" class="space-y-4 md:space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" placeholder="Your full name"
                        value="{{ old('name') }}" required maxlength="255"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent text-sm transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" placeholder="you@example.com"
                        value="{{ old('email') }}" required maxlength="255"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent text-sm transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" id="password" placeholder="••••••••" required minlength="8"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent text-sm transition">
                    <p class="text-xs text-gray-400 mt-1">Minimum 8 characters</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••" required minlength="8"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent text-sm transition">
                    {{-- Password match indicator --}}
                    <p id="password-match" class="text-xs mt-1 hidden"></p>
                </div>

                <button type="submit"
                    class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2.5 rounded-xl transition text-sm mt-2">
                    Create Account
                </button>
            </form>
        </div>

        {{-- Bottom links --}}
        <div class="flex flex-col sm:flex-row items-center justify-between mt-6 gap-2 text-sm text-gray-500">
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-1.5 text-orange-600 hover:text-orange-800 font-medium transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Home
            </a>
            <p>Already have an account?
                <a href="/login" class="text-orange-600 hover:text-orange-800 font-medium transition">Login here</a>
            </p>
        </div>

    </div>

    <script>
        // Password match indicator
        const password = document.getElementById('password');
        const confirmation = document.getElementById('password_confirmation');
        const matchMsg = document.getElementById('password-match');

        confirmation.addEventListener('input', function () {
            if (confirmation.value === '') {
                matchMsg.classList.add('hidden');
                return;
            }
            matchMsg.classList.remove('hidden');
            if (password.value === confirmation.value) {
                matchMsg.textContent = '✅ Passwords match';
                matchMsg.className = 'text-xs mt-1 text-green-500';
            } else {
                matchMsg.textContent = '❌ Passwords do not match';
                matchMsg.className = 'text-xs mt-1 text-red-500';
            }
        });
    </script>

</body>
</html>