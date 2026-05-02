{{-- resources/views/components/notification-bell.blade.php --}}
{{-- resources/views/components/notification-bell.blade.php --}}
{{-- resources/views/components/notification-bell.blade.php --}}
<div class="dropdown d-inline-block me-2">
    <button class="btn btn-link text-white position-relative p-0 text-decoration-none" 
            type="button" 
            data-bs-toggle="dropdown" 
            aria-expanded="false" 
            style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 10px;">
        <i class="bi bi-bell-fill fs-5"></i>
        <span id="notif-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-2 border-dark" 
              style="display: none; font-size: 0.65rem; padding: 0.25em 0.5em;">
            0
        </span>
    </button>

    {{-- Perbaikan Posisi Disini Den: Pake translateX biar geser ke kanan --}}
    <div class="dropdown-menu shadow-lg border-0 p-0" 
         style="width: 360px; border-radius: 12px; overflow: hidden; z-index: 1050; margin-top: 10px; left: 0 !important; transform: translateX(10px) !important;">
        
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center px-4 py-3 bg-white border-bottom">
            <h6 class="mb-0 fw-bold" style="color: #1e293b;">🔔 Notifikasi Sistem</h6>
            <button onclick="readAll()" class="btn btn-sm btn-link text-primary p-0 text-decoration-none fw-semibold" style="font-size: 0.8rem;">
                Tandai semua dibaca
            </button>
        </div>

        {{-- List notifikasi --}}
        <div id="notif-list" style="max-height: 400px; overflow-y: auto; background: #f8fafc;">
            <div id="notif-empty" class="p-5 text-center text-muted">
                <i class="bi bi-bell-slash fs-1 d-block mb-2 opacity-50"></i>
                <span class="small fw-medium">Tidak ada notifikasi baru</span>
            </div>
        </div>
    </div>
</div>
<script>
async function fetchNotifikasi() {
    try {
        const res  = await fetch('{{ route("notifications.index") }}');
        const json = await res.json();

        const badge = document.getElementById('notif-badge');
        const list  = document.getElementById('notif-list');
        const empty = document.getElementById('notif-empty');

        if (json.count === 0) {
            badge.style.display = 'none';
            empty.style.display = 'block';
            
            // Hapus isi list kecuali elemen empty
            Array.from(list.children).forEach(child => {
                if(child.id !== 'notif-empty') child.remove();
            });
            return;
        }

        badge.style.display  = 'inline-block';
        badge.textContent    = json.count > 99 ? '99+' : json.count;
        empty.style.display  = 'none';

        // Mapping warna background agar estetik sesuai tipe (danger/warning/primary)
        const getBgColor = (warna) => {
            if(warna === 'danger') return { bg: '#fef2f2', border: '#ef4444', text: '#991b1b' };
            if(warna === 'warning' || warna === 'orange') return { bg: '#fffbeb', border: '#f59e0b', text: '#92400e' };
            return { bg: '#eff6ff', border: '#3b82f6', text: '#1e40af' };
        };

        // Render HTML
        const listHTML = json.data.map(n => {
            const style = getBgColor(n.warna);
            return `
            <div class="p-3 border-bottom position-relative" 
                 style="background: ${style.bg}; border-left: 4px solid ${style.border}; cursor: pointer; transition: filter 0.2s;" 
                 onmouseover="this.style.filter='brightness(0.95)'" 
                 onmouseout="this.style.filter='none'" 
                 onclick="readOne(${n.id}, this)">
                 
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <div style="flex:1;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-${n.warna === 'orange' ? 'warning' : n.warna} text-dark border shadow-sm" style="font-size:0.7rem;">
                                ${n.icon} ${n.no_invoice !== '-' ? n.no_invoice : 'INFO'}
                            </span>
                            <span class="text-muted fw-semibold" style="font-size: 0.65rem;">${n.waktu}</span>
                        </div>
                        <div class="fw-bold mb-1" style="font-size: 0.85rem; color: ${style.text}; line-height: 1.3;">
                            ${n.judul}
                        </div>
                        <div style="font-size: 0.75rem; color: #475569; line-height: 1.4;">
                            ${n.pesan}
                        </div>
                    </div>
                </div>
            </div>`;
        }).join('');

        // Masukkan HTML baru sambil mempertahankan elemen empty
        list.innerHTML = listHTML;
        list.appendChild(empty);

    } catch(e) {
        console.error('Gagal fetch notifikasi:', e);
    }
}

async function readOne(id, divElement) {
    // Biar ada efek loading tipis pas diklik
    divElement.style.opacity = '0.5';
    divElement.style.pointerEvents = 'none';
    
    await fetch(`/notifications/${id}/read`, { 
        method: 'PATCH', 
        headers: { 
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        } 
    });
    fetchNotifikasi();
}

async function readAll() {
    await fetch('{{ route("notifications.readAll") }}', { 
        method: 'PATCH', 
        headers: { 
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        } 
    });
    fetchNotifikasi();
}

// Polling otomatis
document.addEventListener('DOMContentLoaded', () => {
    fetchNotifikasi();
    setInterval(fetchNotifikasi, 5 * 60 * 1000);
});
</script>