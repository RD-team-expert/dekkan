<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'متجر الميني') - Mini Market</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@400;700&display=swap" rel="stylesheet">
    <link href="{{ asset('build/assets/app-DH7cDScs.css') }}" rel="stylesheet">
    <link href="{{ asset('css/form-styles.css') }}" rel="stylesheet">

    <script src="{{ asset('build/assets/app-T1DpEqax.js') }}" defer></script>
    <style>

        
        @media (max-width: 640px) {
            .mobile-nav-text {
                font-size: 0.65rem;
            }
            .mobile-header {
                padding: 0.5rem;
            }
            .mobile-search {
                max-width: 150px;
            }
        }
        
        /* Add flexible layout styles */
        .flex-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .flex-item {
            flex: 1 1 auto;
            min-width: 250px;
        }
        
        @media (max-width: 768px) {
            .flex-item {
                min-width: 100%;
            }
            
            .space-between-sm {
                justify-content: space-between;
            }
            
            .stack-on-mobile {
                flex-direction: column;
            }
        }
    </style>
</head>
<body class="bg-gray-100 font-arabic">
<!-- Header -->
<header class="bg-white shadow-md p-4 fixed top-0 left-0 w-full z-10 flex justify-between items-center mobile-header">
    <div class="flex items-center space-x-2">
        <h1 class="text-xl font-bold sm:text-xl text-lg">@yield('header', 'قائمة المنتجات')</h1>
        @auth
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-gray-600 hover:text-gray-800">
                    <svg class="w-6 h-6 sm:w-6 sm:h-6 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </form>
        @endauth
    </div>
    <div class="relative w-full max-w-xs mobile-search">
        <input type="text" id="search" placeholder="...البحث" class="w-full p-2 sm:p-2 p-1 rounded-md border-gray-300 shadow-sm pr-10 focus:outline-none text-sm"
            @yield('search-action', 'onkeyup="searchProducts(event)"')>
        <span class="absolute left-2 top-2 text-gray-500">
                <svg class="w-5 h-5 sm:w-5 sm:h-5 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7 7 0 1117 4a7 7 0 01-7 7 7 7 0 017 7z"></path>
                </svg>
            </span>
    </div>
</header>

<!-- Main Content -->
<main class="container mx-auto pt-20 pb-24 p-4 min-h-screen">
    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>

<!-- Bottom Navigation Bar -->
<nav class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 p-2 flex justify-around items-center shadow-md overflow-x-auto">
    <a href="{{ route('products.alerts') }}" class="text-gray-600 hover:text-gray-800 flex flex-col items-center min-w-[60px]">
        <svg class="w-6 h-6 sm:w-6 sm:h-6 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="block text-xs mobile-nav-text">نقص بالكميات</span>
    </a>
    <a href="{{ route('sales.create') }}" class="text-gray-600 hover:text-gray-800 flex flex-col items-center min-w-[60px]">
        <svg class="w-6 h-6 sm:w-6 sm:h-6 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span class="block text-xs mobile-nav-text">مبيع</span>
    </a>
    <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-800 flex flex-col items-center min-w-[60px]">
        <svg class="w-6 h-6 sm:w-6 sm:h-6 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
        </svg>
        <span class="block text-xs mobile-nav-text">قائمة المنتجات</span>
    </a>
    <a href="{{ route('purchases.index') }}" class="text-gray-600 hover:text-gray-800 flex flex-col items-center min-w-[60px]">
        <svg class="w-6 h-6 sm:w-6 sm:h-6 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18M3 7h18M3 12h18m-7 4h7"></path>
        </svg>
        <span class="block text-xs mobile-nav-text">شراء</span>
    </a>
    <a href="{{ route('sales.index') }}" class="text-gray-600 hover:text-gray-800 flex flex-col items-center min-w-[60px]">
        <svg class="w-6 h-6 sm:w-6 sm:h-6 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
        </svg>
        <span class="block text-xs mobile-nav-text">سجل المبيع</span>
    </a>
    <a href="{{ route('payment_receipts.index') }}" class="text-gray-600 hover:text-gray-800 flex flex-col items-center min-w-[80px]" dir="rtl">
        @svg('heroicon-o-currency-dollar', 'w-6 h-6 sm:w-6 sm:h-6 w-5 h-5')
        <span class="block text-xs mobile-nav-text">الماليات</span>
    </a>
</nav>

<!-- Floating Action Button -->


@push('scripts')
    <script>
        function searchProducts(event) {
            let query = event.target.value;
            console.log('Search query:', query);
            // Add AJAX or Livewire logic here to filter products
        }
    </script>
@endpush
@stack('scripts')
</body>
</html>
