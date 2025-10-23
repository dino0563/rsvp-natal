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
    .ok{color:#8cff9a}.err{color:#ff9a8c}
  </style>
</head>
<body>
<div id="wrap">
  <video id="cam" autoplay playsinline></video>
  <canvas id="canvas" style="display:none"></canvas>

  <div id="hud">
    <div>Counter: <b id="count">0</b></div>
    <button class="btn" id="toggleOffline">Cache offline</button>
    <button class="btn" id="syncBtn" title="Paksa sinkron sekarang">Sync</button>
  </div>

  <div id="log"></div>
</div>

<script src="https://unpkg.com/jsqr/dist/jsQR.js"></script>
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const log = (m, cls='') => {
  const el = document.getElementById('log');
  el.insertAdjacentHTML('afterbegin', `<div class="${cls}">${new Date().toLocaleTimeString()} — ${m}</div>`);
};
let offline = false, cache = new Map(), scans = [], last='';

document.getElementById('toggleOffline').onclick = async () => {
  try {
    const res = await fetch('/checkin/cache.json', {credentials:'same-origin'});
    const data = await res.json();
    data.data.forEach(x=>cache.set(x.qr_hash, true));
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
      if (code && code.data) await verify(code.data);
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

async function verify(payload){
  if (payload === last) return;
  last = payload;

  if (offline) {
    const h = await sha256Hex(payload);
    if (!cache.has(h)) return log('QR tidak dikenal (offline)', 'err');
    scans.push({qr_hash: h, at: Date.now()});
    incCount(); log('OFFLINE OK', 'ok');
    return;
  }

  try {
    const res = await fetch('/checkin/verify', {
      method:'POST',
      headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
      body: JSON.stringify({payload})
    });
    const j = await res.json().catch(()=> ({}));
    if (res.ok && j.ok) { incCount(); log('OK: '+(j.name||''), 'ok'); }
    else log('ERR: '+(j.msg || res.status), 'err');
  } catch(e){ log('ERR: '+e.message, 'err'); }
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
      headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
      body: JSON.stringify({scans})
    });
    const j = await res.json();
    log('SYNC updated: '+(j.updated||0), 'ok');
    scans = [];
  } catch(e){ log('SYNC gagal: '+e.message, 'err'); }
}

startCam().catch(e=>log('Camera error: '+e.message, 'err'));
</script>
</body>
</html>
