@extends('layouts.app')
@section('title', 'Transaksi Baru')

@section('content')
<div class="mb-4">
    <h1 class="text-xl font-bold text-gray-800">Transaksi Baru</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Daftar Produk --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h2 class="font-semibold text-gray-700 mb-3">Pilih Produk</h2>
        <input type="text" id="cari-produk" placeholder="Cari nama atau barcode..."
            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-blue-300">
        <div class="space-y-2 max-h-80 overflow-y-auto" id="produk-list">
            @foreach($products as $p)
            <div class="produk-item flex items-center justify-between p-3 rounded-lg border border-gray-100 hover:bg-blue-50 cursor-pointer"
                 data-id="{{ $p['id'] }}"
                 data-name="{{ $p['name'] }}"
                 data-price="{{ $p['sell_price'] }}"
                 data-stock="{{ $p['stock'] }}"
                 onclick="tambahItem(this)">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $p['name'] }}</p>
                    <p class="text-xs text-gray-400">Stok: {{ $p['stock'] }} · Rp {{ number_format($p['sell_price'],0,',','.') }}</p>
                </div>
                <span class="text-blue-600 text-lg">+</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Keranjang --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h2 class="font-semibold text-gray-700 mb-3">Keranjang</h2>
        <div id="keranjang" class="space-y-2 min-h-32 mb-4"></div>

        <div class="border-t border-gray-100 pt-3 space-y-3">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Total</span>
                <span class="font-bold text-gray-800" id="total-display">Rp 0</span>
            </div>
            <div>
                <label class="text-sm text-gray-500">Jumlah Bayar</label>
                <input type="number" id="bayar-input" min="0" placeholder="0"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm mt-1 focus:outline-none focus:ring-2 focus:ring-blue-300"
                    oninput="hitungKembalian()">
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Kembalian</span>
                <span class="font-bold text-green-600" id="kembalian-display">Rp 0</span>
            </div>
        </div>

        <form id="form-transaksi" method="POST" action="{{ route('transaksi.store') }}">
            @csrf
            <div id="form-items"></div>
            <input type="hidden" name="paid_amount" id="paid_amount">
            <button type="submit" id="btn-bayar"
                class="mt-4 w-full bg-blue-600 text-white py-2.5 rounded-lg text-sm font-medium hover:bg-blue-700 disabled:opacity-50"
                disabled>
                Proses Transaksi
            </button>
        </form>
    </div>

</div>

<script>
let items = {};

function tambahItem(el) {
    const id    = el.dataset.id;
    const name  = el.dataset.name;
    const price = parseFloat(el.dataset.price);
    const stock = parseInt(el.dataset.stock);

    if (items[id]) {
        if (items[id].qty < stock) items[id].qty++;
    } else {
        items[id] = { name, price, qty: 1, stock };
    }
    renderKeranjang();
}

function renderKeranjang() {
    const div = document.getElementById('keranjang');
    const formItems = document.getElementById('form-items');
    div.innerHTML = '';
    formItems.innerHTML = '';
    let total = 0;

    Object.entries(items).forEach(([id, item], i) => {
        const sub = item.price * item.qty;
        total += sub;
        div.innerHTML += `
            <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg text-sm">
                <div class="flex-1">
                    <p class="font-medium text-gray-800">${item.name}</p>
                    <p class="text-gray-400">Rp ${item.price.toLocaleString('id')} × ${item.qty}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="font-medium text-gray-800">Rp ${sub.toLocaleString('id')}</span>
                    <button type="button" onclick="hapusItem('${id}')" class="text-red-400 hover:text-red-600">×</button>
                </div>
            </div>`;
        formItems.innerHTML += `
            <input type="hidden" name="items[${i}][product_id]" value="${id}">
            <input type="hidden" name="items[${i}][quantity]" value="${item.qty}">`;
    });

    document.getElementById('total-display').textContent = 'Rp ' + total.toLocaleString('id');
    hitungKembalian();
}

function hapusItem(id) {
    delete items[id];
    renderKeranjang();
}

function hitungKembalian() {
    const total = Object.values(items).reduce((s, i) => s + i.price * i.qty, 0);
    const bayar = parseFloat(document.getElementById('bayar-input').value) || 0;
    const kembalian = bayar - total;
    document.getElementById('kembalian-display').textContent = 'Rp ' + Math.max(0, kembalian).toLocaleString('id');
    document.getElementById('paid_amount').value = bayar;
    document.getElementById('btn-bayar').disabled = (bayar < total || Object.keys(items).length === 0);
}

document.getElementById('cari-produk').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.produk-item').forEach(el => {
        el.style.display = el.dataset.name.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
@endsection