<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Mini Market') }} - @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    <span class="text-blue-600 font-bold text-xl">Mini Market</span>
                    @if(auth()->user()->store)
                        <span class="text-sm text-gray-500">— {{ auth()->user()->store->name }}</span>
                    @endif
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full capitalize">
                        {{ auth()->user()->getRoleNames()->first() }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-500 hover:text-red-700">Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-56 min-h-screen bg-white shadow-sm border-r border-gray-200 pt-6">
            <nav class="px-3 space-y-1">
                @if(auth()->user()->hasRole('owner'))
                    <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('owner.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">
                        Dashboard Owner
                    </a>
                @endif

                @if(auth()->user()->hasAnyRole(['manajer','supervisor']))
                    <a href="{{ route('toko.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('toko.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">
                        Dashboard Toko
                    </a>
                @endif

                @if(auth()->user()->hasAnyRole(['kasir','supervisor','manajer']))
                    <a href="{{ route('transaksi.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('transaksi.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">
                        Transaksi POS
                    </a>
                @endif

                @if(auth()->user()->hasAnyRole(['gudang','supervisor','manajer']))
                    <a href="{{ route('stok.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('stok.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">
                        Stok Barang
                    </a>
                @endif

                @if(auth()->user()->hasAnyRole(['owner','manajer']))
                    <a href="{{ route('laporan.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('laporan.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">
                        Laporan
                    </a>
                @endif

                @if(auth()->user()->hasRole('owner'))
                    <a href="{{ route('users.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('users.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">
                        Manajemen User
                    </a>
                @endif
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>