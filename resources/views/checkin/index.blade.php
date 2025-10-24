<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Check-in • Scanner</title>
  <style>
    html,body{height:100%;margin:0;background:#000;color:#fff;font-family:system-ui, -apple-system, Segoe UI, Roboto, Arial}
    #wrap{position:fixed;inset:0}
    video{width:100%;height:100%;object-fit:cover}
    #hud{position:absolute;top:10px;left:10px;right:10px;display:flex;gap:8px;align-items:center;flex-wrap:wrap}
    .btn{background:#1116;border:1px solid #444;border-radius:10px;padding:8px 12px;color:#fff;cursor:pointer}
    #log{position:absolute;left:0;right:0;bottom:0;max-height:40vh;overflow:auto;background:rgba(0,0,0,.55);font:14px/1.4 monospace;padding:8px}
    .ok{color:#8cff9a}.warn{color:#ffd28c}.err{color:#ff9a8c}

    /* Modal konfirmasi */
    #modal{
      position:fixed;inset:0;display:none;place-items:center;background:rgba(0,0,0,.55);backdrop-filter: blur(4px);z-index:20
    }
    .modal-card{
      width:min(520px,calc(100% - 24px));
      background:#0b0b0b;border:1px solid #333;border-radius:14px;padding:16px 16px 12px
    }
    .modal-title{margin:4px 0 10px;font-weight:700;font-size:18px}
    .modal-body{font-size:15px;line-height:1.5}
    .modal-actions{display:flex;gap:8px;justify-content:flex-end;margin-top:14px}
    .btn-primary{background:#1f8b4c;border:1px solid #2aa263}
    .btn-ghost{background:#1116;border:1px solid #444}
  </style>
</head>
<body>
<div id="wrap">
  <video id="cam" autoplay playsinline></video>
  <canvas id="canvas" style="display:none"></canvas>

  <div id="hud">
    <button class="btn" id="backBtn" onclick="history.back()">← Back</button>
    <div>Counter: <b id="count">0</b></div>
    <button class="btn" id="toggleOffline">Cache offline</button>
    <button class="btn" id="syncBtn" title="Paksa sinkron sekarang">Sync</button>
  </div>

  <div id="log"></div>
</div>

<!-- Modal -->
<div id="modal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
  <div class="modal-card">
    <div class="modal-title" id="modalTitle">Konfirmasi check-in</div>
    <div class="modal-body" id="modalBody">
      <!-- diisi via JS -->
    </div>
    <div class="modal-actions">
      <button class="btn btn-ghost" id="btnCancel">Batal</button>
      <button class="btn btn-primary" id="btnConfirm">Benar, check-in</button>
    </div>
  </div>
</div>

<script src="https://unpkg.com/jsqr/dist/jsQR.js"></script>
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const log = (m, cls='') => {
  const el = document.getElementById('log');
  el.insertAdjacentHTML('afterbegin', `<div class="${cls}">${new Date().toLocaleTimeString()} — ${m}</div>`);
};
let offline = false, cache = new Map(), scans = [], last='', pending = null;

document.getElementById('toggleOffline').onclick = async () => {
  try {
    const res = await fetch('/checkin/cache.json', {credentials:'same-origin'});
    const data = await res.json();
    cache.clear();
    (data.data || []).forEach(x=>cache.set(x.qr_hash, true));
    offline = true;
    log('Offline cache loaded: '+cache.size, 'ok');
  } catch(e){ log('Gagal load cache: '+e.message, 'err'); }
};

document.getElementById('syncBtn').onclick = syncNow;

async function startCam(){
  const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' }});
  const video = document.getElementById('cam');
  video.srcObject = stream;

  const canvas = document.getElementById('canvas');
  const ctx = canvas.getContext('2d');

  async function tick(){
    if (video.readyState === video.HAVE_ENOUGH_DATA) {
      canvas.width = video.videoWidth; canvas.height = video.videoHeight;
      ctx.drawImage(video,0,0,canvas.width,canvas.height);
      const img = ctx.getImageData(0,0,canvas.width,canvas.height);
      const code = jsQR(img.data, img.width, img.height);
      if (code && code.data) await handleScan(code.data);
    }
    requestAnimationFrame(tick);
  }
  tick();
}

async function sha256Hex(text){
  const buf = new TextEncoder().encode(text);
  const hash = await crypto.subtle.digest('SHA-256', buf);
  return [...new Uint8Array(hash)].map(b=>b.toString(16).padStart(2,'0')).join('');
}

async function handleScan(payload){
  if (payload === last) return; // cegah spam frame yang sama
  last = payload;

  if (offline) {
    const h = await sha256Hex(payload);
    if (!cache.has(h)) return log('QR tidak dikenal (OFFLINE)', 'err');
    scans.push({qr_hash: h, at: Date.now()});
    incCount(); log('OFFLINE OK', 'ok');
    return;
  }

  // Mode online: pertama PREVIEW untuk ambil nama, lalu konfirmasi, lalu FINALIZE
  try {
    const preview = await callVerify({payload, preview: true});
    // server ideal balas: {ok:true, name, code, status:'VALID'|'USED'|'UNKNOWN'|'DUPLICATE', msg?}

    if (!preview.ok) {
      const s = (preview.status || '').toUpperCase();
      if (s === 'USED' || s === 'DUPLICATE') {
        return log(preview.msg || 'QR sudah dipakai.', 'warn');
      }
      return log(preview.msg || 'QR tidak valid.', 'err');
    }

    // Status valid → minta konfirmasi nama
    if (preview.status && preview.status.toUpperCase() === 'USED') {
      return log('QR sudah dipakai.', 'warn');
    }

    showConfirm(preview.name || 'Tanpa nama', preview.code || '', payload);

  } catch(e){
    log('ERR: '+e.message, 'err');
  }
}

function showConfirm(name, code, payload){
  pending = { payload, name, code };
  document.getElementById('modalBody').innerHTML =
    `<div>Nama: <b>${escapeHtml(name)}</b></div>` +
    (code ? `<div>Kode: <b>${escapeHtml(code)}</b></div>` : '') +
    `<div style="margin-top:4px;font-size:13px;color:#bbb">Pastikan orangnya benar sebelum check-in.</div>`;
  document.getElementById('modal').style.display = 'grid';
}

document.getElementById('btnCancel').onclick = () => {
  pending = null;
  document.getElementById('modal').style.display = 'none';
};

document.getElementById('btnConfirm').onclick = async () => {
  if (!pending) return;
  try {
    // FINALIZE: minta server tandai used
    const res = await callVerify({payload: pending.payload, confirm: true});
    if (res.ok) {
      incCount();
      log('CHECK-IN OK: '+(pending.name || ''), 'ok');
    } else if ((res.status || '').toUpperCase() === 'USED') {
      log(res.msg || 'QR sudah dipakai.', 'warn');
    } else {
      log(res.msg || 'Gagal check-in.', 'err');
    }
  } catch(e){
    log('ERR: '+e.message, 'err');
  } finally {
    pending = null;
    document.getElementById('modal').style.display = 'none';
  }
};

async function callVerify(body){
  const res = await fetch('/checkin/verify', {
    method:'POST',
    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
    body: JSON.stringify(body)
  });
  // Tetap coba baca JSON walau status bukan 2xx
  return res.json().catch(() => ({ ok:false, msg:'Bad response', status:'ERROR' }));
}

function incCount(){
  const el = document.getElementById('count');
  el.textContent = (parseInt(el.textContent||'0',10)+1).toString();
}

window.addEventListener('online', syncNow);
async function syncNow(){
  if (!offline || scans.length===0) return;
  try {
    const res = await fetch('/checkin/sync', {
      method:'POST',
      headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
      body: JSON.stringify({scans})
    });
    const j = await res.json();
    log('SYNC updated: '+(j.updated||0), 'ok');
    scans = [];
  } catch(e){ log('SYNC gagal: '+e.message, 'err'); }
}

function escapeHtml(s){ return (s||'').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c])); }

startCam().catch(e=>log('Camera error: '+e.message, 'err'));
</script>
</body>
</html>
