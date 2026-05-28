@extends('layouts.app')
@section('title', 'Manajemen User')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen User</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola akun pengguna semua cabang</p>
    </div>
    <a href="{{ route('users.create') }}"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">
        + Tambah User
    </a>
</div>

{{-- Owner --}}
@php
    $owner = $users->filter(fn($u) => $u->hasRole('owner'));
    $grouped = $users->filter(fn($u) => !$u->hasRole('owner'))->groupBy('store_id');
@endphp

{{-- Kartu Owner --}}
@if($owner->count() > 0)
<div class="mb-6">
    <div class="flex items-center gap-2 mb-3">
        <span class="bg-purple-600 text-white text-xs font-bold px-3 py-1 rounded-full">👑 OWNER</span>
    </div>
    <div class="bg-white rounded-xl border border-purple-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100 bg-purple-50">
                    <th class="px-5 py-3 font-medium">Nama</th>
                    <th class="px-5 py-3 font-medium">Email</th>
                    <th class="px-5 py-3 font-medium">Role</th>
                    <th class="px-5 py-3 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($owner as $user)
                <tr class="border-b border-gray-50 hover:bg-purple-50">
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $user->name }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $user->email }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700 capitalize">
                            Owner
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('users.edit', $user) }}"
                                class="text-blue-600 hover:text-blue-800 text-xs font-medium">Edit</a>
                            {{-- Tidak ada tombol hapus untuk owner --}}
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Per Cabang --}}
@foreach($grouped as $storeId => $storeUsers)
@php $store = $storeUsers->first()->store; @endphp
<div class="mb-6">
    {{-- Header Cabang --}}
    <div class="flex items-center gap-2 mb-3">
        <span class="bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full">
            🏪 {{ $store->name ?? 'Tanpa Cabang' }}
        </span>
        <span class="text-xs text-gray-400">{{ $store->city ?? '' }}</span>
        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">
            {{ $storeUsers->count() }} pegawai
        </span>
    </div>

    <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100 bg-blue-50">
                    <th class="px-5 py-3 font-medium">Nama</th>
                    <th class="px-5 py-3 font-medium">Email</th>
                    <th class="px-5 py-3 font-medium">Role</th>
                    <th class="px-5 py-3 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($storeUsers as $user)
                <tr class="border-b border-gray-50 hover:bg-blue-50">
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $user->name }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $user->email }}</td>
                    <td class="px-5 py-3">
                        @php $role = $user->getRoleNames()->first(); @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-medium capitalize
                            {{ $role == 'manajer' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $role == 'supervisor' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $role == 'kasir' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $role == 'gudang' ? 'bg-orange-100 text-orange-700' : '' }}">
                            {{ $role }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('users.edit', $user) }}"
                                class="text-blue-600 hover:text-blue-800 text-xs font-medium">Edit</a>
                            <form method="POST" action="{{ route('users.destroy', $user) }}"
                                onsubmit="return confirm('Yakin hapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach

@endsection