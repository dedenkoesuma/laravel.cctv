<div id="ai-assistant-wrapper">
    <!-- Toggle Button (Corporate Live Chat Style) -->
    <button id="ai-toggle-btn" onclick="toggleAI()" title="Bantuan & Konsultasi Online">
        <i class="bi bi-headset"></i>
        <span class="ai-btn-text">Bantuan CS</span>
    </button>

    <!-- Chat Window -->
    <div id="ai-chat-window" style="display:none">
        <div id="ai-chat-header">
            <div style="display:flex; align-items:center; gap:10px">
                <div class="ai-avatar-badge">
                    <i class="bi bi-headset"></i>
                </div>
                <div>
                    <div style="font-weight:700; font-size:14px; letter-spacing:-0.2px">Layanan Bantuan Virtual</div>
                    <div style="font-size:11px; color:#10b981; display:flex; align-items:center; gap:4px">
                        <span style="width:6px; height:6px; border-radius:50%; background:#10b981; display:inline-block"></span>
                        PT. MJA TEKNOLOGI · Online
                    </div>
                </div>
            </div>
            <button onclick="toggleAI()" class="ai-close-btn" aria-label="Tutup Chat">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div id="ai-chat-messages">
            <div class="ai-msg ai-msg-bot">
                <i class="bi bi-shield-check me-1 text-danger"></i>
                Halo! Selamat datang di <strong>TechStore (PT. MJA TEKNOLOGI)</strong>. Ada yang bisa kami bantu mengenai paket kamera CCTV, WiFi Cam, atau Akses Kontrol?
            </div>
        </div>

        <div id="ai-chat-input-area">
            <input
                type="text"
                id="ai-user-input"
                placeholder="Tulis pertanyaan Anda..."
                onkeydown="if(event.key==='Enter') sendAIMessage()"
            />
            <button onclick="sendAIMessage()" id="ai-send-btn" aria-label="Kirim Pesan">
                <i class="bi bi-send-fill"></i>
            </button>
        </div>
    </div>
</div>

<style>
#ai-assistant-wrapper { 
    position: fixed; 
    bottom: 24px; 
    right: 24px; 
    z-index: 9999; 
    font-family: var(--ts-font, 'Plus Jakarta Sans', system-ui, sans-serif); 
}

#ai-toggle-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    border-radius: 9999px;
    border: 1px solid rgba(15, 23, 42, 0.1);
    background-color: #0f172a;
    color: white; 
    font-size: 14px; 
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.25);
    transition: all 0.2s ease;
}

#ai-toggle-btn i {
    font-size: 16px;
    color: #f87171;
}

#ai-toggle-btn:hover { 
    background-color: #dc2626;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 38, 38, 0.35);
}

#ai-toggle-btn:hover i {
    color: white;
}

@media (max-width: 576px) {
    #ai-toggle-btn {
        width: 44px;
        height: 44px;
        padding: 0;
        justify-content: center;
    }
    #ai-toggle-btn .ai-btn-text {
        display: none;
    }
}

#ai-chat-window {
    position: absolute; 
    bottom: 58px; 
    right: 0;
    width: 350px; 
    height: 480px; 
    background: #ffffff;
    border-radius: 12px; 
    box-shadow: 0 16px 40px rgba(15, 23, 42, 0.2);
    border: 1px solid #cbd5e1;
    display: flex; 
    flex-direction: column; 
    overflow: hidden;
}

#ai-chat-header {
    background: #0f172a;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    color: white; 
    padding: 14px 16px;
    display: flex; 
    justify-content: space-between; 
    align-items: center;
}

.ai-avatar-badge {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    background: rgba(220, 38, 38, 0.2);
    color: #f87171;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.ai-close-btn {
    background: rgba(255, 255, 255, 0.08);
    border: none;
    color: #cbd5e1;
    width: 28px;
    height: 28px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 12px;
    transition: all 0.2s ease;
}

.ai-close-btn:hover {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.2);
}

