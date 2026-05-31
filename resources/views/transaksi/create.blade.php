@extends('layouts.app')
@section('title', 'Transaksi Baru')

@section('content')
<div class="mb-4 flex items-center justify-between">
    <h1 class="text-xl font-bold text-gray-800">Transaksi Baru</h1>
    <a href="{{ route('transaksi.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali</a>
</div>

<div class="flex gap-4">

    {{-- KIRI: Produk --}}
    <div class="flex-1 bg-white rounded-xl border border-gray-200 p-4">

        {{-- Search --}}
        <input type="text" id="cari-produk" placeholder="Cari produk..."
            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm mb-4 focus:outline-none focus:ring-2 focus:ring-blue-300">

        {{-- Kategori Filter --}}
        <div class="flex gap-2 flex-wrap mb-4" id="kategori-filter">
            <button onclick="filterKategori('semua', this)"
                class="kategori-btn px-4 py-1.5 rounded-full text-sm font-medium bg-blue-600 text-white">
                Semua
            </button>
            @php
                $kategoris = $products->pluck('category')->unique()->filter()->values();
            @endphp
            @foreach($kategoris as $kat)
            <button onclick="filterKategori('{{ $kat }}', this)"
                class="kategori-btn px-4 py-1.5 rounded-full text-sm font-medium bg-gray-100 text-gray-600 hover:bg-gray-200">
                {{ strtoupper($kat) }}
            </button>
            @endforeach
        </div>

        {{-- Grid Produk --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3" id="produk-grid">
            @foreach($products as $p)
            <div class="produk-card border border-gray-100 rounded-xl p-3 cursor-pointer hover:border-blue-300 hover:shadow-sm transition text-center"
                 data-id="{{ $p['id'] }}"
                 data-name="{{ $p['name'] }}"
                 data-price="{{ $p['sell_price'] }}"
                 data-stock="{{ $p['stock'] }}"
                 data-category="{{ $p['category'] }}"
                 onclick="tambahItem(this)">
                {{-- Ikon Produk --}}
                <div class="w-14 h-14 mx-auto mb-2 bg-blue-50 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">
                        @if(in_array($p['category'], ['Makanan', 'Snack']))🍜
                        @elseif($p['category'] == 'Minuman')🥤
                        @elseif($p['category'] == 'Kebersihan')🧴
                        @elseif($p['category'] == 'Dapur')🫙
                        @else📦
                        @endif
                    </span>
                </div>
                <p class="text-xs font-semibold text-gray-800 leading-tight mb-1">{{ $p['name'] }}</p>
                <p class="text-xs text-gray-400 mb-1">Stok: {{ $p['stock'] }} {{ $p['unit'] ?? 'pcs' }}</p>
                <p class="text-xs font-bold text-blue-600">Rp {{ number_format($p['sell_price'], 0, ',', '.') }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- KANAN: Keranjang --}}
    <div class="w-80 flex-shrink-0">
        <div class="bg-white rounded-xl border border-gray-200 p-4 sticky top-20">

            {{-- Header Keranjang --}}
            <div class="flex items-center gap-2 mb-4 pb-3 border-b border-gray-100">
                <span class="text-lg">🛒</span>
                <h2 class="font-semibold text-gray-800">Keranjang</h2>
                <span id="keranjang-count" class="ml-auto text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full hidden">0</span>
            </div>

            {{-- Isi Keranjang --}}
            <div id="keranjang-empty" class="py-10 text-center">
                <span class="text-5xl block mb-3 opacity-30">🛒</span>
                <p class="text-sm text-gray-400 font-medium">Belum ada barang</p>
                <p class="text-xs text-gray-300 mt-1">Klik produk untuk menambahkan</p>
            </div>

            <div id="keranjang-items" class="space-y-2 mb-4 max-h-72 overflow-y-auto hidden"></div>

            {{-- Subtotal & Total --}}
            <div class="border-t border-gray-100 pt-3 space-y-1 mb-4">
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Subtotal</span>
                    <span id="subtotal-display">Rp 0</span>
                </div>
                <div class="flex justify-between text-base font-bold text-gray-800">
                    <span>TOTAL</span>
                    <span id="total-display">Rp 0</span>
                </div>
            </div>

            {{-- Pilih Pembayaran --}}
            <select id="metode-bayar"
                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-600 mb-3 focus:outline-none focus:ring-2 focus:ring-blue-300">
                <option value="">Pilih Pembayaran</option>
                <option value="tunai">Tunai</option>
                <option value="transfer">Transfer Bank</option>
                <option value="qris">QRIS</option>
            </select>

            {{-- Input Bayar (muncul kalau pilih tunai) --}}
            <div id="input-bayar" class="hidden mb-3">
                <input type="number" id="bayar-input" placeholder="Jumlah bayar" min="0"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
                    oninput="hitungKembalian()">
                <div class="flex justify-between text-sm mt-2">
                    <span class="text-gray-500">Kembalian</span>
                    <span id="kembalian-display" class="font-bold text-green-600">Rp 0</span>
                </div>
            </div>

            {{-- Tombol Bayar --}}
            <form id="form-transaksi" method="POST" action="{{ route('transaksi.store') }}">
                @csrf
                <div id="form-items"></div>
                <input type="hidden" name="paid_amount" id="paid_amount">
                <input type="hidden" name="payment_method" id="payment_method">
                <button type="submit" id="btn-bayar"
                    class="w-full bg-gray-200 text-gray-400 py-3 rounded-xl text-sm font-semibold cursor-not-allowed transition"
                    disabled>
                    Bayar Sekarang
                </button>
            </form>

        </div>
    </div>

</div>

<script>
let items = {};

// Tambah item ke keranjang
function tambahItem(el) {
    const id    = el.dataset.id;
    const name  = el.dataset.name;
    const price = parseFloat(el.dataset.price);
    const stock = parseInt(el.dataset.stock);

    if (items[id]) {
        if (items[id].qty < stock) {
            items[id].qty++;
        } else {
            alert('Stok tidak mencukupi!');
            return;
        }
    } else {
        items[id] = { name, price, qty: 1, stock, unit: el.dataset.unit || 'pcs' };
    }

    // Highlight kartu
    el.classList.add('border-blue-400', 'bg-blue-50');
    renderKeranjang();
}

function renderKeranjang() {
    const itemsDiv   = document.getElementById('keranjang-items');
    const emptyDiv   = document.getElementById('keranjang-empty');
    const formItems  = document.getElementById('form-items');
    const countBadge = document.getElementById('keranjang-count');
    itemsDiv.innerHTML  = '';
    formItems.innerHTML = '';

    let total     = 0;
    let itemCount = 0;
    let i         = 0;

    Object.entries(items).forEach(([id, item]) => {
        const sub = item.price * item.qty;
        total    += sub;
        itemCount += item.qty;

        itemsDiv.innerHTML += `
        <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-xl text-sm">
            <div class="flex-1 min-w-0">
                <p class="font-medium text-gray-800 truncate">${item.name}</p>
                <p class="text-xs text-gray-400">Rp ${item.price.toLocaleString('id')} / pcs</p>
            </div>
            <div class="flex items-center gap-1">
                <button type="button" onclick="kurangiItem('${id}')"
                    class="w-6 h-6 rounded-full bg-gray-200 text-gray-600 text-xs font-bold hover:bg-gray-300 flex items-center justify-center">−</button>
                <span class="w-6 text-center text-xs font-bold text-gray-800">${item.qty}</span>
                <button type="button" onclick="tambahQty('${id}')"
                    class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 text-xs font-bold hover:bg-blue-200 flex items-center justify-center">+</button>
            </div>
            <span class="text-xs font-bold text-gray-800 w-16 text-right">Rp ${sub.toLocaleString('id')}</span>
            <button type="button" onclick="hapusItem('${id}')" class="text-red-400 hover:text-red-600 text-xs ml-1">✕</button>
        </div>`;

        formItems.innerHTML += `
            <input type="hidden" name="items[${i}][product_id]" value="${id}">
            <input type="hidden" name="items[${i}][quantity]" value="${item.qty}">`;
        i++;
    });

    // Toggle tampilan
    const hasItems = Object.keys(items).length > 0;
    emptyDiv.classList.toggle('hidden', hasItems);
    itemsDiv.classList.toggle('hidden', !hasItems);

    // Count badge
    countBadge.textContent = itemCount;
    countBadge.classList.toggle('hidden', itemCount === 0);

    // Update total
    document.getElementById('subtotal-display').textContent = 'Rp ' + total.toLocaleString('id');
    document.getElementById('total-display').textContent    = 'Rp ' + total.toLocaleString('id');

    hitungKembalian();
}

function kurangiItem(id) {
    if (items[id].qty > 1) {
        items[id].qty--;
    } else {
        hapusItem(id);
        return;
    }
    renderKeranjang();
}

function tambahQty(id) {
    if (items[id].qty < items[id].stock) {
        items[id].qty++;
        renderKeranjang();
    } else {
        alert('Stok tidak mencukupi!');
    }
}

function hapusItem(id) {
    // Hapus highlight kartu
    document.querySelectorAll('.produk-card').forEach(el => {
        if (el.dataset.id === id) {
            el.classList.remove('border-blue-400', 'bg-blue-50');
        }
    });
    delete items[id];
    renderKeranjang();
}

function hitungKembalian() {
    const total  = Object.values(items).reduce((s, i) => s + i.price * i.qty, 0);
    const metode = document.getElementById('metode-bayar').value;
    const bayar  = parseFloat(document.getElementById('bayar-input').value) || 0;

    let siap = false;

    if (metode === 'tunai') {
        const kembalian = bayar - total;
        document.getElementById('kembalian-display').textContent = 'Rp ' + Math.max(0, kembalian).toLocaleString('id');
        document.getElementById('kembalian-display').className = kembalian >= 0
            ? 'font-bold text-green-600' : 'font-bold text-red-500';
        document.getElementById('paid_amount').value = bayar;
        siap = bayar >= total && total > 0;
    } else if (metode === 'transfer' || metode === 'qris') {
        document.getElementById('paid_amount').value = total;
        siap = total > 0;
    }

    document.getElementById('payment_method').value = metode;

    const btn = document.getElementById('btn-bayar');
    if (siap) {
        btn.disabled  = false;
        btn.className = 'w-full bg-blue-600 text-white py-3 rounded-xl text-sm font-semibold hover:bg-blue-700 transition cursor-pointer';
    } else {
        btn.disabled  = true;
        btn.className = 'w-full bg-gray-200 text-gray-400 py-3 rounded-xl text-sm font-semibold cursor-not-allowed transition';
    }
}

// Tampil/sembunyikan input bayar tunai
document.getElementById('metode-bayar').addEventListener('change', function() {
    const inputBayar = document.getElementById('input-bayar');
    inputBayar.classList.toggle('hidden', this.value !== 'tunai');
    hitungKembalian();
});

function filterKategori(kat, btn) {
    document.querySelectorAll('.kategori-btn').forEach(b => {
        b.className = 'kategori-btn px-4 py-1.5 rounded-full text-sm font-medium bg-gray-100 text-gray-600 hover:bg-gray-200';
    });
    btn.className = 'kategori-btn px-4 py-1.5 rounded-full text-sm font-medium bg-blue-600 text-white';

    document.querySelectorAll('.produk-card').forEach(el => {
        const katProduk = el.dataset.category ? el.dataset.category.trim().toLowerCase() : '';
        const katFilter = kat.trim().toLowerCase();
        const tampil = (katFilter === 'semua' || katProduk === katFilter);
        el.closest('div').style.display = tampil ? '' : 'none';
    });
}

// Search produk
document.getElementById('cari-produk').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.produk-card').forEach(el => {
        el.parentElement.style.display = el.dataset.name.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
@endsection