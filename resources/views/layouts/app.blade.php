<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Market Jayusman - @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-blue-700 text-white shadow-md fixed top-0 left-0 right-0 z-50">
        <div class="max-w-full mx-auto px-4">
            <div class="flex justify-between h-14 items-center">
                <div class="flex items-center gap-3">
                    <span class="font-bold text-lg">🛒 Mini Market Jayusman</span>
                    @if(auth()->user()->store)
                        <span class="text-blue-200 text-sm hidden md:block">
                            — {{ auth()->user()->store->name }}
                        </span>
                    @endif
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-blue-200 capitalize">{{ auth()->user()->getRoleNames()->first() }}</p>
                    </div>
                    <span class="text-xs bg-blue-500 px-2 py-1 rounded-full capitalize md:hidden">
                        {{ auth()->user()->getRoleNames()->first() }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="text-sm bg-blue-600 hover:bg-blue-500 px-3 py-1.5 rounded-lg border border-blue-500">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex pt-14">
        <!-- Sidebar -->
        <aside class="w-56 min-h-screen bg-white shadow-sm border-r border-gray-200 fixed top-14 left-0 bottom-0 overflow-y-auto">
            <div class="p-4 border-b border-gray-100">
                <p class="text-xs text-gray-400 uppercase font-semibold tracking-wider">Menu</p>
            </div>
            <nav class="p-3 space-y-1">

                @if(auth()->user()->hasRole('owner'))
                <a href="{{ route('owner.dashboard') }}"
                    class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium transition
                    {{ request()->routeIs('owner.dashboard') ? 'bg-blue-50 text-blue-700 border border-blue-100' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span>📊</span> Dashboard
                </a>
                @endif

                @if(auth()->user()->hasAnyRole(['manajer','supervisor']))
                <a href="{{ route('toko.dashboard') }}"
                    class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium transition
                    {{ request()->routeIs('toko.dashboard') ? 'bg-blue-50 text-blue-700 border border-blue-100' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span>📊</span> Dashboard Toko
                </a>
                @endif

                @if(auth()->user()->hasAnyRole(['kasir','supervisor','manajer']))
                <a href="{{ route('transaksi.index') }}"
                    class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium transition
                    {{ request()->routeIs('transaksi.*') ? 'bg-blue-50 text-blue-700 border border-blue-100' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span>🧾</span> Transaksi POS
                </a>
                @endif

                @if(auth()->user()->hasAnyRole(['gudang','supervisor','manajer']))
                <a href="{{ route('stok.index') }}"
                    class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium transition
                    {{ request()->routeIs('stok.*') ? 'bg-blue-50 text-blue-700 border border-blue-100' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span>📦</span> Stok Barang
                </a>
                @endif

                @if(auth()->user()->hasAnyRole(['owner','manajer']))
                <a href="{{ route('laporan.index') }}"
                    class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium transition
                    {{ request()->routeIs('laporan.*') ? 'bg-blue-50 text-blue-700 border border-blue-100' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span>📋</span> Laporan
                </a>
                @endif

                @if(auth()->user()->hasRole('owner'))
                <a href="{{ route('users.index') }}"
                    class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium transition
                    {{ request()->routeIs('users.*') ? 'bg-blue-50 text-blue-700 border border-blue-100' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span>👥</span> Manajemen User
                </a>
                @endif

            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-56 p-6 min-h-screen">
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm flex items-center gap-2">
                    <span>✅</span> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm flex items-center gap-2">
                    <span>❌</span> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>