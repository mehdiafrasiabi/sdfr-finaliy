<div>
    @push('link')
        <style>
            html { scroll-behavior: smooth; }

            /* ============ Brand helpers ============ */
            .text-brand   { color: hsl(var(--primary)); }
            .bg-brand     { background: hsl(var(--primary)); }
            .bg-brand-soft{ background: hsl(var(--primary) / .10); }
            .border-brand { border-color: hsl(var(--primary)); }
            .border-brand-soft { border-color: hsl(var(--primary) / .30); }

            /* ---------- Buttons ---------- */
            .btn-brand { display:inline-flex; align-items:center; justify-content:center; gap:.5rem;
                height:3rem; padding:0 1.85rem; border-radius:999px; font-weight:800; font-size:.9rem;
                background:hsl(var(--primary)); color:hsl(var(--primary-foreground));
                box-shadow:0 12px 32px -10px hsl(var(--primary) / .6);
                transition:transform .25s, box-shadow .25s, filter .25s; cursor:pointer; }
            .btn-brand:hover { transform:translateY(-2px); filter:brightness(1.06); box-shadow:0 18px 42px -10px hsl(var(--primary) / .75); }
            .btn-ghost { display:inline-flex; align-items:center; justify-content:center; gap:.5rem;
                height:3rem; padding:0 1.85rem; border-radius:999px; font-weight:800; font-size:.9rem;
                color:hsl(var(--foreground)); transition:border-color .25s, transform .25s; cursor:pointer; }
            .btn-ghost:hover { transform:translateY(-2px); border-color:hsl(var(--primary) / .6) !important; }

            /* ---------- Glass (home) ---------- */
            .glass-home { background: hsl(var(--background) / .5);
                -webkit-backdrop-filter: blur(18px) saturate(140%); backdrop-filter: blur(18px) saturate(140%);
                border: 1px solid hsl(var(--border) / .65); }

            /* ---------- Grid bg ---------- */
            .grid-bg { background-image: linear-gradient(to right, hsl(var(--border) / .35) 1px, transparent 1px),
            linear-gradient(to bottom, hsl(var(--border) / .35) 1px, transparent 1px); background-size: 46px 46px;
                -webkit-mask-image: radial-gradient(ellipse 80% 70% at 50% 45%, #000 35%, transparent 100%);
                mask-image: radial-gradient(ellipse 80% 70% at 50% 45%, #000 35%, transparent 100%); }

            /* ---------- Shimmer ---------- */
            @keyframes shimmer { 0%{background-position:-200% 0} 100%{background-position:200% 0} }
            .shimmer-text { background: linear-gradient(90deg, hsl(var(--foreground)) 0%, #38bdf8 35%, hsl(var(--primary)) 65%, hsl(var(--foreground)) 100%);
                background-size: 200% 100%; -webkit-background-clip:text; background-clip:text;
                -webkit-text-fill-color:transparent; color:transparent; animation: shimmer 6s linear infinite; }

            /* ---------- Reveal ---------- */
            .reveal, .reveal-up { opacity:0; transform:translateY(30px);
                transition:opacity .8s cubic-bezier(.16,1,.3,1), transform .8s cubic-bezier(.16,1,.3,1); will-change:opacity,transform; }
            .reveal.is-visible, .reveal-up.is-visible { opacity:1; transform:translateY(0); }
            .rv-d1{transition-delay:.08s}.rv-d2{transition-delay:.16s}.rv-d3{transition-delay:.24s}

            /* ================== SECTION 1 : INTRO (single screen) ================== */
            .hero-screen { position:relative; min-height:100vh; display:flex; align-items:center; justify-content:center; overflow:hidden; }
            .hero-blue { position:absolute; inset:0; z-index:0; pointer-events:none;
                background:
                    radial-gradient(ellipse 95% 80% at 50% 62%, rgba(23,60,128,.55), rgba(10,26,62,.30) 42%, transparent 78%),
                    radial-gradient(ellipse 70% 50% at 50% 12%, rgba(56,90,170,.18), transparent 70%); }
            .hero-intro { position:relative; z-index:10; text-align:center; padding:0 1.25rem; }

            /* ================== SECTION 1.5 : STORY (black, scroll-driven Q&A) ================== */
            .story-track  { height: 360vh; position: relative; }
            .story-sticky { position: sticky; top:0; height:100vh; overflow:hidden; background:#000;
                display:flex; align-items:center; justify-content:center; }
            .story-layer  { position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center;
                text-align:center; padding:0 1.5rem; opacity:0; will-change:opacity,transform; }
            .story-q { font-weight:900; font-size:clamp(21px,5.6vw,36px); color:#fff; line-height:1.6; max-width:42ch;
                opacity:0; transform:translateY(18px); transition:opacity .55s cubic-bezier(.16,1,.3,1), transform .55s cubic-bezier(.16,1,.3,1); }
            .story-a { font-weight:800; font-size:clamp(18px,5vw,30px); color:#5b6473; margin-top:1rem;
                opacity:0; transform:translateY(12px); transition:opacity .55s ease, transform .55s ease; }
            .story-q.on, .story-a.on { opacity:1; transform:none; }
            .story-msg h2 { font-weight:900; font-size:clamp(27px,7vw,54px); color:#fff; line-height:1.45; }
            .story-brand { color:#38bdf8; text-shadow:0 0 42px rgba(56,189,248,.55); }
            .story-msg p { font-weight:500; color:#9aa3b2; font-size:clamp(14px,3.8vw,18px); line-height:2.1; margin-top:1.1rem; max-width:44ch; }
            .story-badge { display:inline-flex; align-items:center; gap:.45rem; font-weight:900; letter-spacing:.6px;
                color:#38bdf8; background:rgba(56,189,248,.12); border:1px solid rgba(56,189,248,.32);
                padding:.45rem 1.1rem; border-radius:999px; font-size:13px; margin-bottom:1.3rem; }
            .story-chips { display:flex; flex-wrap:wrap; gap:.6rem; justify-content:center; margin-top:1.7rem; }
            .story-chip { font-weight:800; font-size:13px; color:#cbd5e1; background:rgba(255,255,255,.06);
                border:1px solid rgba(255,255,255,.12); padding:.5rem 1rem; border-radius:999px; }

            /* ================== SECTION 2 : PHONE (pinned, rises) ================== */
            .phone-track  { height: 240vh; position: relative; }
            .phone-sticky { position: sticky; top: 0; height: 100vh; overflow: hidden; }
            .hero-stage { position:absolute; inset:0; z-index:5; display:flex; align-items:center; justify-content:center; will-change:opacity; }

            /* ---------- Device (iPhone Pro) — dark bezel ---------- */
            .device {
                position: relative; z-index: 5;
                aspect-ratio: 430 / 932; border-radius: 50px;
                background: linear-gradient(160deg, #1a1c22, #050507);
                padding: 9px;
                box-shadow: 0 55px 110px -25px rgba(0,0,0,.9), inset 0 0 0 2px rgba(255,255,255,.06), inset 0 0 0 7px #000;
                width: auto; height: min(80vh, 660px); will-change: transform;
            }
            @media (min-width: 768px) { .device { height: min(82vh, 780px); border-radius: 60px; padding: 12px; } }

            .device-screen {
                position: relative; width: 100%; height: 100%; border-radius: 41px; overflow: hidden;
                background: radial-gradient(circle at 50% 16%, #11336a 0%, #0a1f48 34%, #04102b 64%, #01060f 100%);
                display: flex; flex-direction: column;
            }
            @media (min-width: 768px) { .device-screen { border-radius: 49px; } }

            .device-island { position: absolute; top: 20px; left: 50%; transform: translateX(-50%);
                width: 78px; height: 22px; background: #000; border-radius: 20px; z-index: 6; }
            .device-status { display: flex; align-items: center; justify-content: space-between;
                padding: 14px 20px 4px; direction: ltr; position: relative; z-index: 7; }
            .device-status .t { font-size: 13px; font-weight: 800; color: #fff; }
            .device-status .ic { display: flex; gap: 5px; align-items: center; color: #fff; opacity: .9; }

            .device-chat { flex: 1; display: flex; flex-direction: column; gap: 9px; padding: 20px 11px 22px; direction: rtl; overflow: hidden; }
            .chat-bubble { max-width: 84%; font-size: 13px; line-height: 1.9; border-radius: 18px; padding: 9px 13px;
                opacity: 0; transform: translateY(10px) scale(.96); transition: opacity .5s ease, transform .5s ease; }
            .chat-bubble.shown { opacity: 1; transform: none; }
            .chat-bubble--me  { align-self: flex-end; background: hsl(var(--primary)); color: #fff; border-bottom-left-radius: 5px; }
            .chat-bubble--bot { align-self: flex-start; background: rgba(255,255,255,.09); color: #e6eeff; border-bottom-right-radius: 5px; }

            /* ---------- Floating step-labels ---------- */
            .hero-label { position:absolute; z-index:6; font-weight:900; white-space:nowrap; letter-spacing:-.01em; line-height:1.3;
                text-align:center; pointer-events:none; left:50%; transform:translate(-50%,-22px);
                font-size:clamp(18px,6vw,28px); text-shadow:0 0 0 transparent; opacity:0;
                transition:opacity .7s cubic-bezier(.16,1,.3,1), transform .7s cubic-bezier(.16,1,.3,1), text-shadow .7s ease; }
            .hero-label.on { opacity:1; transform:translate(-50%,0); }
            .hl-1 { color:#38bdf8; top:5%; }
            .hl-2 { color:#a78bfa; top:5%; }
            .hl-3 { color:#34d399; top:5%; }
            .hl-1.on { text-shadow:0 0 30px rgba(56,189,248,.8); }
            .hl-2.on { text-shadow:0 0 30px rgba(167,139,250,.8); }
            .hl-3.on { text-shadow:0 0 30px rgba(52,211,153,.8); }
            @media (min-width:768px){
                .hero-label { left:auto; right:auto; text-align:right; font-size:clamp(20px,2vw,32px); transform:translateY(-22px); }
                .hero-label.on { transform:translateY(0); }
                .hl-1 { top:25%; right:13%; left:auto; }
                .hl-2 { top:48%; left:13%; right:auto; text-align:left; }
                .hl-3 { top:auto; bottom:19%; right:15%; left:auto; }
            }

            /* ================== TRUST (pinned) ================== */
            .logos-track  { height: 140vh; position: relative; }
            .logos-sticky { position: sticky; top: 0; height: 100vh; overflow: hidden; display:flex; align-items:center; justify-content:center; }
            .logos-marq { position:absolute; inset:0; z-index:2; display:flex; flex-direction:column; justify-content:space-between;
                padding:24vh 0; opacity:0; will-change:opacity; }
            .marq-mask { overflow:hidden; width:100%;
                -webkit-mask-image: linear-gradient(to right, transparent, #000 12%, #000 88%, transparent);
                mask-image: linear-gradient(to right, transparent, #000 12%, #000 88%, transparent); }
            .marq-row { display:flex; width:max-content; gap:1rem; will-change:transform; }
            .logos-veil { position:absolute; inset:0; z-index:3; pointer-events:none;
                background: radial-gradient(ellipse 60% 50% at 50% 50%, hsl(var(--background)) 0%, hsl(var(--background) / .7) 45%, transparent 75%); }
            .logos-text { position:absolute; z-index:4; text-align:center; padding:0 1rem; pointer-events:none; will-change:opacity,transform; }

            /* ================== FEATURES ================== */
            .feat-desktop { display:none; } .feat-mobile { display:block; }
            @media (min-width:768px){ .feat-desktop{ display:block; } .feat-mobile{ display:none; } }

            .feat-track  { height: 560vh; position:relative; }
            .feat-sticky { position:sticky; top:0; height:100vh; overflow:hidden; display:flex; flex-direction:column; align-items:center; justify-content:center; }

            .acc-item { position:relative; padding:14px 16px; border-radius:18px; cursor:default;
                transition:background .4s, border-color .4s, transform .4s; border:1px solid hsl(var(--border) / .5);
                background: linear-gradient(209deg, rgb(146 146 146 / 16%), rgb(0 0 0)); -webkit-backdrop-filter: blur(14px) saturate(140%); backdrop-filter: blur(14px) saturate(140%); }
            .acc-item.active { background:hsl(var(--primary) / .08); border-color:hsl(var(--primary) / .35); }
            .acc-head { display:flex; align-items:center; gap:12px; opacity:.55; transition:opacity .4s; }
            .acc-item.active .acc-head { opacity:1; }
            .acc-ico { display:flex; align-items:center; justify-content:center; width:42px; height:42px; border-radius:13px; flex:none;
                background:hsl(var(--primary) / .12); color:hsl(var(--primary)); border:1px solid hsl(var(--primary) / .22); transition:.4s; }
            .acc-item.active .acc-ico { background:hsl(var(--primary)); color:hsl(var(--primary-foreground)); border-color:transparent; box-shadow:0 10px 24px -8px hsl(var(--primary) / .7); }
            .acc-desc { max-height:0; opacity:0; overflow:hidden; padding-right:54px; transition:max-height .5s ease, opacity .5s ease, margin .5s ease; }
            .acc-item.active .acc-desc { max-height:170px; opacity:1; margin-top:10px; }

            .feat-stage { position:relative; width:100%; height:min(54vh,430px); border-radius:30px; }
            .feat-preview { position:absolute; inset:0; opacity:0; transform:translateY(26px) scale(.97);
                transition:opacity .6s cubic-bezier(.16,1,.3,1), transform .6s cubic-bezier(.16,1,.3,1); pointer-events:none; }
            .feat-preview.active { opacity:1; transform:none; pointer-events:auto; }

            /* ================== Feature preview mock-ups (.fp-*) ================== */
            .fp-root { height:100%; width:100%; display:flex; flex-direction:column; overflow:hidden; background:#f6f8fc; color:#0f172a; border-radius:inherit; font-family:inherit; }
            .fp-topbar { display:flex; align-items:center; justify-content:space-between; gap:8px; padding:9px 13px; background:#fff; border-bottom:1px solid #e7ecf3; }
            .fp-dot { width:9px; height:9px; border-radius:50%; display:inline-block; }
            .fp-tab { font-size:11px; font-weight:800; color:#475569; }
            .fp-avatar { width:26px; height:26px; border-radius:50%; background:hsl(var(--primary)); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:12px; }
            .fp-body { flex:1; padding:13px; overflow:hidden; display:flex; flex-direction:column; }
            .fp-card { background:#fff; border:1px solid #e7ecf3; border-radius:14px; padding:12px; }
            .fp-card-title { font-size:11px; font-weight:800; color:#334155; }
            .fp-badge { font-size:10px; font-weight:800; color:hsl(var(--primary)); background:hsl(var(--primary)/.10); padding:2px 8px; border-radius:999px; }
            .fp-stat { background:#fff; border:1px solid #e7ecf3; border-radius:13px; padding:10px; display:flex; flex-direction:column; gap:3px; }
            .fp-stat-label { font-size:9.5px; color:#64748b; font-weight:700; }
            .fp-stat-num { font-size:18px; font-weight:900; color:#0f172a; line-height:1; }
            .fp-stat-num small { font-size:9px; font-weight:700; color:#94a3b8; margin-right:3px; }
            .fp-stat-up { font-size:9px; font-weight:800; color:#16a34a; }
            .fp-bars { display:flex; align-items:flex-end; gap:7px; flex:1; min-height:96px; padding:8px 2px 0; }
            .fp-bar { flex:1; background:linear-gradient(180deg, hsl(var(--primary)), hsl(var(--primary)/.35)); border-radius:6px 6px 0 0; min-height:6px; }
            .fp-bars-x { display:flex; gap:7px; padding:5px 2px 0; }
            .fp-bars-x span { flex:1; text-align:center; font-size:9px; color:#94a3b8; font-weight:700; }
            .fp-week { display:flex; gap:7px; }
            .fp-day { flex:1; background:#fff; border:1px solid #e7ecf3; border-radius:11px; padding:7px 5px; display:flex; flex-direction:column; gap:5px; min-height:150px; }
            .fp-day-h { font-size:8.5px; font-weight:800; color:#64748b; text-align:center; }
            .fp-slot { font-size:9px; font-weight:800; text-align:center; padding:6px 2px; border-radius:7px; border:1px solid transparent; }
            .fp-chat { display:flex; flex-direction:column; gap:7px; background:#eef2f8; }
            .fp-msg { max-width:82%; font-size:10.5px; line-height:1.7; padding:7px 9px; border-radius:12px; font-weight:600; }
            .fp-msg--in  { align-self:flex-start; background:#fff; color:#1e293b; border:1px solid #e7ecf3; border-bottom-right-radius:4px; }
            .fp-msg--out { align-self:flex-end; background:hsl(var(--primary)); color:#fff; border-bottom-left-radius:4px; }
            .fp-chat-input { margin-top:auto; display:flex; align-items:center; justify-content:space-between; background:#fff; border:1px solid #e7ecf3; border-radius:999px; padding:7px 12px; font-size:10px; color:#94a3b8; font-weight:700; }
            .fp-send { width:24px; height:24px; border-radius:50%; background:hsl(var(--primary)); color:#fff; display:flex; align-items:center; justify-content:center; font-size:11px; }
            .fp-q { background:#fff; border:1px solid #e7ecf3; border-radius:12px; padding:11px; font-size:11.5px; font-weight:800; color:#1e293b; line-height:1.9; }
            .fp-timer { font-size:10px; font-weight:800; color:#dc2626; background:#fee2e2; padding:2px 8px; border-radius:999px; }
            .fp-options { display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-top:10px; }
            .fp-opt { background:#fff; border:1px solid #e7ecf3; border-radius:10px; padding:9px; font-size:11px; font-weight:800; color:#334155; display:flex; align-items:center; justify-content:space-between; }
            .fp-opt--on { border-color:hsl(var(--primary)); background:hsl(var(--primary)/.08); color:hsl(var(--primary)); }
            .fp-opt i { font-style:normal; }
            .fp-progress { height:6px; background:#e7ecf3; border-radius:999px; margin-top:11px; overflow:hidden; }
            .fp-progress span { display:block; height:100%; background:hsl(var(--primary)); border-radius:999px; }
            .fp-skip { font-size:10px; font-weight:800; color:#94a3b8; }
            .fp-next { font-size:10px; font-weight:800; color:#fff; background:hsl(var(--primary)); padding:6px 14px; border-radius:999px; }
            .fp-donut { width:108px; flex:none; background:#fff; border:1px solid #e7ecf3; border-radius:14px; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:7px; padding:10px; }
            .fp-donut-ring { width:74px; height:74px; border-radius:50%; background:conic-gradient(hsl(var(--primary)) 0 78%, #e7ecf3 78% 100%); display:flex; align-items:center; justify-content:center; }
            .fp-donut-ring span { width:52px; height:52px; border-radius:50%; background:#fff; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:900; color:#0f172a; }
            .fp-donut-cap { font-size:9.5px; font-weight:800; color:#64748b; }
            .fp-sub-name { font-size:10.5px; font-weight:800; color:#334155; }
            .fp-sub-pct  { font-size:10.5px; font-weight:900; }
            .fp-track { height:7px; background:#e7ecf3; border-radius:999px; overflow:hidden; }
            .fp-track span { display:block; height:100%; border-radius:999px; }
            .fp-note { margin-top:11px; font-size:10px; font-weight:700; color:#475569; background:#fff; border:1px dashed #cbd5e1; border-radius:11px; padding:9px 11px; }

            /* ================== PRICING (livelier) ================== */
            .price-card { position:relative; transition: transform .4s cubic-bezier(.2,.8,.2,1), box-shadow .4s, border-color .4s; }
            .price-card:hover { transform: translateY(-8px); box-shadow: 0 34px 70px -28px hsl(var(--primary) / .5); border-color: hsl(var(--primary) / .5) !important; }
            .price-card .price-ico-row li { transition: transform .25s; }
            .price-card:hover .price-ico-row li { transform: translateX(-3px); }
            @keyframes priceRing { 0%{ box-shadow:0 0 0 0 hsl(var(--primary)/.35);} 70%{ box-shadow:0 0 0 14px hsl(var(--primary)/0);} 100%{ box-shadow:0 0 0 0 hsl(var(--primary)/0);} }
            .price-feat { animation: priceRing 3.2s ease-out infinite; }
            .price-feat:hover { animation-play-state: paused; }

            /* ---------- Inputs ---------- */
            .field { width:100%; height:2.85rem; background:hsl(var(--secondary) / .6); color:hsl(var(--foreground));
                border:1px solid hsl(var(--border)); border-radius:.85rem; padding:0 1rem; font-size:.85rem; transition:border-color .2s, box-shadow .2s, background .2s; }
            textarea.field { height:auto; padding:.7rem 1rem; resize:none; }
            .field::placeholder { color:hsl(var(--muted)); }
            .field:focus { outline:none; border-color:hsl(var(--primary)); background:hsl(var(--secondary) / .85); box-shadow:0 0 0 3px hsl(var(--primary) / .2); }

            /* ---------- header hide after the first screen ---------- */
            header { transition: transform .5s cubic-bezier(.16,1,.3,1), opacity .4s ease !important; will-change: transform, opacity; }
            header.is-hidden { transform: translateY(-110%) !important; opacity: 0 !important; pointer-events: none !important; }

            /* ---------- Reduced motion ---------- */
            .reduce-motion .story-track, .reduce-motion .phone-track, .reduce-motion .logos-track, .reduce-motion .feat-track { height:auto !important; }
            .reduce-motion .story-sticky, .reduce-motion .phone-sticky, .reduce-motion .logos-sticky, .reduce-motion .feat-sticky { position:static !important; height:auto !important; padding-block:3.5rem; }
            .reduce-motion .story-layer { position:relative; opacity:1 !important; margin-block:2rem; transform:none !important; }
            .reduce-motion .story-q, .reduce-motion .story-a { opacity:1 !important; transform:none !important; }
            .reduce-motion .hero-stage { position:static; margin-top:2rem; opacity:1 !important; }
            .reduce-motion .logos-marq { position:static; opacity:.55 !important; }
            .reduce-motion .logos-veil { display:none; }
            .reduce-motion .feat-desktop { display:none !important; }
            .reduce-motion .feat-mobile  { display:block !important; }
            @media (prefers-reduced-motion: reduce){
                *, *::before, *::after { animation-duration:.01ms !important; animation-iteration-count:1 !important; transition-duration:.01ms !important; scroll-behavior:auto !important; }
            }

            /* ---------- Cosmic backdrop ---------- */
            .space-fx { position:absolute; inset:0; z-index:0; pointer-events:none; overflow:hidden;
                background:
                    radial-gradient(42vw 42vw at 8% 5%,   rgba(99,102,241,.10), transparent 70%),
                    radial-gradient(46vw 46vw at 95% 16%,  rgba(56,189,248,.08), transparent 72%),
                    radial-gradient(54vw 54vw at 78% 52%,  rgba(139,92,246,.09), transparent 72%),
                    radial-gradient(40vw 40vw at 12% 84%,  rgba(56,189,248,.07), transparent 72%); }
            .space-fx .sdfr-lines { z-index:0; }
        </style>
    @endpush
    <div id="home-root" dir="rtl" class="relative">
        <div class="space-fx" aria-hidden="true">
            <x-cosmic-lines color="#7dd3fc" />
        </div>
        @php
            $features = [
                ['id'=>'dashboard','t'=>'داشبورد هوشمند','d'=>'نمای لحظه‌ای از ساعت مطالعه، برنامه‌ی امروز و وضعیت کلی دانش‌آموز در یک نگاه؛ همه‌چیز زنده و به‌روز.',
                 'icon'=>'<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>'],
                ['id'=>'planning','t'=>'برنامه‌ریزی هفتگی','d'=>'برنامه‌ی مطالعاتی اختصاصی هر دانش‌آموز با جزئیات کامل ساعت، آزمون و درس که توسط مشاور تنظیم می‌شود.',
                 'icon'=>'<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>'],
                ['id'=>'consult','t'=>'اتاق مشاوره','d'=>'گفتگوی مستقیم و جلسات هدفمند با مشاوران تخصصی برای هدایت مسیر تحصیلی؛ ارتباط مستمر، کلید موفقیت است.',
                 'icon'=>'<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8z"/>'],
                ['id'=>'exam','t'=>'آزمون‌های آنلاین','d'=>'برگزاری آزمون‌های پیشرفته همراه با تصحیح و بازخورد تخصصی برای سنجش دقیق پیشرفت و شبیه‌سازی شرایط کنکور.',
                 'icon'=>'<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m22 4-10 10.01-3-3"/>'],
                ['id'=>'report','t'=>'کارنامه و تحلیل','d'=>'کارنامه‌ی هوشمند و نمودارهای پیشرفت که نقاط ضعف و قوت را شفاف نشان می‌دهد تا برنامه‌ریزی هدفمندتر شود.',
                 'icon'=>'<path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/>'],
            ];
            // تصویر هر ویژگی (فعلاً فیک). بعداً با اسکرین‌شات واقعی جایگزین کن. خالی بگذاری -> ماک‌آپ HTML برمی‌گردد.
            $featImgs = [
                'dashboard' => 'https://placehold.co/900x680/0b1e4a/38bdf8?text=Dashboard',
                'planning'  => 'https://placehold.co/900x680/0b1e4a/38bdf8?text=Weekly+Program',
                'consult'   => 'https://placehold.co/900x680/0b1e4a/38bdf8?text=Consulting+Room',
                'exam'      => 'https://placehold.co/900x680/0b1e4a/38bdf8?text=Online+Exam',
                'report'    => 'https://placehold.co/900x680/0b1e4a/38bdf8?text=Report',
            ];
            $logos = ['دبیرستان فرزانگان','مجتمع علامه حلی','دبیرستان شهید بهشتی','آموزشگاه نمونه','ماندگار البرز','دبیرستان دکتر حسابی','مجتمع نیکان','دبیرستان مفید','آموزشگاه اندیشه','مدرسه‌ی سلام','دبیرستان رشد','مجتمع آفرینش'];
            $longLogos = array_merge($logos, $logos, $logos);
        @endphp
        {{-- ============================= SECTION 1 : INTRO ============================= --}}
        <section id="hero" class="hero-screen relative z-10">
            <div class="hero-blue"></div>
            <div class="absolute inset-0 grid-bg pointer-events-none" style="z-index:1;"></div>

            <div class="hero-intro">
                <div class="inline-flex items-center gap-2 glass-home rounded-full px-4 py-2 mb-5">
                    <span class="relative flex w-2 h-2">
                        <span class="absolute inline-flex w-full h-full bg-brand rounded-full opacity-75 animate-ping"></span>
                        <span class="relative inline-flex w-2 h-2 bg-brand rounded-full"></span>
                    </span>
                    <span class="font-semibold text-[11px] sm:text-xs text-foreground">پلتفرم هوشمند پایش و مشاوره‌ی تحصیلی</span>
                </div>
                <h1 class="font-black text-4xl sm:text-5xl md:text-6xl text-foreground" style="line-height:1.35">
                    مسیر موفقیت تحصیلی‌ات،<br>
                    <span class="shimmer-text">هوشمند</span> و بی‌وقفه
                </h1>
                <p class="font-medium text-sm sm:text-base text-muted leading-8 max-w-xl mx-auto mt-5">
                    با <span class="font-black text-foreground">SDFR</span> ساعت مطالعه ثبت می‌شود، برنامه‌ی اختصاصی می‌گیری و هوش مصنوعی هر روز عملکردت را تحلیل می‌کند.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3 mt-7">
                    <a href="{{ route('client.auth.login') }}" class="btn-brand group w-60 sm:w-auto">
                        <span>شروع رایگان</span>
                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                    </a>
                    <a href="#features" class="btn-ghost glass-home w-60 sm:w-auto">مشاهده ویژگی‌ها</a>
                </div>
            </div>
        </section>

        {{-- ============================= SECTION 1.5 : STORY (black) ============================= --}}
        <section id="story" class="relative z-10" wire:ignore>
            <div id="story-track" class="story-track">
                <div class="story-sticky">

                    {{-- Q&A pairs (each scroll reveals a line) --}}
                    <div class="story-layer story-pair" data-pair="0">
                        <div class="story-q">بنظرت شب درس خوندن بهتره یا روز؟</div>
                        <div class="story-a">نمی‌دانم!</div>
                    </div>
                    <div class="story-layer story-pair" data-pair="1">
                        <div class="story-q">موقع یادگیری، راه‌رفتن بهتره یا دراز کشیدن؟</div>
                        <div class="story-a">نمی‌دانم!</div>
                    </div>
                    <div class="story-layer story-pair" data-pair="2">
                        <div class="story-q">بحث و چالش با پدر و مادرم چقدر روی درس‌خوندنم اثر می‌ذاره؟</div>
                        <div class="story-a">نمی‌دانم…</div>
                    </div>

                    {{-- message 1 : SDFR is here --}}
                    <div class="story-layer story-msg" id="story-help">
                        <h2>اینجاست که <span class="story-brand">SDFR</span><br>کنارت می‌ایستد</h2>
                        <p>تا جوابِ همین سؤال‌ها را، دقیق و مخصوصِ خودت، پیدا کنی.</p>
                    </div>

                    {{-- message 2 : Mindset Test --}}
                    <div class="story-layer story-msg" id="story-mindset">
                        <span class="story-badge">⚡ Mindset Test</span>
                        <h2>آنالیزِ دقیقِ ذهن و سبکِ یادگیریِ تو</h2>
                        <p>با یک آزمونِ کوتاهِ علمی، نقطه‌ی شروعت مشخص می‌شود: بهترین ساعاتِ مطالعه، الگوی تمرکز، سبکِ یادگیری و موانعِ ذهنی‌ات سنجیده می‌شود؛ بعد برنامه و مشاوره دقیقاً براساسِ همین تحلیل برایت چیده می‌شود.</p>
                        <div class="story-chips">
                            <span class="story-chip">الگوی تمرکز</span>
                            <span class="story-chip">ساعاتِ طلاییِ مطالعه</span>
                            <span class="story-chip">سبکِ یادگیری</span>
                            <span class="story-chip">موانعِ ذهنی</span>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- ============================= SECTION 2 : PHONE ============================= --}}
        <section id="phone" class="relative z-10" wire:ignore>
            <div id="phone-track" class="phone-track">
                <div class="phone-sticky">
                    <div class="hero-blue"></div>
                    <div class="absolute inset-0 grid-bg pointer-events-none" style="z-index:1;"></div>

                    <div id="hero-stage" class="hero-stage">
                        <span class="hero-label hl-1">تحلیل هوشمند</span>
                        <span class="hero-label hl-2">برنامه‌ی شخصی</span>
                        <span class="hero-label hl-3">پیشرفت روزانه</span>

                        <div id="hero-phone" class="device">
                            <div class="device-island"></div>
                            <div class="device-screen">
                                <div class="device-status">
                                    <span class="t">9:41</span>
                                    <span class="ic">
                                        <svg width="16" height="12" viewBox="0 0 18 12" fill="currentColor"><rect x="0" y="7" width="3" height="5" rx="1"/><rect x="5" y="4" width="3" height="8" rx="1"/><rect x="10" y="1.5" width="3" height="10.5" rx="1"/><rect x="15" y="0" width="3" height="12" rx="1" opacity=".4"/></svg>
                                        <svg width="16" height="12" viewBox="0 0 16 12" fill="currentColor"><path d="M8 2.5c2.3 0 4.4.9 6 2.4l-1.4 1.5A6.6 6.6 0 0 0 8 4.6c-1.8 0-3.4.7-4.6 1.8L2 4.9A8.6 8.6 0 0 1 8 2.5Zm0 3.6c1.3 0 2.5.5 3.4 1.3L8 11 4.6 7.4A4.9 4.9 0 0 1 8 6.1Z"/></svg>
                                        <svg width="23" height="12" viewBox="0 0 26 12" fill="none"><rect x="1" y="1" width="21" height="10" rx="3" stroke="currentColor" stroke-opacity=".5"/><rect x="3" y="3" width="16" height="6" rx="1.5" fill="currentColor"/><rect x="23.5" y="4" width="1.5" height="4" rx=".75" fill="currentColor"/></svg>
                                    </span>
                                </div>
                                <div id="hero-chat" class="device-chat">
                                    <div class="chat-bubble chat-bubble--me">سلام! نمی‌دونم امروز از کجا شروع کنم 😅</div>
                                    <div class="chat-bubble chat-bubble--bot">سلام 👋 طبق برنامه‌ت، الان وقت ریاضیه: ۴۵ دقیقه مبحثِ مشتق.</div>
                                    <div class="chat-bubble chat-bubble--me">باشه، بعدش چی؟</div>
                                    <div class="chat-bubble chat-bubble--bot">یه آزمون کوتاهِ ۱۰ سؤالی می‌گیرم تا نقاط ضعفت مشخص بشه.</div>
                                    <div class="chat-bubble chat-bubble--bot">امروز تا اینجا ۳ ساعت و ۲۰ دقیقه مطالعه‌ی مفید داشتی، عالیه! 🔥</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============================= TRUST (Section 3) ============================= --}}
        <section id="logos" class="relative z-10" wire:ignore>
            <div id="logos-track" class="logos-track">
                <div class="logos-sticky">
                    <div id="logos-marq" class="logos-marq">
                        <div class="marq-mask">
                            <div class="marq-row" id="marq-top">
                                @foreach($longLogos as $logo)
                                    <div class="flex-shrink-0 flex items-center justify-center h-14 sm:h-16 px-5 sm:px-7 glass-home rounded-2xl">
                                        <span class="font-bold text-xs sm:text-sm text-muted whitespace-nowrap">{{ $logo }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="marq-mask">
                            <div class="marq-row" id="marq-bottom">
                                @foreach($longLogos as $logo)
                                    <div class="flex-shrink-0 flex items-center justify-center h-14 sm:h-16 px-5 sm:px-7 glass-home rounded-2xl">
                                        <span class="font-bold text-xs sm:text-sm text-muted whitespace-nowrap">{{ $logo }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="logos-veil"></div>

                    <div id="logos-text" class="logos-text">
                        <h2 class="font-black text-3xl md:text-5xl text-foreground" style="text-shadow:0 4px 30px hsl(var(--background)),0 2px 10px hsl(var(--background));">بهترین‌ها به ما اعتماد کردند</h2>
                        <p class="font-medium text-sm md:text-base text-muted mt-3 max-w-md mx-auto" style="text-shadow:0 2px 14px hsl(var(--background));">مدارس و آموزشگاه‌هایی که مسیر دانش‌آموزانشان را به SDFR سپرده‌اند.</p>
                    </div>
                </div>
            </div>
        </section>


        <div class="relative z-10 max-w-7xl mx-auto px-4 space-y-16 md:space-y-24 pb-24">

            {{-- ===================== FEATURES (Section 4) ===================== --}}
            <section id="features" class="relative scroll-mt-24" wire:ignore>
                <div class="feat-desktop">
                    <div id="feat-track" class="feat-track">
                        <div class="feat-sticky">
                            <div class="w-full">
                                <div class="text-center space-y-2 max-w-3xl mx-auto mb-6">
                                    <div class="inline-flex items-center gap-2 glass-home rounded-full px-3 py-1.5">
                                        <svg class="w-3.5 h-3.5 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2 2 7l10 5 10-5-10-5z"/><path d="m2 17 10 5 10-5"/><path d="m2 12 10 5 10-5"/></svg>
                                        <span class="font-semibold text-xs text-foreground">امکانات پلتفرم</span>
                                    </div>
                                    <h2 class="font-black text-2xl md:text-4xl text-foreground">همه‌ی ابزارها در <span class="shimmer-text">یک پلتفرم</span></h2>
                                </div>
                                <div class="grid grid-cols-12 gap-10 items-center">
                                    <div class="col-span-5 space-y-2.5">
                                        @foreach($features as $i => $f)
                                            <div class="acc-item" data-i="{{ $i }}">
                                                <div class="acc-head">
                                                    <span class="acc-ico">
                                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $f['icon'] !!}</svg>
                                                    </span>
                                                    <h3 class="font-black text-xl text-foreground">{{ $f['t'] }}</h3>
                                                </div>
                                                <div class="acc-desc"><p class="font-medium text-sm text-muted leading-8">{{ $f['d'] }}</p></div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-span-7">
                                        <div class="feat-stage glass-home rounded-[30px] p-2 shadow-2xl">
                                            @foreach($features as $i => $f)
                                                <div class="feat-preview" data-i="{{ $i }}">
                                                    <div class="w-full h-full rounded-[24px] overflow-hidden">
                                                        @if(!empty($featImgs[$f['id']]))
                                                            <img src="{{ $featImgs[$f['id']] }}" alt="{{ $f['t'] }}" loading="lazy" class="w-full h-full object-cover">
                                                        @else
                                                            @include('livewire.client.home.feature-preview', ['type' => $f['id']])
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="feat-mobile">
                    <div class="text-center space-y-2 max-w-3xl mx-auto">
                        <div class="inline-flex items-center gap-2 glass-home rounded-full px-3 py-1.5">
                            <svg class="w-3.5 h-3.5 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2 2 7l10 5 10-5-10-5z"/><path d="m2 17 10 5 10-5"/><path d="m2 12 10 5 10-5"/></svg>
                            <span class="font-semibold text-xs text-foreground">امکانات پلتفرم</span>
                        </div>
                        <h2 class="font-black text-2xl text-foreground">همه‌ی ابزارها در <span class="shimmer-text">یک پلتفرم</span></h2>
                    </div>
                    <div class="mt-6 space-y-7">
                        @foreach($features as $f)
                            <div class="reveal-up glass rounded-3xl p-2.5">
                                <div class="rounded-2xl overflow-hidden" style="height:250px;">
                                    @if(!empty($featImgs[$f['id']]))
                                        <img src="{{ $featImgs[$f['id']] }}" alt="{{ $f['t'] }}" loading="lazy" class="w-full h-full object-cover">
                                    @else
                                        @include('livewire.client.home.feature-preview', ['type' => $f['id']])
                                    @endif
                                </div>
                                <div class="p-4">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="acc-ico" style="opacity:1;background:hsl(var(--primary));color:hsl(var(--primary-foreground));border-color:transparent;">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $f['icon'] !!}</svg>
                                        </span>
                                        <h3 class="font-black text-lg text-foreground">{{ $f['t'] }}</h3>
                                    </div>
                                    <p class="font-medium text-sm text-muted leading-8">{{ $f['d'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>


            {{-- ===================== PRICING ===================== --}}
            <section id="pricing" class="relative space-y-8 scroll-mt-24">
                <div class="text-center space-y-2 max-w-2xl mx-auto reveal-up">
                    <h2 class="font-black text-3xl md:text-4xl"><span class="shimmer-text">قیمت SDFR</span></h2>
                    <p class="font-medium text-sm text-muted px-4">با هفته‌ی آزمایشیِ رایگان شروع کن؛ هر وقت خواستی ادامه بده.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-5 max-w-5xl mx-auto items-stretch">
                    <div class="price-card glass rounded-3xl p-6 flex flex-col space-y-5 reveal-up">
                        <div class="space-y-1">
                            <h3 class="font-black text-xl text-foreground">هفته‌ی آزمایشی</h3>
                            <p class="font-medium text-xs text-muted">بدون نیاز به پرداخت، همین حالا شروع کن</p>
                        </div>
                        <div class="flex items-end gap-1 border-b border-border pb-5">
                            <span class="font-black text-3xl text-foreground">رایگان</span>
                            <span class="text-xs text-muted pb-1">۷ روز کامل</span>
                        </div>
                        <ul class="space-y-3 flex-1 price-ico-row">
                            @foreach(['دسترسی کامل به مدت ۷ روز','برنامه‌ی هفتگی آزمایشی','ثبت ساعت مطالعه','آشنایی با مشاور و پلتفرم'] as $it)
                                <li class="flex items-center gap-2.5">
                                    <span class="flex items-center justify-center w-5 h-5 bg-brand-soft text-brand border border-brand-soft rounded-md shrink-0">
                                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    </span>
                                    <span class="font-semibold text-xs text-foreground">{{ $it }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('client.onboarding') }}" class="btn-ghost glass-home w-full">شروع هفته‌ی آزمایشی</a>
                    </div>

                    <div class="price-card price-feat glass relative rounded-3xl p-6 flex flex-col space-y-5 bg-brand-soft border-2 border-brand md:-translate-y-3 mt-3 md:mt-0 reveal-up rv-d1" style="box-shadow:0 30px 60px -25px hsl(var(--primary)/.5);">
                        <div class="absolute -top-3 left-1/2 -translate-x-1/2 inline-flex items-center bg-brand text-white font-black text-[11px] rounded-full px-4 py-1 shadow-lg">پیشنهاد ویژه</div>
                        <div class="space-y-1">
                            <h3 class="font-black text-xl text-foreground">نقدی</h3>
                            <p class="font-medium text-xs text-muted">دسترسی کامل به همه‌ی امکانات</p>
                        </div>
                        <div class="flex items-end gap-1 border-b border-border pb-5">
                            @if($monthlyPrice)
                                <span class="font-black text-3xl text-foreground">{{ number_format((int) $monthlyPrice) }}</span>
                                <span class="text-xs text-muted pb-1">تومان / ماه</span>
                            @else
                                <span class="font-black text-3xl text-foreground">به‌زودی</span>
                                <span class="text-xs text-muted pb-1">اعلام قیمت</span>
                            @endif
                        </div>
                        <ul class="space-y-3 flex-1 price-ico-row">
                            @foreach(['همه‌ی امکانات پلتفرم','مشاور تخصصی اختصاصی','برنامه‌ی کاملاً شخصی‌سازی‌شده','آزمون‌های آنلاین نامحدود','تحلیل هوشمند با هوش مصنوعی','پشتیبانی اولویت‌دار'] as $it)
                                <li class="flex items-center gap-2.5">
                                    <span class="flex items-center justify-center w-5 h-5 bg-brand text-white rounded-md shrink-0">
                                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    </span>
                                    <span class="font-semibold text-xs text-foreground">{{ $it }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="flex items-center gap-2 rounded-xl px-3 py-2.5" style="background:hsl(var(--primary)/.10);border:1px solid hsl(var(--primary)/.25);">
                            <svg class="w-4 h-4 text-brand shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>
                            <span class="font-bold text-[11px] text-foreground">امکان پرداخت اقساطی با ۳۰٪ پیش‌پرداخت</span>
                        </div>
                        <a href="{{ route('client.onboarding') }}" class="btn-brand w-full">شروع ثبت‌نام</a>
                    </div>

                    <div class="price-card glass rounded-3xl p-6 flex flex-col space-y-5 reveal-up rv-d2">
                        <div class="space-y-1">
                            <div class="flex items-center justify-between">
                                <h3 class="font-black text-xl text-foreground">مدارس</h3>
                                <span class="font-medium text-[10px] text-muted">طرح همکاری</span>
                            </div>
                            <p class="font-medium text-xs text-muted">ویژه‌ی مدارس و آموزشگاه‌ها</p>
                        </div>
                        <div class="flex items-end gap-1 border-b border-border pb-5">
                            <span class="font-black text-2xl text-foreground">قرارداد اختصاصی</span>
                        </div>
                        <ul class="space-y-3 flex-1 price-ico-row">
                            @foreach(['پنل مدیریتی مدرسه','گزارش‌گیری دوره‌ای و تحلیلی','مدیر موفقیت اختصاصی','تعرفه‌ی پلکانی هر دانش‌آموز','پشتیبانی و قرارداد ویژه'] as $it)
                                <li class="flex items-center gap-2.5">
                                    <span class="flex items-center justify-center w-5 h-5 bg-brand-soft text-brand border border-brand-soft rounded-md shrink-0">
                                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    </span>
                                    <span class="font-semibold text-xs text-foreground">{{ $it }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('client.schools') }}" class="btn-ghost glass-home w-full">مشاهده قرارداد مدارس</a>
                    </div>
                </div>
            </section>


            {{-- ===================== CONTACT ===================== --}}
            <section id="contact" class="relative space-y-8 scroll-mt-24 reveal-up">
                <div class="text-center space-y-2 max-w-2xl mx-auto">
                    <div class="inline-flex items-center gap-2 glass-home rounded-full px-4 py-2">
                        <svg class="w-3.5 h-3.5 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 5L2 7"/></svg>
                        <span class="font-semibold text-xs text-foreground">تماس با ما</span>
                    </div>
                    <h2 class="font-black text-2xl md:text-4xl text-foreground">آماده‌ی شروع هستی؟</h2>
                    <p class="font-medium text-sm md:text-base text-muted px-4">سؤالی داری یا به مشاوره نیاز داری؟ تیم ما آماده‌ی پاسخگویی به توست.</p>
                </div>

                <div class="grid md:grid-cols-12 gap-6">
                    <div class="md:col-span-7">
                        <div class="glass rounded-3xl p-5 sm:p-6 md:p-8">
                            @if (session('contact_sent'))
                                <div class="mb-5 flex items-center gap-2 rounded-2xl px-4 py-3" style="background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);color:#34d399;">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m22 4-10 10.01-3-3"/></svg>
                                    <span class="font-semibold text-sm">پیامت با موفقیت ارسال شد. به‌زودی پاسخت را می‌دهیم.</span>
                                </div>
                            @endif

                            <form wire:submit="submitContact" class="space-y-5">
                                <div class="grid sm:grid-cols-2 gap-5">
                                    <div class="space-y-2">
                                        <label class="font-semibold text-xs text-muted">نام و نام خانوادگی</label>
                                        <input type="text" wire:model="contact_name" placeholder="نام خود را وارد کن" class="field">
                                        @error('contact_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="space-y-2">
                                        <label class="font-semibold text-xs text-muted">ایمیل</label>
                                        <input type="email" dir="ltr" wire:model="contact_email" placeholder="example@email.com" class="field text-right">
                                        @error('contact_email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="space-y-2">
                                        <label class="font-semibold text-xs text-muted">شماره تماس</label>
                                        <input type="tel" dir="ltr" wire:model="contact_phone" placeholder="09123456789" class="field text-right">
                                        @error('contact_phone') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="space-y-2">
                                        <label class="font-semibold text-xs text-muted">موضوع</label>
                                        <input type="text" wire:model="contact_subject" placeholder="موضوع پیامت را بنویس" class="field">
                                        @error('contact_subject') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="font-semibold text-xs text-muted">پیام</label>
                                    <textarea wire:model="contact_message" rows="5" placeholder="پیام خود را اینجا بنویس..." class="field"></textarea>
                                    @error('contact_message') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <button type="submit" class="btn-brand w-full">
                                    <span wire:loading.remove wire:target="submitContact">ارسال پیام</span>
                                    <span wire:loading wire:target="submitContact">در حال ارسال...</span>
                                    <svg wire:loading.remove wire:target="submitContact" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="md:col-span-5 space-y-4">
                        @php
                            $contactCards = [
                                ['t'=>'ایمیل','v'=>'info@sdfr.me','d'=>'پاسخگویی سریع در اسرع وقت','icon'=>'<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 5L2 7"/>'],
                                ['t'=>'تلفن تماس','v'=>'۰۲۱-۱۲۳۴۵۶۷۸','d'=>'تماس مستقیم با تیم ما','icon'=>'<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/>'],
                                ['t'=>'تلگرام','v'=>'@sdfr_support','d'=>'پشتیبانی سریع در تلگرام','icon'=>'<path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/>'],
                                ['t'=>'پشتیبانی آنلاین','v'=>'۲۴/۷ پاسخگویی','d'=>'همیشه در کنار تو هستیم','icon'=>'<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>'],
                            ];
                        @endphp
                        @foreach($contactCards as $c)
                            <div class="glass rounded-2xl p-5 flex items-center gap-4">
                                <span class="flex items-center justify-center w-12 h-12 bg-brand-soft text-brand border border-brand-soft rounded-xl shrink-0">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $c['icon'] !!}</svg>
                                </span>
                                <div class="flex-1 min-w-0">
                                    <div class="font-black text-sm text-foreground">{{ $c['t'] }}</div>
                                    <div class="font-bold text-sm text-brand truncate" dir="ltr">{{ $c['v'] }}</div>
                                    <div class="font-medium text-[11px] text-muted">{{ $c['d'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>


            {{-- ===================== FINAL CTA ===================== --}}
            <section class="reveal-up">
                <div class="relative rounded-3xl glass-home overflow-hidden">
                    <div class="absolute inset-0 grid-bg pointer-events-none"></div>
                    <div class="hero-blue" style="opacity:.5"></div>
                    <div class="relative max-w-2xl mx-auto text-center space-y-6 p-7 sm:p-10 md:p-14">
                        <h2 class="font-black text-2xl md:text-4xl text-foreground leading-tight">آماده‌ای مسیر تحصیلت را <span class="shimmer-text">هوشمند</span> کنی؟</h2>
                        <p class="font-medium text-sm md:text-base text-muted leading-8">همین امروز به جمع هزاران دانش‌آموزی بپیوند که با SDFR یادگیری را شفاف، هدفمند و قابل‌اندازه‌گیری کرده‌اند.</p>
                        <div class="flex flex-wrap items-center justify-center gap-3">
                            <a href="{{ route('client.auth.login') }}" class="btn-brand group">
                                <span>شروع رایگان</span>
                                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                            </a>
                            <a href="#contact" class="btn-ghost glass-home">تماس با ما</a>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>

    @push('script')
        <script>
            (function () {
                var hooked = false;

                function initHome() {
                    var root = document.getElementById('home-root');
                    if (!root || root.dataset.inited) return;
                    root.dataset.inited = '1';

                    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                    function clamp(v,a,b){ return Math.max(a, Math.min(b, v)); }

                    var header  = document.querySelector('header.header-main') || document.querySelector('header');

                    // STORY
                    var storyTrack = document.getElementById('story-track');
                    var storyHelp  = document.getElementById('story-help');
                    var storyMindset = document.getElementById('story-mindset');
                    var storyPairs = [];
                    root.querySelectorAll('#story .story-pair').forEach(function (wrap) {
                        storyPairs.push({ wrap: wrap, q: wrap.querySelector('.story-q'), a: wrap.querySelector('.story-a') });
                    });
                    // reveal windows for the 3 pairs: [Q-threshold, A-threshold, fade-out]
                    var pairTh = [ [0.03,0.10,0.17], [0.19,0.26,0.33], [0.35,0.42,0.50] ];

                    // PHONE
                    var phoneTrack = document.getElementById('phone-track');
                    var phone      = document.getElementById('hero-phone');
                    var labels     = root.querySelectorAll('#phone .hero-label');
                    var bubbles    = root.querySelectorAll('#hero-chat .chat-bubble');

                    // TRUST
                    var logosTrack = document.getElementById('logos-track');
                    var logosText  = document.getElementById('logos-text');
                    var logosMarq  = document.getElementById('logos-marq');
                    var marqTop    = document.getElementById('marq-top');
                    var marqBottom = document.getElementById('marq-bottom');

                    // FEATURES
                    var featTrack = document.getElementById('feat-track');
                    var accItems  = root.querySelectorAll('.feat-desktop .acc-item');
                    var previews  = root.querySelectorAll('.feat-desktop .feat-preview');

                    function revealVisible() {
                        root.querySelectorAll('.reveal, .reveal-up').forEach(function (el) {
                            if (el.getBoundingClientRect().top < window.innerHeight * 0.92) el.classList.add('is-visible');
                        });
                    }
                    var io = new IntersectionObserver(function (es) {
                        es.forEach(function (e) { if (e.isIntersecting) e.target.classList.add('is-visible'); });
                    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
                    root.querySelectorAll('.reveal, .reveal-up').forEach(function (el) { io.observe(el); });

                    function progress(el) {
                        var r = el.getBoundingClientRect();
                        var total = el.offsetHeight - window.innerHeight;
                        if (total <= 0) return 0;
                        return clamp((-r.top) / total, 0, 1);
                    }

                    /* ===== STORY : Q&A one-by-one -> "SDFR" -> "Mindset Test" ===== */
                    function setStory(p) {
                        for (var i = 0; i < storyPairs.length; i++) {
                            var pr = storyPairs[i], th = pairTh[i];
                            var op = clamp((p - th[0]) / 0.03, 0, 1) * (1 - clamp((p - th[2]) / 0.05, 0, 1));
                            pr.wrap.style.opacity = op.toFixed(3);
                            pr.q.classList.toggle('on', p >= th[0]);
                            pr.a.classList.toggle('on', p >= th[1]);
                        }
                        if (storyHelp) {
                            var hi = clamp((p - 0.56) / 0.06, 0, 1), ho = clamp((p - 0.72) / 0.06, 0, 1);
                            storyHelp.style.opacity = (hi * (1 - ho)).toFixed(3);
                            storyHelp.style.transform = 'translateY(' + (18 * (1 - hi)).toFixed(1) + 'px)';
                        }
                        if (storyMindset) {
                            var mi = clamp((p - 0.78) / 0.10, 0, 1);
                            storyMindset.style.opacity = mi.toFixed(3);
                            storyMindset.style.transform = 'translateY(' + (22 * (1 - mi)).toFixed(1) + 'px)';
                        }
                    }

                    function setLabelStyle(el, op, blur, y) {
                        if (!el) return;
                        el.style.transition = 'none';
                        el.style.opacity = op.toFixed(3);
                        el.style.filter = 'blur(' + blur.toFixed(2) + 'px)';
                        el.style.transform = 'translate(-50%,' + y.toFixed(1) + 'px)';
                        el.style.textShadow = op > 0.12 ? '0 0 26px currentColor' : 'none';
                    }
                    function setLabels(cP) {
                        if (window.innerWidth < 768) {
                            labels.forEach(function (l) { l.classList.remove('on'); });
                            var o0 = clamp(cP / 0.15, 0, 1);
                            var up0 = clamp((cP - 0.28) / 0.15, 0, 1);
                            setLabelStyle(labels[0], o0 * (1 - up0), 0, -40 * up0);

                            var o1 = clamp((cP - 0.35) / 0.15, 0, 1);
                            var rise1 = 1 - clamp((cP - 0.35) / 0.15, 0, 1);
                            var up1 = clamp((cP - 0.65) / 0.15, 0, 1);
                            setLabelStyle(labels[1], o1 * (1 - up1), rise1 * 4, (rise1 * 30) - (40 * up1));

                            var o2 = clamp((cP - 0.70) / 0.15, 0, 1);
                            var rise2 = 1 - clamp((cP - 0.70) / 0.20, 0, 1);
                            setLabelStyle(labels[2], o2, rise2 * 4, rise2 * 70);
                        } else {
                            var st = [0.04, 0.30, 0.56], en = [0.58, 0.84, 1.06];
                            labels.forEach(function (l, i) {
                                l.style.opacity = ''; l.style.filter = ''; l.style.transform = ''; l.style.textShadow = ''; l.style.transition = '';
                                l.classList.toggle('on', cP >= st[i] && cP < en[i]);
                            });
                        }
                    }

                    /* ===== PHONE : rises from bottom -> bubbles one-by-one -> labels (phone stays visible) ===== */
                    function setPhone(p) {
                        var riseT = clamp((p - 0.05) / 0.30, 0, 1);
                        if (phone) {
                            var startOff = window.innerHeight * 0.5 + phone.offsetHeight * 0.20;
                            var off = startOff * (1 - riseT);
                            var s = 0.9 + 0.1 * riseT;
                            phone.style.transform = 'translateY(' + off.toFixed(1) + 'px) scale(' + s.toFixed(3) + ')';
                        }
                        var bP = clamp((p - 0.12) / 0.50, 0, 1);
                        var n = bubbles.length;
                        var show = Math.max(1, Math.min(n, Math.ceil((bP / 0.8) * n)));
                        bubbles.forEach(function (b, i) { b.classList.toggle('shown', i < show); });

                        setLabels(clamp((p - 0.42) / 0.58, 0, 1));
                    }

                    function setLogos(p) {
                        if (logosText) logosText.style.opacity = clamp(p / 0.08, 0, 1);
                        if (logosMarq) logosMarq.style.opacity = clamp((p - 0.08) / 0.12, 0, 1) * 0.85;
                        var moveP = clamp((p - 0.16) / 0.84, 0, 1);
                        if (marqTop)    marqTop.style.transform    = 'translateX(' + (moveP * -26) + '%)';
                        if (marqBottom) marqBottom.style.transform = 'translateX(' + (moveP *  26) + '%)';
                    }

                    function setFeatures(p) {
                        if (!accItems.length) return;
                        var n = accItems.length;
                        var idx = Math.max(0, Math.min(n - 1, Math.floor(p * n * 0.999)));
                        accItems.forEach(function (it, i) { it.classList.toggle('active', i === idx); });
                        previews.forEach(function (pv, i) { pv.classList.toggle('active', i === idx); });
                    }

                    function updateHeader() {
                        if (!header) return;
                        header.classList.toggle('is-hidden', !reduce && window.scrollY > window.innerHeight * 0.6);
                    }

                    /* ---- heavy, consistent, reversible scroll (lerp) ---- */
                    var sT=0,sS=0, hT=0,hS=0, lT=0,lS=0, fT=0,fS=0, raf=null;
                    function loop() {
                        var k = 0.06; // smaller = heavier
                        sS += (sT-sS)*k; hS += (hT-hS)*k; lS += (lT-lS)*k; fS += (fT-fS)*k;
                        if (storyTrack) setStory(sS);
                        if (phoneTrack) setPhone(hS);
                        if (logosTrack) setLogos(lS);
                        if (featTrack && window.innerWidth >= 768) setFeatures(fS);
                        if (Math.abs(sT-sS)>0.0004 || Math.abs(hT-hS)>0.0004 || Math.abs(lT-lS)>0.0004 || Math.abs(fT-fS)>0.0004) {
                            raf = requestAnimationFrame(loop);
                        } else {
                            sS=sT; hS=hT; lS=lT; fS=fT;
                            if (storyTrack) setStory(sS);
                            if (phoneTrack) setPhone(hS);
                            if (logosTrack) setLogos(lS);
                            if (featTrack && window.innerWidth >= 768) setFeatures(fS);
                            raf = null;
                        }
                    }
                    function onScroll() {
                        if (storyTrack) sT = progress(storyTrack);
                        if (phoneTrack) hT = progress(phoneTrack);
                        if (logosTrack) lT = progress(logosTrack);
                        fT = (featTrack && window.innerWidth >= 768) ? progress(featTrack) : 0;
                        updateHeader();
                        if (!raf) raf = requestAnimationFrame(loop);
                    }

                    function reapply() {
                        revealVisible();
                        if (reduce) return;
                        if (accItems.length && !root.querySelector('.feat-preview.active')) {
                            if (accItems[0]) accItems[0].classList.add('active');
                            if (previews[0]) previews[0].classList.add('active');
                        }
                        onScroll();
                    }
                    function registerHook() {
                        if (hooked || !window.Livewire || !Livewire.hook) return;
                        hooked = true;
                        Livewire.hook('commit', function (payload) {
                            if (payload && typeof payload.succeed === 'function') {
                                payload.succeed(function () { requestAnimationFrame(reapply); });
                            }
                        });
                    }
                    if (window.Livewire && Livewire.hook) registerHook();
                    else document.addEventListener('livewire:init', registerHook);

                    if (reduce) {
                        root.classList.add('reduce-motion');
                        if (header) header.classList.remove('is-hidden');
                        storyPairs.forEach(function (pr) { pr.wrap.style.opacity = 1; pr.q.classList.add('on'); pr.a.classList.add('on'); });
                        if (storyHelp) storyHelp.style.opacity = 1;
                        if (storyMindset) storyMindset.style.opacity = 1;
                        if (phone) phone.style.transform = 'none';
                        labels.forEach(function (l) { l.classList.add('on'); });
                        bubbles.forEach(function (b) { b.classList.add('shown'); });
                        if (logosText) logosText.style.opacity = 1;
                        if (logosMarq) logosMarq.style.opacity = 0.55;
                        revealVisible();
                        return;
                    }

                    if (accItems[0]) accItems[0].classList.add('active');
                    if (previews[0]) previews[0].classList.add('active');

                    window.addEventListener('scroll', onScroll, { passive: true });
                    window.addEventListener('resize', onScroll, { passive: true });
                    onScroll();
                }

                document.addEventListener('DOMContentLoaded', initHome);
                document.addEventListener('livewire:navigated', initHome);
                if (document.readyState !== 'loading') initHome();
            })();
        </script>
    @endpush
</div>
