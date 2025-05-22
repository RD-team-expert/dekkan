<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'تسجيل الدخول - متجر الميني')</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@400;700&display=swap" rel="stylesheet">
    <link href="{{ asset('build/assets/app-DH7cDScs.css') }}" rel="stylesheet">
    <script src="{{ asset('build/assets/app-T1DpEqax.js') }}" defer></script>
    <style>
        @media (max-width: 640px) {
            .mobile-header {
                padding: 0.5rem;
            }
            .mobile-title {
                font-size: 1rem;
            }
        }

        /* Centered form styling */
        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 4rem); /* Adjust for header */
        }

        .login-card {
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            width: 100%;
            max-width: 400px;
        }

        .form-input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
        }

        .form-button {
            width: 100%;
            background-color: #2563eb;
            color: #ffffff;
            padding: 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background-color 0.2s ease;
        }

        .form-button:hover {
            background-color: #1d4ed8;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        .link-text {
            color: #2563eb;
            font-size: 0.875rem;
        }

        .link-text:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="bg-gray-100 font-arabic">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="text-xl font-bold text-gray-800">متجر الميني</a>
            </div>
            <div>
                @hasSection('search-action')
                    <form action="@yield('search-action')" method="GET" class="hidden md:block">
                        <input type="text" name="q" placeholder="بحث..." class="px-3 py-1 border rounded-md">
                        <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded-md ml-2">بحث</button>
                    </form>
                @endif
            </div>
        </div>
    </header>

    <!-- Page Title -->
    <div class="bg-gray-50 border-b">
        <div class="container mx-auto px-4 py-3">
            <h1 class="text-2xl font-bold text-gray-800">@yield('header')</h1>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-auto">
        <div class="container mx-auto px-4 py-4 text-center text-gray-600">
            <p>© {{ date('Y') }} متجر الميني - جميع الحقوق محفوظة</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
