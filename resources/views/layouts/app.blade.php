{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>@yield('title','Cosmetics Management')</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.tailwindcss.com"></script>

<style>
/* ====== Background (Light + Mauve/Pink Nude) ====== */
body{
    font-family: 'Poppins', sans-serif;
    color: #111827;

    background:
        radial-gradient(900px 520px at 15% 10%, rgba(168,85,247,.18), transparent 60%),
        radial-gradient(900px 520px at 85% 20%, rgba(244,114,182,.18), transparent 60%),
        radial-gradient(900px 520px at 50% 90%, rgba(236,72,153,.10), transparent 55%),
        linear-gradient(135deg, #fbf7ff, #fff5f9 55%, #f6f0ff);
    min-height: 100vh;
}

/* ====== Navbar Links ====== */
.nav-link{
    display:flex;
    align-items:center;
    gap:10px;
    padding:10px 18px;
    color: rgba(255,255,255,.95);
    border-radius:14px;
    transition: .25s ease;
}

.nav-link:hover{
    background: rgba(255,255,255,.18);
}

.active-nav{
    background: rgba(255,255,255,.26);
    box-shadow: inset 0 10px 24px rgba(0,0,0,.18);
}

/* ====== Cards (White + Glow) ====== */
.card-white{
    background: rgba(255,255,255,.92);
    border: 1px solid rgba(236,72,153,.12);
    border-radius: 22px;
    box-shadow:
        0 18px 45px rgba(17,24,39,.08),
        0 0 22px rgba(168,85,247,.14),
        0 0 22px rgba(244,114,182,.10);
    backdrop-filter: blur(10px);
}

.card-glow{
    box-shadow:
        0 22px 60px rgba(17,24,39,.10),
        0 0 36px rgba(168,85,247,.18),
        0 0 34px rgba(244,114,182,.16);
}

/* ====== Dropdown links ====== */
.menu-link{
    display:flex;
    gap:10px;
    padding:12px;
    border-radius:14px;
    color:#111827;
    transition:.2s;
}
.menu-link:hover{
    background: rgba(168,85,247,.10);
}

/* ====== Footer text ====== */
.footer-muted{
    color: rgba(17,24,39,.55);
}
</style>
</head>

<body>

<!-- ================= NAVBAR ================= -->
<nav class="sticky top-0 z-50 shadow-xl"
     style="background: linear-gradient(90deg, #6d28d9 0%, #a855f7 40%, #f472b6 100%);">
<div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">

<!-- Logo -->
<div class="flex items-center gap-3">
    <div class="w-11 h-11 rounded-full bg-white/20 flex items-center justify-center shadow">
        <i class="fas fa-spa text-white"></i>
    </div>
    <div>
        <h1 class="text-white text-xl font-bold">Cosmetic Hub</h1>
        <p class="text-xs text-white/80">Management System</p>
    </div>
</div>

<!-- Desktop Links -->
<div class="hidden md:flex gap-2">

<a href="{{ route('dashboard') }}"
   class="nav-link {{ request()->routeIs('dashboard')?'active-nav':'' }}">
<i class="fas fa-gauge-high"></i> Dashboard
</a>

<a href="{{ route('products.index') }}"
   class="nav-link {{ request()->routeIs('products.*')?'active-nav':'' }}">
<i class="fas fa-box"></i> Products
</a>

<a href="{{ route('categories.index') }}"
   class="nav-link {{ request()->routeIs('categories.*')?'active-nav':'' }}">
<i class="fas fa-tags"></i> Categories
</a>

<a href="{{ route('suppliers.index') }}"
   class="nav-link {{ request()->routeIs('suppliers.*')?'active-nav':'' }}">
<i class="fas fa-truck"></i> Suppliers
</a>

</div>

<!-- User Dropdown -->
<div class="relative group">

<button class="flex items-center gap-3 bg-white/15 px-4 py-2 rounded-xl">
    <div class="w-9 h-9 bg-white/25 rounded-full flex items-center justify-center">
        <i class="fas fa-user text-white"></i>
    </div>
    <div class="text-left">
        <p class="text-white text-sm font-semibold">{{ Auth::user()->name }}</p>
        <p class="text-xs text-white/80">Logged In</p>
    </div>
    <i class="fas fa-chevron-down text-white/80"></i>
</button>

<!-- Dropdown -->
<div class="absolute right-0 mt-3 w-64 card-white opacity-0 invisible group-hover:opacity-100 group-hover:visible transition">

<div class="p-4 border-b border-black/5 flex items-center gap-3">
    <div class="w-12 h-12 rounded-full"
         style="background: linear-gradient(135deg,#a855f7,#f472b6);">
        <div class="w-full h-full flex items-center justify-center">
            <i class="fas fa-user-cog text-white"></i>
        </div>
    </div>
    <div>
        <p class="font-bold text-gray-900">{{ Auth::user()->name }}</p>
        <p class="text-sm text-gray-500">Administrator</p>
    </div>
</div>

<div class="p-2">
<a href="{{ route('profile.edit') }}" class="menu-link">
    <i class="fas fa-user text-purple-600"></i> My Profile
</a>

<a href="{{ route('settings') }}" class="menu-link">
    <i class="fas fa-cog text-pink-500"></i> Settings
</a>
</div>

<div class="border-t border-black/5 p-2">
<form method="POST" action="{{ route('logout') }}">
@csrf
<button class="w-full menu-link text-red-600">
    <i class="fas fa-sign-out-alt"></i> Logout
</button>
</form>
</div>

</div>
</div>

</div>
</nav>
<!-- ================= END NAVBAR ================= -->

<!-- Flash Messages -->
<div class="max-w-7xl mx-auto px-6 mt-6 space-y-3">

@if(session('success'))
<div class="card-white card-glow px-5 py-3">
    <p class="font-semibold text-emerald-700">{{ session('success') }}</p>
</div>
@endif

@if(session('error'))
<div class="card-white card-glow px-5 py-3">
    <p class="font-semibold text-red-600">{{ session('error') }}</p>
</div>
@endif

@if($errors->any())
<div class="card-white card-glow px-5 py-3">
    <p class="font-semibold text-orange-600 mb-2">Please fix the following errors:</p>
    <ul class="list-disc ml-6 text-orange-700">
        @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
</div>
@endif

</div>

<!-- Page Content -->
<main class="max-w-7xl mx-auto px-6 py-8">
@yield('content')
@include('partials.quick-links')
</main>

<!-- Footer -->
<footer class="mt-14">
<div class="max-w-7xl mx-auto px-6 py-6 text-center footer-muted">
© {{ date('Y') }} Cosmetics Hub — All Rights Reserved
</div>
</footer>

</body>
</html>