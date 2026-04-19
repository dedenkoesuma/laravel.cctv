<div id="ai-assistant-wrapper">
    <!-- Toggle Button -->
    <button id="ai-toggle-btn" onclick="toggleAI()" title="AI Assistant">
        <i class="bi bi-robot"></i>
    </button>

    <!-- Chat Window -->
    <div id="ai-chat-window" style="display:none">
        <div id="ai-chat-header">
            <div style="display:flex; align-items:center; gap:8px">
                <i class="bi bi-robot" style="font-size:1.2rem"></i>
                <div>
                    <div style="font-weight:700; font-size:.95rem">AI Assistant</div>
                    <div style="font-size:.75rem; opacity:.8">TechStore · Online</div>
                </div>
            </div>
            <button onclick="toggleAI()" style="background:none;border:none;color:white;font-size:1.2rem;cursor:pointer">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div id="ai-chat-messages">
            <div class="ai-msg ai-msg-bot">
                <i class="bi bi-robot" style="margin-right:6px"></i>
                Halo! Saya AI Assistant TechStore. Ada yang bisa saya bantu? 😊
            </div>
        </div>

        <div id="ai-chat-input-area">
            <input
                type="text"
                id="ai-user-input"
                placeholder="Tanya sesuatu..."
                onkeydown="if(event.key==='Enter') sendAIMessage()"
            />
            <button onclick="sendAIMessage()" id="ai-send-btn">
                <i class="bi bi-send"></i>
            </button>
        </div>
    </div>
</div>

<style>
#ai-assistant-wrapper { position:fixed; bottom:24px; right:24px; z-index:9999; font-family:'Segoe UI',sans-serif; }

#ai-toggle-btn {
    width:56px; height:56px; border-radius:50%; border:none;
    background:linear-gradient(135deg,#667eea,#764ba2);
    color:white; font-size:1.5rem; cursor:pointer;
    box-shadow:0 4px 16px rgba(102,126,234,.5);
    transition:all .3s ease; display:flex; align-items:center; justify-content:center;
}
#ai-toggle-btn:hover { transform:scale(1.1); }

#ai-chat-window {
    position:absolute; bottom:70px; right:0;
    width:340px; height:480px; background:white;
    border-radius:16px; box-shadow:0 8px 32px rgba(0,0,0,.18);
    display:flex; flex-direction:column; overflow:hidden;
}

#ai-chat-header {
    background:linear-gradient(135deg,#667eea,#764ba2);
    color:white; padding:14px 16px;
    display:flex; justify-content:space-between; align-items:center;
}

#ai-chat-messages {
    flex:1; overflow-y:auto; padding:16px;
    display:flex; flex-direction:column; gap:10px;
    background:#f8f9fa;
}

.ai-msg { padding:10px 14px; border-radius:12px; font-size:.875rem; max-width:85%; line-height:1.5; }
.ai-msg-bot { background:white; color:#333; border:1px solid #e9ecef; align-self:flex-start; border-bottom-left-radius:4px; }
.ai-msg-user { background:linear-gradient(135deg,#667eea,#764ba2); color:white; align-self:flex-end; border-bottom-right-radius:4px; }
.ai-msg-typing { color:#6c757d; font-style:italic; }

#ai-chat-input-area {
    padding:12px; border-top:1px solid #e9ecef;
    display:flex; gap:8px; background:white;
}

#ai-user-input {
    flex:1; padding:10px 14px; border:2px solid #e9ecef;
    border-radius:24px; font-size:.875rem; outline:none; transition:.3s;
}
#ai-user-input:focus { border-color:#667eea; }

#ai-send-btn {
    width:40px; height:40px; border-radius:50%; border:none;
    background:linear-gradient(135deg,#667eea,#764ba2);
    color:white; cursor:pointer; display:flex;
    align-items:center; justify-content:center; transition:.3s;
}
#ai-send-btn:hover { transform:scale(1.1); }

@media(max-width:480px) {
    #ai-chat-window { width:calc(100vw - 32px); right:-8px; }
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
    div.innerHTML = (role !== 'user' ? '<i class="bi bi-robot" style="margin-right:6px"></i>' : '') + escapeHtml(text);
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
            innerHTML: '<i class="bi bi-robot" style="margin-right:6px"></i>Sedang mengetik...'
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
        const reply = data.reply || 'Maaf, terjadi kesalahan.';

        typing.remove();
        appendMessage('bot', reply);
        aiMessages.push({ role: 'assistant', content: reply });

    } catch (e) {
        typing.remove();
        appendMessage('bot', 'Koneksi gagal. Silakan coba lagi.');
    }

    document.getElementById('ai-send-btn').disabled = false;
    document.getElementById('ai-user-input').focus();
}
</script>