{{-- resources/views/components/notification-bell.blade.php --}}
{{-- Include di navbar dengan: @include('components.notification-bell') --}}

<div class="notif-wrapper" style="position: relative; display: inline-block;">

    {{-- Bell button --}}
    <button id="notif-btn" onclick="toggleNotifDropdown()"
        style="
            position: relative;
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px 10px;
            border-radius: 8px;
            font-size: 18px;
            transition: background .2s;
        "
        onmouseover="this.style.background='rgba(0,0,0,0.06)'"
        onmouseout="this.style.background='none'"
    >
        🔔
        <span id="notif-badge"
            style="
                display: none;
                position: absolute;
                top: 2px; right: 4px;
                background: #E24B4A;
                color: #fff;
                font-size: 10px;
                font-weight: 600;
                min-width: 16px;
                height: 16px;
                border-radius: 8px;
                padding: 0 4px;
                line-height: 16px;
                text-align: center;
            "
        >0</span>
    </button>

    {{-- Dropdown --}}
    <div id="notif-dropdown"
        style="
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 340px;
            background: #fff;
            border: 0.5px solid #ddd;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.10);
            z-index: 9999;
            overflow: hidden;
        "
    >
        {{-- Header --}}
        <div style="display:flex; justify-content:space-between; align-items:center; padding: 12px 16px; border-bottom: 0.5px solid #eee;">
            <span style="font-weight:600; font-size:14px; color:#1a1a1a;">Notifikasi</span>
            <button onclick="readAll()" style="font-size:12px; color:#185FA5; background:none; border:none; cursor:pointer;">
                Tandai semua dibaca
            </button>
        </div>

        {{-- List notifikasi --}}
        <div id="notif-list" style="max-height: 380px; overflow-y: auto;">
            <div id="notif-empty" style="padding: 24px; text-align:center; color:#999; font-size:13px;">
                Tidak ada notifikasi baru
            </div>
        </div>
    </div>
</div>

{{-- Close dropdown kalau klik di luar --}}
<script>
document.addEventListener('click', function(e) {
    const wrapper = document.querySelector('.notif-wrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        document.getElementById('notif-dropdown').style.display = 'none';
    }
});

function toggleNotifDropdown() {
    const dd = document.getElementById('notif-dropdown');
    dd.style.display = dd.style.display === 'none' ? 'block' : 'none';
}

const WARNA = {
    h3:      { bg: '#FAEEDA', border: '#BA7517', text: '#633806', label: 'H-3' },
    h1:      { bg: '#FEF3C7', border: '#D97706', text: '#92400E', label: 'H-1' },
    overdue: { bg: '#FCEBEB', border: '#E24B4A', text: '#7F1D1D', label: 'Overdue' },
};

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
            list.innerHTML = '';
            list.appendChild(empty);
            return;
        }

        badge.style.display  = 'inline-block';
        badge.textContent    = json.count > 99 ? '99+' : json.count;
        empty.style.display  = 'none';

        list.innerHTML = json.data.map(n => {
            const w = WARNA[n.tipe] || WARNA.h3;
            return `
            <div style="
                padding: 12px 16px;
                border-bottom: 0.5px solid #f0f0f0;
                background: ${w.bg};
                border-left: 3px solid ${w.border};
            ">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:8px;">
                    <div style="flex:1;">
                        <div style="display:flex; align-items:center; gap:6px; margin-bottom:4px;">
                            <span style="
                                font-size:10px; font-weight:600;
                                background:${w.border}; color:#fff;
                                padding: 1px 6px; border-radius:4px;
                            ">${w.label}</span>
                            <span style="font-size:11px; color:#999;">${n.waktu}</span>
                        </div>
                        <div style="font-size:13px; font-weight:600; color:${w.text}; margin-bottom:2px;">${n.judul}</div>
                        <div style="font-size:12px; color:#555; line-height:1.4;">${n.pesan}</div>
                    </div>
                    <button onclick="readOne(${n.id}, this)"
                        style="
                            font-size:11px; white-space:nowrap;
                            color:#185FA5; background:none;
                            border:none; cursor:pointer; padding:0;
                            margin-top:2px;
                        "
                    >✓ Dibaca</button>
                </div>
            </div>`;
        }).join('');

    } catch(e) {
        console.error('Gagal fetch notifikasi:', e);
    }
}

async function readOne(id, btn) {
    btn.disabled = true;
    await fetch(`/notifications/${id}/read`, { method: 'PATCH', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
    fetchNotifikasi();
}

async function readAll() {
    await fetch('{{ route("notifications.readAll") }}', { method: 'PATCH', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
    fetchNotifikasi();
}

// Polling tiap 5 menit + langsung saat load
fetchNotifikasi();
setInterval(fetchNotifikasi, 5 * 60 * 1000);
</script>