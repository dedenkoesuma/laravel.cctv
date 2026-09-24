<style>
  .tp-sidebar {
    width: 256px !important;
    min-width: 256px !important;
    max-width: 256px !important;
    min-height: 100vh !important;
    background-color: #090D16 !important;
    border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
    color: #94A3B8 !important;
    display: flex !important;
    flex-direction: column !important;
    flex-shrink: 0 !important;
    box-sizing: border-box !important;
    font-family: 'Plus Jakarta Sans', 'Inter', system-ui, -apple-system, sans-serif !important;
    position: sticky;
    top: 0;
    height: 100vh;
    overflow-y: auto;
    z-index: 40;
  }
  .tp-sidebar * { box-sizing: border-box; }
  .tp-sidebar a { text-decoration: none; color: inherit; }
  .tp-brand-header {
    padding: 18px 16px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .tp-brand-logo {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: linear-gradient(135deg, #DC2626, #991B1B);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.35);
    flex-shrink: 0;
  }
  .tp-brand-title {
    font-size: 14.5px;
    font-weight: 700;
    color: #F8FAFC;
    letter-spacing: -0.01em;
    line-height: 1.2;
  }
  .tp-brand-sub {
    font-size: 11px;
    color: #64748B;
    font-weight: 500;
    margin-top: 2px;
  }
  .tp-nav-body {
    flex: 1;
    padding: 14px 10px;
    overflow-y: auto;
  }
  .tp-nav-group-title {
    font-size: 10px;
    font-weight: 700;
    color: #475569;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    padding: 12px 10px 4px;
  }
  .tp-nav-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8.5px 12px;
    border-radius: 8px;
    font-size: 12.5px;
    font-weight: 500;
    color: #94A3B8;
    margin-bottom: 2px;
    transition: all 0.18s ease;
    position: relative;
  }
  .tp-nav-link:hover {
    background: rgba(255, 255, 255, 0.05);
    color: #F8FAFC;
    padding-left: 14px;
  }
  .tp-nav-link.active {
    background: rgba(220, 38, 38, 0.12);
    color: #FFFFFF;
    font-weight: 600;
    border-left: 3px solid #EF4444;
  }
  .tp-nav-icon {
    width: 17px;
    height: 17px;
    flex-shrink: 0;
    opacity: 0.85;
    transition: transform 0.2s ease;
  }
  .tp-nav-link:hover .tp-nav-icon,
  .tp-nav-link.active .tp-nav-icon {
    opacity: 1;
  }
  .tp-badge-tag {
    font-size: 9px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 4px;
    letter-spacing: 0.04em;
    margin-left: auto;
  }
  .tp-badge-cctv {
    background: rgba(59, 130, 246, 0.15);
    color: #60A5FA;
    border: 1px solid rgba(59, 130, 246, 0.3);
  }
  .tp-badge-count {
    background: rgba(239, 68, 68, 0.2);
    color: #F87171;
    border: 1px solid rgba(239, 68, 68, 0.3);
  }
  .tp-sidebar-footer {
    padding: 12px 14px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 11px;
    color: #64748B;
  }
</style>

<aside id="tokoPrintSidebar" class="tp-sidebar w-64 min-h-screen flex flex-col bg-[#090D16] border-r border-white/10 shrink-0 text-slate-400 select-none"
       style="width:256px !important; min-width:256px !important; max-width:256px !important; min-height:100vh !important; background-color:#090D16 !important; border-right:1px solid rgba(255,255,255,0.08) !important; color:#94A3B8 !important; display:flex !important; flex-direction:column !important; flex-shrink:0 !important; box-sizing:border-box !important;">
    
    <!-- Brand Header -->
    <div class="tp-brand-header p-4 border-b border-white/10 flex items-center gap-3"
         style="padding:16px 14px !important; border-bottom:1px solid rgba(255,255,255,0.08) !important; display:flex !important; align-items:center !important; gap:12px !important;">
        <div class="tp-brand-logo w-9 h-9 rounded-lg bg-gradient-to-br from-red-600 to-red-800 flex items-center justify-center text-white shrink-0"
             style="width:38px !important; height:38px !important; border-radius:10px !important; background:linear-gradient(135deg, #DC2626, #991B1B) !important; display:flex !important; align-items:center !important; justify-content:center !important; color:#ffffff !important; flex-shrink:0 !important; box-shadow:0 4px 12px rgba(220,38,38,0.35) !important;">
            <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
        </div>
        <div style="min-width:0;">
            <div class="tp-brand-title" style="font-size:14.5px !important; font-weight:700 !important; color:#F8FAFC !important; line-height:1.2 !important;">Toko Print</div>
            <div class="tp-brand-sub" style="font-size:11px !important; color:#64748B !important; font-weight:500 !important;">Management Suite</div>
        </div>
    </div>

    <!-- Navigation List -->
    <div class="tp-nav-body flex-1 py-3 px-2" style="flex:1 !important; padding:12px 10px !important; overflow-y:auto !important;">
        
        <!-- Section: UTAMA -->
        <div class="tp-nav-group-title" style="font-size:10px !important; font-weight:700 !important; color:#475569 !important; letter-spacing:0.08em !important; text-transform:uppercase !important; padding:10px 10px 4px !important;">Utama</div>
        
        <a href="{{ route('admin.dashboard') }}"
           class="tp-nav-link flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-lg text-slate-300 hover:bg-white/5 hover:text-white transition"
           style="display:flex !important; align-items:center !important; justify-content:space-between !important; padding:8.5px 12px !important; border-radius:8px !important; text-decoration:none !important;">
            <span style="display:flex !important; align-items:center !important; gap:10px !important;">
                <svg class="tp-nav-icon text-red-500" style="width:17px;height:17px;color:#EF4444;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                <span style="color:#CBD5E1; font-weight:600;">Dashboard Admin</span>
            </span>
            <span class="tp-badge-tag tp-badge-cctv text-[9px] font-bold px-1.5 py-0.5 rounded bg-blue-500/20 text-blue-400 border border-blue-500/30"
                  style="font-size:9px !important; font-weight:700 !important; padding:2px 6px !important; border-radius:4px !important; background:rgba(59,130,246,0.15) !important; color:#60A5FA !important; border:1px solid rgba(59,130,246,0.3) !important;">CCTV</span>
        </a>

        <a href="{{ route('dashboard') }}"
           class="tp-nav-link {{ request()->routeIs('dashboard') || request()->routeIs('beranda') ? 'active' : '' }}"
           style="display:flex !important; align-items:center !important; gap:10px !important; padding:8.5px 12px !important; border-radius:8px !important; text-decoration:none !important;">
            <svg class="tp-nav-icon" style="width:17px;height:17px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/>
            </svg>
            <span>Dashboard Print</span>
        </a>

        <!-- Section: TRANSAKSI -->
        <div class="tp-nav-group-title" style="font-size:10px !important; font-weight:700 !important; color:#475569 !important; letter-spacing:0.08em !important; text-transform:uppercase !important; padding:14px 10px 4px !important;">Transaksi</div>

        <a href="{{ route('pesanan-online.index') }}"
           class="tp-nav-link {{ request()->routeIs('pesanan-online.*') ? 'active' : '' }}"
           style="display:flex !important; align-items:center !important; gap:10px !important; padding:8.5px 12px !important; border-radius:8px !important; text-decoration:none !important;">
            <svg class="tp-nav-icon" style="width:17px;height:17px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            <span>Pesanan Online</span>
        </a>

        <a href="{{ route('pesanan-offline.index') }}"
           class="tp-nav-link {{ request()->routeIs('pesanan-offline.*') ? 'active' : '' }}"
           style="display:flex !important; align-items:center !important; gap:10px !important; padding:8.5px 12px !important; border-radius:8px !important; text-decoration:none !important;">
            <svg class="tp-nav-icon" style="width:17px;height:17px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-6 9 6v11a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1Z"/>
            </svg>
            <span>Pesanan Offline</span>
        </a>

        <a href="{{ route('invoice.index') }}"
           class="tp-nav-link {{ request()->routeIs('invoice.*') ? 'active' : '' }}"
           style="display:flex !important; align-items:center !important; gap:10px !important; padding:8.5px 12px !important; border-radius:8px !important; text-decoration:none !important;">
            <svg class="tp-nav-icon" style="width:17px;height:17px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>Invoice Cetak</span>
        </a>

        <!-- Section: KEUANGAN -->
        <div class="tp-nav-group-title" style="font-size:10px !important; font-weight:700 !important; color:#475569 !important; letter-spacing:0.08em !important; text-transform:uppercase !important; padding:14px 10px 4px !important;">Keuangan</div>

        <a href="{{ route('uang-masuk.index') }}"
           class="tp-nav-link {{ request()->routeIs('uang-masuk.*') ? 'active' : '' }}"
           style="display:flex !important; align-items:center !important; gap:10px !important; padding:8.5px 12px !important; border-radius:8px !important; text-decoration:none !important;">
            <svg class="tp-nav-icon text-emerald-400" style="width:17px;height:17px;color:#34D399;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
            <span>Uang Masuk</span>
        </a>

        <a href="{{ route('uang-keluar.index') }}"
           class="tp-nav-link {{ request()->routeIs('uang-keluar.*') ? 'active' : '' }}"
           style="display:flex !important; align-items:center !important; gap:10px !important; padding:8.5px 12px !important; border-radius:8px !important; text-decoration:none !important;">
            <svg class="tp-nav-icon text-red-400" style="width:17px;height:17px;color:#F87171;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
            </svg>
            <span>Uang Keluar</span>
        </a>

        <a href="{{ route('laporan-printing.index') }}"
           class="tp-nav-link {{ request()->routeIs('laporan-printing.*') ? 'active' : '' }}"
           style="display:flex !important; align-items:center !important; gap:10px !important; padding:8.5px 12px !important; border-radius:8px !important; text-decoration:none !important;">
            <svg class="tp-nav-icon text-amber-400" style="width:17px;height:17px;color:#FBBF24;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span>Laporan Printing</span>
        </a>

        <!-- Section: MASTER -->
        <div class="tp-nav-group-title" style="font-size:10px !important; font-weight:700 !important; color:#475569 !important; letter-spacing:0.08em !important; text-transform:uppercase !important; padding:14px 10px 4px !important;">Master</div>

        <a href="{{ route('dashboard') }}"
           class="tp-nav-link"
           style="display:flex !important; align-items:center !important; gap:10px !important; padding:8.5px 12px !important; border-radius:8px !important; text-decoration:none !important;">
            <svg class="tp-nav-icon" style="width:17px;height:17px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <span>Tipe Kertas</span>
        </a>

        <a href="{{ route('dashboard') }}"
           class="tp-nav-link"
           style="display:flex !important; align-items:center !important; gap:10px !important; padding:8.5px 12px !important; border-radius:8px !important; text-decoration:none !important;">
            <svg class="tp-nav-icon" style="width:17px;height:17px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <span>Stok Kertas</span>
        </a>
    </div>

    <!-- Footer Status -->
    <div class="tp-sidebar-footer"
         style="padding:14px 16px !important; border-top:1px solid rgba(255,255,255,0.08) !important; display:flex !important; align-items:center !important; justify-content:space-between !important; font-size:11px !important; color:#64748B !important;">
        <div style="display:flex; align-items:center; gap:6px;">
            <span style="width:7px; height:7px; border-radius:50%; background:#10B981; box-shadow:0 0 8px rgba(16,185,129,0.5); display:inline-block;"></span>
            <span style="color:#94A3B8; font-weight:500;">Sistem Aktif</span>
        </div>
        <span style="font-family:monospace; color:#475569; font-size:10px;">v2.6</span>
    </div>
</aside>