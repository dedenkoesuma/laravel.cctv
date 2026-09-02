<div class="w-52 min-h-screen flex flex-col" style="background:#1e1b4b">
    <div class="p-4 border-b border-white/10">
        <p class="text-white font-medium text-sm">🖨 Toko Print</p>
        <p class="text-white/50 text-xs">Admin Dashboard</p>
    </div>

    <nav class="flex-1 py-2">
        <p class="text-white/40 text-xs px-4 pt-3 pb-1 uppercase tracking-wider">Utama</p>
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-2 px-4 py-2 text-sm text-white/75 hover:bg-white/10 hover:text-white
                  {{ request()->routeIs('dashboard') ? 'bg-white/10 text-white border-l-2 border-indigo-400' : '' }}">
            Dashboard
        </a>

        <p class="text-white/40 text-xs px-4 pt-3 pb-1 uppercase tracking-wider">Transaksi</p>
        <a href="{{ route('pesanan-online.index') }}"
           class="flex items-center gap-2 px-4 py-2 text-sm text-white/75 hover:bg-white/10 hover:text-white
                  {{ request()->routeIs('pesanan-online.*') ? 'bg-white/10 text-white border-l-2 border-indigo-400' : '' }}">
            Pesanan online
        </a>
        <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-white/75 hover:bg-white/10 hover:text-white">
            Pesanan offline
        </a>
        <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-white/75 hover:bg-white/10 hover:text-white">
            Invoice
        </a>

        <p class="text-white/40 text-xs px-4 pt-3 pb-1 uppercase tracking-wider">Keuangan</p>
        <a href="{{ route('uang-masuk.index') }}"
           class="flex items-center gap-2 px-4 py-2 text-sm text-white/75 hover:bg-white/10 hover:text-white
                  {{ request()->routeIs('uang-masuk.*') ? 'bg-white/10 text-white border-l-2 border-indigo-400' : '' }}">
            Uang masuk
        </a>
        <a href="{{ route('uang-keluar.index') }}"
           class="flex items-center gap-2 px-4 py-2 text-sm text-white/75 hover:bg-white/10 hover:text-white
                  {{ request()->routeIs('uang-keluar.*') ? 'bg-white/10 text-white border-l-2 border-indigo-400' : '' }}">
            Uang keluar
        </a>
        <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-white/75 hover:bg-white/10 hover:text-white">
            Laporan
        </a>

        <p class="text-white/40 text-xs px-4 pt-3 pb-1 uppercase tracking-wider">Produk</p>
        <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-white/75 hover:bg-white/10 hover:text-white">
            Tipe kertas
        </a>
        <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-white/75 hover:bg-white/10 hover:text-white">
            Stok
        </a>
    </nav>
</div>