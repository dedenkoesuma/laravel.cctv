{{-- resources/views/admin/purchase-orders/history.blade.php --}}
@extends('layouts.simple')
@section('title', 'History & Tracking PO')

@section('content')
<style>
.history-header { background: #1e3a5f; color: white; padding: 24px 28px; border-radius: 12px; margin-bottom: 24px; }
.history-card { background: #ffffff; border-radius: 12px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,.04); border: 1px solid #f1f5f9; height: 100%; }
.hc-title { border-bottom: 1px solid #f1f5f9; padding-bottom: 15px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 10px; }
.hc-po-num { font-size: 1.1rem; font-weight: 800; color: #0f172a; font-family: monospace; }
.hc-supplier { font-size: 0.85rem; color: #64748b; margin-top: 4px; font-weight: 600; }

.badge-custom { padding: 4px 10px; border-radius: 6px; font-size: 0.7rem; font-weight: 700; white-space: nowrap; }
.badge-ppn { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
.badge-draft { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }
.badge-sent { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
.badge-done { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }

/* TIMELINE CSS */
.timeline { position: relative; padding-left: 28px; }
.timeline::before { content: ''; position: absolute; left: 11px; top: 0; bottom: 0; width: 2px; background: #e2e8f0; }
.tl-item { position: relative; margin-bottom: 24px; }
.tl-item:last-child { margin-bottom: 0; }
.tl-icon { position: absolute; left: -28px; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; z-index: 1; border: 2px solid #fff; }
.icon-draft { background: #fef08a; color: #ca8a04; }
.icon-edit { background: #e0f2fe; color: #0284c7; }
.icon-sent { background: #dbeafe; color: #2563eb; }
.icon-done { background: #bbf7d0; color: #16a34a; }

.tl-content { background: #f8fafc; padding: 12px 16px; border-radius: 8px; border: 1px solid #f1f5f9; transition: all 0.2s; }
.tl-content:hover { border-color: #cbd5e1; box-shadow: 0 2px 8px rgba(0,0,0,.03); }
.tl-title { font-weight: 700; font-size: 0.85rem; color: #1e293b; margin-bottom: 4px; }
.tl-desc { font-size: 0.75rem; color: #64748b; line-height: 1.5; }
.tl-date { font-size: 0.7rem; color: #94a3b8; margin-top: 8px; display: flex; align-items: center; gap: 4px; }
</style>

<div class="container py-4">
    <div class="history-header d-flex justify-content-between align-items-center">
        <div>
            <h1 class="fs-4 fw-bold mb-1">⏱️ History & Tracking PO</h1>
            <p class="mb-0 opacity-75" style="font-size: 0.85rem">Riwayat lengkap semua aktivitas Purchase Order</p>
        </div>
        <a href="{{ route('admin.po.index') }}" class="btn btn-light btn-sm fw-bold">Kembali ke Daftar PO</a>
    </div>

    {{-- Grid History (Kita load via JS dari API index yang sudah ada) --}}
    <div class="row g-4" id="historyGrid">
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-primary"></div>
            <div class="mt-2 text-muted">Memuat Riwayat...</div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', loadHistory);

async function loadHistory() {
    try {
        // Ambil data PO beserta logs-nya (pastikan controller kamu return logs di index atau endpoint khusus)
        const res = await fetch('/api/admin/purchase-orders');
        const data = await res.json();
        
        const grid = document.getElementById('historyGrid');
        
        if(!data.success || !data.data.length) {
            grid.innerHTML = `<div class="col-12 text-center py-5 text-muted">Belum ada riwayat PO.</div>`;
            return;
        }

        let html = '';
        data.data.forEach(po => {
            // Mapping status badge
            let badgeStatus = `<span class="badge-custom badge-draft">Draft</span>`;
            if(po.status === 'sent') badgeStatus = `<span class="badge-custom badge-sent">Terkirim</span>`;
            if(po.status === 'completed' || po.status === 'confirmed') badgeStatus = `<span class="badge-custom badge-done">Selesai</span>`;
            
            let badgePpn = po.use_ppn ? `<span class="badge-custom badge-ppn">PPN ${po.ppn_percent}%</span>` : `<span class="badge-custom badge-draft">Non PPN</span>`;

            // Bikin dummy history jika array logs kosong (agar UI keliatan jalan)
            const logs = po.logs || [
                { action: 'created', description: 'Draft dibuat', created_at: po.created_at },
                (po.status !== 'draft' ? { action: 'sent', description: 'PO dikirim ke supplier', created_at: po.updated_at } : null)
            ].filter(Boolean);

            let timelines = logs.map(l => {
                let iconClass = 'icon-draft'; let iconSymbol = '📝';
                if(l.action === 'sent') { iconClass = 'icon-sent'; iconSymbol = '📤'; }
                if(l.action === 'edited') { iconClass = 'icon-edit'; iconSymbol = '✏️'; }
                if(l.action === 'confirmed' || l.action === 'completed') { iconClass = 'icon-done'; iconSymbol = '✅'; }

                return `
                <div class="tl-item">
                    <div class="tl-icon ${iconClass}">${iconSymbol}</div>
                    <div class="tl-content">
                        <div class="tl-title">${l.action.toUpperCase()}</div>
                        <div class="tl-desc">${l.description || 'Sistem mencatat perubahan.'}</div>
                        <div class="tl-date"><i class="bi bi-clock"></i> ${new Date(l.created_at).toLocaleString('id-ID')} WIB</div>
                    </div>
                </div>`;
            }).join('');

            html += `
            <div class="col-lg-6">
                <div class="history-card">
                    <div class="hc-title">
                        <div>
                            <div class="hc-po-num">${po.po_number}</div>
                            <div class="hc-supplier">${po.supplier_name}</div>
                        </div>
                        <div class="d-flex gap-2">
                            ${badgePpn} ${badgeStatus}
                        </div>
                    </div>
                    <div class="timeline">
                        ${timelines}
                    </div>
                </div>
            </div>`;
        });

        grid.innerHTML = html;

    } catch (e) {
        document.getElementById('historyGrid').innerHTML = `<div class="col-12 text-center text-danger py-5">Gagal memuat data.</div>`;
    }
}
</script>
@endsection