#ai-chat-messages {
    flex: 1; 
    overflow-y: auto; 
    padding: 16px;
    display: flex; 
    flex-direction: column; 
    gap: 12px;
    background: #f8fafc;
}

.ai-msg { 
    padding: 10px 14px; 
    border-radius: 8px; 
    font-size: 13.5px; 
    max-width: 86%; 
    line-height: 1.5; 
}

.ai-msg-bot { 
    background: #ffffff; 
    color: #1e293b; 
    border: 1px solid #e2e8f0; 
    align-self: flex-start; 
}

.ai-msg-user { 
    background-color: #0f172a; 
    color: white; 
    align-self: flex-end; 
}

.ai-msg-typing { 
    color: #64748b; 
    font-style: italic; 
    font-size: 12.5px;
}

#ai-chat-input-area {
    padding: 10px 12px; 
    border-top: 1px solid #e2e8f0;
    display: flex; 
    gap: 8px; 
    background: #ffffff;
}

#ai-user-input {
    flex: 1; 
    padding: 9px 14px; 
    border: 1px solid #cbd5e1;
    border-radius: 6px; 
    font-size: 13.5px; 
    outline: none; 
    transition: all 0.2s ease;
    background: #f8fafc;
}

#ai-user-input:focus { 
    border-color: #0f172a; 
    background: #ffffff;
}

#ai-send-btn {
    width: 38px; 
    height: 38px; 
    border-radius: 6px; 
    border: none;
    background-color: #0f172a;
    color: white; 
    cursor: pointer; 
    display: flex;
    align-items: center; 
    justify-content: center; 
    font-size: 14px;
    transition: all 0.2s ease;
}

#ai-send-btn:hover { 
    background-color: #dc2626;
}

#ai-send-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

@media(max-width: 480px) {
    #ai-chat-window { 
        width: calc(100vw - 32px); 
        right: -8px; 
        height: 450px;
    }
}
</style>

<script>
let aiMessages = [];
let aiOpen = false;

function toggleAI() {
    aiOpen = !aiOpen;
    document.getElementById('ai-chat-window').style.display = aiOpen ? 'flex' : 'none';
    if (aiOpen) document.getElementById('ai-user-input').focus();
}

function appendMessage(role, text) {
    const box = document.getElementById('ai-chat-messages');
    const div = document.createElement('div');
    div.className = `ai-msg ai-msg-${role === 'user' ? 'user' : 'bot'}`;
    div.innerHTML = (role !== 'user' ? '<i class="bi bi-shield-check me-1 text-danger"></i>' : '') + escapeHtml(text);
    box.appendChild(div);
    box.scrollTop = box.scrollHeight;
    return div;
}

function escapeHtml(str) {
    return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

async function sendAIMessage() {
    const input = document.getElementById('ai-user-input');
    const text = input.value.trim();
    if (!text) return;

    input.value = '';
    appendMessage('user', text);
    aiMessages.push({ role: 'user', content: text });

    // Typing indicator
    const typing = document.getElementById('ai-chat-messages').appendChild(
        Object.assign(document.createElement('div'), {
            className: 'ai-msg ai-msg-bot ai-msg-typing',
            innerHTML: '<i class="bi bi-shield-check me-1 text-danger"></i>Mengetik tanggapan...'
        })
    );
    document.getElementById('ai-chat-messages').scrollTop = 99999;
    document.getElementById('ai-send-btn').disabled = true;

    try {
        const res = await fetch('/ai/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ messages: aiMessages })
        });

        const data = await res.json();
        const reply = data.reply || 'Maaf, terjadi kendala teknis. Silakan hubungi WhatsApp kami langsung.';

        typing.remove();
        appendMessage('bot', reply);
        aiMessages.push({ role: 'assistant', content: reply });

    } catch (e) {
        typing.remove();
        appendMessage('bot', 'Koneksi terputus. Silakan hubungi teknisi kami via WhatsApp.');
    }

    document.getElementById('ai-send-btn').disabled = false;
    document.getElementById('ai-user-input').focus();
}
</script>