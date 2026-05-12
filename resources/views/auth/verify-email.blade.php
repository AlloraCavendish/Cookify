<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Cookify</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-orange-50 min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md">

        <div class="text-center mb-6 md:mb-8">
            <span class="text-4xl md:text-5xl">🍳</span>
            <h1 class="text-2xl md:text-3xl font-bold text-orange-900 mt-2">Verify Your Email</h1>
            <p class="text-gray-500 text-sm mt-1">One more step before you start cooking!</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-orange-100 p-6 md:p-8 text-center">

            @if(session('status') == 'verification-link-sent' || session('status'))
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                    <p class="text-sm text-green-600">
                        ✅ A fresh verification link has been sent to your email!
                    </p>
                </div>
            @endif

            <div class="text-5xl mb-4">📧</div>

            <p class="text-gray-600 text-sm mb-2">
                We sent a verification link to
            </p>
            <p class="font-semibold text-orange-800 text-sm mb-6">
                {{ Auth::user()->email }}
            </p>
            <p class="text-gray-400 text-xs mb-8">
                Click the link in the email to verify your account. Check your spam folder if you don't see it.
            </p>

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                    class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2.5 rounded-xl transition text-sm">
                    Resend Verification Email
                </button>
            </form>
        </div>

        <div class="text-center mt-6">
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 transition">
                    Logout
                </button>
            </form>
        </div>

    </div>

</body>
</html>