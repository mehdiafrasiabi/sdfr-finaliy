<!DOCTYPE html>
<html lang="fa" dir="rtl" class="dark">
<head>
    <meta name="color-scheme" content="dark">
    <style>
        :root { color-scheme: dark; }
        /* ═══════ SDFR — Cosmic Light Lines (reusable) ═══════ */
        @property --sdfr-ang { syntax: '<angle>'; initial-value: 0deg; inherits: false; }

        /* ظرف خط‌های نوری — هرجا بذاری، روی همون والد پخش می‌شه */
        .sdfr-lines {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }

        .sdfr-lines .comet { position: absolute; transform-origin: center; }

        .sdfr-lines .comet .core {
            position: absolute;
            width: 3px; height: 3px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 0 8px 2px rgba(125, 211, 252, .9);
            opacity: 0;
            animation: sdfr-comet-fly var(--dur, 8s) ease-in infinite;
            animation-delay: var(--delay, 0s);
        }

        .sdfr-lines .comet .core::before {
            content: '';
            position: absolute;
            top: 50%; right: 3px;
            width: 170px; height: 2px;
            transform: translateY(-50%);
            border-radius: 2px;
            background: linear-gradient(to left, rgba(125, 211, 252, .95), rgba(125, 211, 252, 0));
        }

        @keyframes sdfr-comet-fly {
            0%   { transform: translateX(0);                  opacity: 0; }
            3%   { opacity: 1; }
            14%  { transform: translateX(var(--dist, 1400px)); opacity: 0; }
            100% { transform: translateX(var(--dist, 1400px)); opacity: 0; }
        }

        @media (prefers-reduced-motion: reduce) {
            .sdfr-lines .comet .core { animation: none !important; }
        }
    </style>
    <script>
        function mobileMenuHandler() {
            return {
                offcanvasOpen: false,
                profileModalOpen: false,
                desktopProfileOpen: false,
                isScrolled: false,
                isMobile: false,
                pwaBannerClosed: false,
                bannersHidden: false,
                isClosingMenu: false,
                init() {
                    this.checkMobile();
                    this.checkScroll();

                    window.addEventListener('scroll', () => this.checkScroll(), {passive: true});

                    // Watch برای قفل اسکرول
                    this.$watch('offcanvasOpen', (value) => {
                        if (value) {
                            document.body.style.overflow = 'hidden';
                        } else {
                            document.body.style.overflow = '';
                        }
                    });

                    this.$watch('profileModalOpen', (value) => {
                        if (value) {
                            document.body.style.overflow = 'hidden';
                        } else {
                            document.body.style.overflow = '';
                        }
                    });

                    this.$watch('desktopProfileOpen', (value) => {
                        if (value) {
                            document.body.style.overflow = 'hidden';
                        } else {
                            document.body.style.overflow = '';
                        }
                    });
                },

                checkMobile() {
                    this.isMobile = window.innerWidth < 1024;
                },

                checkScroll() {
                    this.isScrolled = window.scrollY > 50;
                },

                handleResize() {
                    this.checkMobile();
                    if (!this.isMobile && this.offcanvasOpen) {
                        this.closeMenu();
                    }
                    if (!this.isMobile && this.profileModalOpen) {
                        this.profileModalOpen = false;
                    }
                    if (this.isMobile && this.desktopProfileOpen) {
                        this.desktopProfileOpen = false;
                    }
                },

                toggleMenu() {
                    if (this.offcanvasOpen) {
                        this.closeMenu();
                    } else {
                        this.openMenu();
                    }
                },

                openMenu() {
                    this.profileModalOpen = false;
                    this.desktopProfileOpen = false;

                    if (!this.isScrolled) {
                        this.bannersHidden = true;
                    }

                    this.offcanvasOpen = true;
                },

                openProfileModal() {
                    if (this.offcanvasOpen) {
                        this.closeMenu();
                    }

                    this.profileModalOpen = true;
                },

                closeMenu() {
                    if (this.isClosingMenu) return;
                    this.isClosingMenu = true;

                    this.offcanvasOpen = false;

                    if (!this.isScrolled) {
                        setTimeout(() => {
                            this.bannersHidden = false;
                            this.isClosingMenu = false;
                        }, 350);
                    } else {
                        this.isClosingMenu = false;
                    }
                },

                scrollToTop() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
            }
        }
    </script>
    @include('layouts.client.link')
    @include('layouts.client.pwa')
    {!! SEO::generate() !!}
    <link rel="preload" href="/client/assets/images/theme/intro/header.png" as="image">
    <link rel="preload" href="/client/assets/images/favicon.svg" as="image" type="image/svg+xml">
</head>

<body class="dark">
<!-- container -->
<div class="flex flex-col min-h-screen bg-background">





    <!-- Loading Overlay برای نصب -->
    <div id="pwaLoading"
         class="hidden fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50">
        <div class=" rounded-lg p-8 text-center max-w-sm mx-4">
            <!-- Spinner -->

            <h3 class="text-xl font-bold text-green-500 mb-2">در حال نصب...</h3>
            <p class="text-foreground">لطفاً چند لحظه صبر کنید</p>
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                 class="justify-center"
                 viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" width="40px" height="40px"
                 style="shape-rendering: auto; display: block; background: transparent;">
                <g>
                    <path stroke="none" fill="#ffffff"
                          d="M19 50A31 31 0 0 0 81 50A31 34 0 0 1 19 50">
                        <animateTransform values="0 50 51.5;360 50 51.5" keyTimes="0;1"
                                          repeatCount="indefinite" dur="0.8130081300813008s"
                                          type="rotate" attributeName="transform"/>
                    </path>
                    <g/>
                </g>
            </svg>
        </div>
    </div>


    <livewire:client.layout.header/>

    <!-- end header -->

    <main class="flex-auto py-4">
        {{$slot}}
        @if(request()->routeIs('client.profile.*'))
            <x-cosmic-lines class="!fixed hidden dark:block" />
        @endif
    </main>

    <!-- footer -->
    <livewire:client.layout.footer/>
    <!-- end footer -->

    <!-- Floating Support Button -->
    <livewire:client.layout.floating-support/>

    <!-- Mobile Bottom Navigation - Fixed at bottom -->
    {{-- تا وقتی برنامه‌ی هفته آزمایشی ساخته نشده، منوی پایین موبایل نمایش داده نمی‌شود --}}
    @php
        $bottomNavTrial = auth()->user()?->trialWeek;
    @endphp
    @if(!$bottomNavTrial || $bottomNavTrial->status === \App\Models\TrialWeek::STATUS_PROGRAM_BUILT)
        <livewire:client.layout.mobile-bottom-nav/>
    @endif

    <div id="video-modal"
         class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 backdrop-blur-sm p-4">
        <div class="absolute inset-0" data-close-video></div>

        <div class="relative w-full max-w-4xl bg-background dark:bg-slate-900 rounded-2xl shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between p-4 md:p-5 border-b border-border">
                <h3 id="video-modal-title" class="text-lg font-bold text-foreground">ویدیو راهنما</h3>

                <button type="button" data-close-video
                        class="w-8 h-8 rounded-full hover:bg-secondary text-white flex items-center justify-center transition-colors">
                    ✕
                </button>
            </div>

            <div class="p-4 md:p-6">
                <div id="video-container" class="w-full aspect-video bg-black rounded-lg overflow-hidden"></div>
            </div>
        </div>
    </div>

</div>
@include('layouts.client.script')
{{--<script src="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.js"></script>--}}
<script data-navigate-once>
    //remove wire:snapshot form tags in client
    let attrs = [
        'snapshot',
        'effects',
    ];
    function snapKill() {
        document.querySelectorAll('div').forEach(function (element) {
            for (let i in attrs) {
                if (element.getAttribute(`wire:${attrs[i]}`) !== null) {
                    element.removeAttribute(`wire:${attrs[i]}`);
                }
            }
        });
    }
    window.addEventListener('load', (ev) => {
        snapKill();
    });
</script>
<script>
    function collapseGuide(key) {
        return {
            open: false,

            init() {
                const saved = localStorage.getItem(key);
                this.open = saved !== null ? saved === 'true' : true;
            },

            toggle() {
                this.open = !this.open;
                localStorage.setItem(key, this.open);
            }
        }
    }
</script>
<script>
    (function () {
        const modal = () => document.getElementById('video-modal');
        const container = () => document.getElementById('video-container');
        const titleEl = () => document.getElementById('video-modal-title');

        function openVideo({url, title}) {
            const m = modal();
            const c = container();
            if (!m || !c) return;

            c.innerHTML = '';

            // بهترین روش برای آپارات: iframe
            const iframe = document.createElement('iframe');
            iframe.src = url;
            iframe.setAttribute('allowfullscreen', 'true');
            iframe.setAttribute('allow', 'autoplay; encrypted-media');
            iframe.className = 'w-full h-full';
            iframe.style.border = '0';
            c.appendChild(iframe);

            if (titleEl() && title) titleEl().textContent = title;

            m.classList.remove('hidden');
            m.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeVideo() {
            const m = modal();
            const c = container();
            if (!m || !c) return;

            m.classList.add('hidden');
            m.classList.remove('flex');
            c.innerHTML = '';
            document.body.style.overflow = '';
        }

        // کلیک روی دکمه‌های باز کردن (حتی بعد از navigate)
        document.addEventListener('click', (e) => {
            const openBtn = e.target.closest('[data-video-url]');
            if (openBtn) {
                const url = openBtn.getAttribute('data-video-url');
                const title = openBtn.getAttribute('data-video-title') || 'ویدیو راهنما';
                if (url) openVideo({url, title});
                return;
            }

            // بستن مدال
            if (e.target.closest('[data-close-video]')) {
                closeVideo();
                return;
            }
        });

        // ESC برای بستن
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeVideo();
        });

        // Livewire v3: بعد از navigate اگر مدال باز مونده بود، ببند
        document.addEventListener('livewire:navigated', () => {
            closeVideo();
        });

        // برای استفاده در جاهای دیگه (اختیاری)
        window.openVideoModal = (url, title = 'ویدیو راهنما') => openVideo({url, title});
        window.closeVideoModal = closeVideo;
    })();
</script>

{{-- Keyframe Animations --}}
<style>
    @keyframes slideInUp {
        from { opacity: 0; transform: translateY(40px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes blink {
        0%, 100% { opacity: 0.7; }
        50%       { opacity: 0.3; }
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to   { opacity: 1; }
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px) scale(0.95); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
</style>

{{--
    🚀 Rocket Page Transition — Realistic Version
    قبل از </body> در app.blade.php قرار بده
--}}

<div id="rkt-overlay" style="display:none;position:fixed;inset:0;z-index:99999;background:#04080f;align-items:center;justify-content:center;overflow:hidden;">
    <canvas id="rkt-canvas" style="position:absolute;inset:0;width:100%;height:100%;"></canvas>
    <div id="rkt-msg" style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;pointer-events:none;opacity:0;transition:opacity 0.5s;z-index:2;direction:rtl;">
        <div id="rkt-ml1" style="font-size:22px;font-weight:600;color:#f38ba8;font-family:inherit;letter-spacing:.02em;"></div>
        <div id="rkt-ml2" style="font-size:14px;color:#a6adc8;margin-top:8px;font-family:inherit;"></div>
    </div>
</div>

<script data-navigate-once>
    (function(){
        const overlay=document.getElementById('rkt-overlay');
        const canvas=document.getElementById('rkt-canvas');
        const msgBox=document.getElementById('rkt-msg');
        const ml1=document.getElementById('rkt-ml1');
        const ml2=document.getElementById('rkt-ml2');
        const ctx=canvas.getContext('2d');

        let W=0,H=0,dpr=1;
        function resize(){
            dpr=window.devicePixelRatio||1;
            W=window.innerWidth; H=window.innerHeight;
            canvas.width=W*dpr; canvas.height=H*dpr;
            canvas.style.width=W+'px'; canvas.style.height=H+'px';
            ctx.setTransform(dpr,0,0,dpr,0,0);
        }

        const STARS=Array.from({length:160},()=>({
            x:Math.random()*3000,y:Math.random()*2000,
            r:0.3+Math.random()*1.4,tw:Math.random()*Math.PI*2
        }));

        let particles=[];
        function addP(x,y,vx,vy,life,color,size){particles.push({x,y,vx,vy,life,maxLife:life,color,size});}

        let curtainT=0,mode=null,phase=null,phaseT=0,raf=null,lastTs=0;
        let rkt={x:0,y:0,vy:0,vx:0,angle:0,opacity:1,flame:1};
        let vpn={y:0,hit:false,hitT:0};

        function resetRkt(){
            rkt.x=W/2;rkt.y=H*.78;rkt.vy=0;rkt.vx=0;
            rkt.angle=0;rkt.opacity=1;rkt.flame=1;
        }

        function showMsg(l1,l2,show){
            ml1.textContent=l1;ml2.textContent=l2;
            msgBox.style.opacity=show?'1':'0';
        }

        function stopAll(){
            if(raf){cancelAnimationFrame(raf);raf=null;}
            particles=[];curtainT=0;vpn.hit=false;vpn.hitT=0;
            showMsg('','',false);
            overlay.style.display='none';
        }

        function drawBG(){
            ctx.fillStyle='#04080f';ctx.fillRect(0,0,W,H);
            STARS.forEach(s=>{
                s.tw+=0.015;
                const a=0.28+0.22*Math.sin(s.tw);
                ctx.beginPath();ctx.arc(s.x%W,s.y%H,s.r,0,Math.PI*2);
                ctx.fillStyle=`rgba(200,210,255,${a})`;ctx.fill();
            });
        }

        function drawExhaust(x,y,size){
            if(size<=0)return;
            const g=ctx.createRadialGradient(x,y,0,x,y+size*.55,size);
            g.addColorStop(0,'rgba(255,255,200,.95)');
            g.addColorStop(.3,'rgba(255,155,30,.85)');
            g.addColorStop(.65,'rgba(220,70,10,.55)');
            g.addColorStop(1,'rgba(180,30,0,0)');
            ctx.save();ctx.translate(x,y);ctx.rotate(rkt.angle);ctx.translate(-x,-y);
            ctx.beginPath();ctx.ellipse(x,y+size*.5,size*.2,size*.6,0,0,Math.PI*2);
            ctx.fillStyle=g;ctx.fill();ctx.restore();
        }

        function drawSmoke(x,y,t){
            for(let i=0;i<5;i++){
                const age=(t*.8+i*.07)%0.6;
                const a=Math.max(0,.22-age*.37);
                ctx.beginPath();
                ctx.arc(x+(i%2?5:-5)*age*5,y+i*12+age*28,5+age*32,0,Math.PI*2);
                ctx.fillStyle=`rgba(120,135,155,${a})`;ctx.fill();
            }
        }

        function drawRocket(x,y,angle,sc,op,flame){
            ctx.save();
            ctx.globalAlpha=Math.max(0,op);
            ctx.translate(x,y);ctx.rotate(angle);ctx.scale(sc,sc);

            if(flame>0)drawExhaust(0,36,34*flame);

            const bg=ctx.createLinearGradient(-13,0,13,0);
            bg.addColorStop(0,'#131d38');bg.addColorStop(.35,'#3a4878');
            bg.addColorStop(.65,'#505e8a');bg.addColorStop(1,'#131d38');
            ctx.fillStyle=bg;ctx.beginPath();ctx.roundRect(-12,-28,24,58,5);ctx.fill();

            ctx.strokeStyle='rgba(255,255,255,0.1)';ctx.lineWidth=0.8;
            [-16,-6,4,14,24].forEach(yy=>{ctx.beginPath();ctx.moveTo(-12,yy);ctx.lineTo(12,yy);ctx.stroke();});

            const ng=ctx.createLinearGradient(-9,-60,9,-28);
            ng.addColorStop(0,'#b0bada');ng.addColorStop(.5,'#5060a0');ng.addColorStop(1,'#202c58');
            ctx.fillStyle=ng;ctx.beginPath();ctx.moveTo(-12,-28);ctx.quadraticCurveTo(0,-68,12,-28);ctx.closePath();ctx.fill();

            ctx.fillStyle='rgba(255,255,255,.55)';ctx.beginPath();ctx.ellipse(-2,-55,1.8,4,.3,0,Math.PI*2);ctx.fill();

            ctx.fillStyle='#080f1e';ctx.beginPath();ctx.ellipse(0,-10,6,7.5,0,0,Math.PI*2);ctx.fill();
            const wg=ctx.createRadialGradient(-1,-12,0,0,-10,7);
            wg.addColorStop(0,'rgba(140,210,255,.7)');wg.addColorStop(1,'rgba(20,80,150,.25)');
            ctx.fillStyle=wg;ctx.beginPath();ctx.ellipse(0,-10,6,7.5,0,0,Math.PI*2);ctx.fill();
            ctx.fillStyle='rgba(255,255,255,.55)';ctx.beginPath();ctx.ellipse(-2,-12.5,1.6,2.2,.3,0,Math.PI*2);ctx.fill();

            const fg=ctx.createLinearGradient(0,20,0,38);
            fg.addColorStop(0,'#2a3868');fg.addColorStop(1,'#111828');
            ctx.fillStyle=fg;
            ctx.beginPath();ctx.moveTo(-12,18);ctx.lineTo(-28,40);ctx.lineTo(-18,40);ctx.lineTo(-12,28);ctx.closePath();ctx.fill();
            ctx.beginPath();ctx.moveTo(12,18);ctx.lineTo(28,40);ctx.lineTo(18,40);ctx.lineTo(12,28);ctx.closePath();ctx.fill();

            ctx.fillStyle='#080c18';ctx.beginPath();ctx.ellipse(0,36,8.5,5.5,0,0,Math.PI*2);ctx.fill();
            ctx.strokeStyle='rgba(255,255,255,.15)';ctx.lineWidth=1;
            ctx.beginPath();ctx.ellipse(0,36,8.5,5.5,0,0,Math.PI*2);ctx.stroke();

            ctx.restore();
        }

        function drawVPNBar(y,shk,pulse){
            const bw=Math.min(W*.72,500),bx=(W-bw)/2,by=y-14+shk;
            ctx.save();
            ctx.shadowBlur=20+pulse*24;ctx.shadowColor=`rgba(230,80,80,${.35+pulse*.5})`;
            const barG=ctx.createLinearGradient(bx,by,bx,by+30);
            barG.addColorStop(0,'#3a2828');barG.addColorStop(.5,'#5a3838');barG.addColorStop(1,'#1a1010');
            ctx.fillStyle=barG;ctx.beginPath();ctx.roundRect(bx,by,bw,30,5);ctx.fill();
            ctx.save();ctx.beginPath();ctx.roundRect(bx,by,bw,30,5);ctx.clip();
            for(let i=0;i<bw/20+2;i++){
                ctx.fillStyle=i%2===0?'rgba(200,35,35,.5)':'rgba(210,155,0,.4)';
                ctx.beginPath();
                ctx.moveTo(bx+i*20-16,by);ctx.lineTo(bx+i*20+20-16,by);
                ctx.lineTo(bx+i*20+20-30-16,by+30);ctx.lineTo(bx+i*20-30-16,by+30);
                ctx.closePath();ctx.fill();
            }
            ctx.restore();
            ctx.strokeStyle=`rgba(230,75,75,${.55+pulse*.4})`;ctx.lineWidth=1.5;
            ctx.beginPath();ctx.roundRect(bx,by,bw,30,5);ctx.stroke();
            ctx.shadowBlur=0;
            ctx.font='bold 13px monospace';ctx.fillStyle='#f0eee8';ctx.textAlign='center';
            ctx.fillText('⛔  VPN BLOCKED — پرواز ممنوع',W/2,by+20);
            ctx.restore();
        }

        function drawCurtain(t){
            const e=1-Math.pow(1-Math.min(1,t),3);
            const half=W/2;
            const lg=ctx.createLinearGradient(-half*e,0,-half*e+half,0);
            lg.addColorStop(0,'#08101e');lg.addColorStop(.7,'#0e1828');lg.addColorStop(1,'#182038');
            ctx.fillStyle=lg;ctx.fillRect(-half*e,0,half,H);
            ctx.fillStyle='rgba(60,100,180,.2)';ctx.fillRect(-half*e+half-5,0,5,H);

            const rx=W/2+half*e;
            const rg=ctx.createLinearGradient(rx,0,rx+half,0);
            rg.addColorStop(0,'#182038');rg.addColorStop(.3,'#0e1828');rg.addColorStop(1,'#08101e');
            ctx.fillStyle=rg;ctx.fillRect(rx,0,half,H);
            ctx.fillStyle='rgba(60,100,180,.2)';ctx.fillRect(rx,0,5,H);
        }

        function drawParticles(){
            particles=particles.filter(p=>p.life>0);
            particles.forEach(p=>{
                p.x+=p.vx;p.y+=p.vy;p.vy+=0.05;p.life--;
                ctx.globalAlpha=(p.life/p.maxLife)*.9;
                ctx.fillStyle=p.color;
                ctx.beginPath();ctx.arc(p.x,p.y,p.size*(p.life/p.maxLife),0,Math.PI*2);ctx.fill();
            });
            ctx.globalAlpha=1;
        }

        function spawnHit(x,y){
            for(let i=0;i<48;i++){
                const a=Math.random()*Math.PI*2,s=2+Math.random()*4.5;
                const c=['#ff9944','#ffcc44','#ff4422','#ffffff','#ffaa00'][Math.floor(Math.random()*5)];
                addP(x,y,Math.cos(a)*s*(0.4+Math.random()),Math.sin(a)*s*(0.4+Math.random()),
                    35+Math.random()*25,c,2+Math.random()*2.5);
            }
        }

        function tick(ts){
            if(!lastTs)lastTs=ts;
            const dt=Math.min((ts-lastTs)/1000,.05);
            lastTs=ts;phaseT+=dt;
            resize();drawBG();drawParticles();
            if(mode==='launch')tickLaunch(dt);
            else if(mode==='slow')tickSlow(dt);
            else if(mode==='offline')tickOffline(dt);
            else if(mode==='vpn')tickVPN(dt);
            raf=requestAnimationFrame(tick);
        }

        function tickLaunch(dt){
            if(phase==='go'){
                rkt.vy-=310*dt;rkt.y+=rkt.vy*dt;
                rkt.flame=1+.08*Math.sin(phaseT*28);
                drawSmoke(rkt.x,rkt.y+52,phaseT);
                drawRocket(rkt.x,rkt.y,0,1,1,rkt.flame);
                for(let i=0;i<4;i++)
                    addP(rkt.x+(Math.random()-.5)*7,rkt.y+44,(Math.random()-.5)*1.5,2+Math.random()*2,
                        16+Math.random()*10,['#ff6622','#ffaa22','#ff3300'][Math.floor(Math.random()*3)],2.5);
                if(rkt.y<-90){phase='curtain';phaseT=0;}
            } else if(phase==='curtain'){
                curtainT=Math.min(1,phaseT*1.5);
                drawCurtain(curtainT);
                if(curtainT>=1)stopAll();
            }
        }

        function tickSlow(dt){
            if(phase==='go'){
                const stopY=H*.36;
                if(rkt.y>stopY){rkt.vy-=280*dt;rkt.y+=rkt.vy*dt;rkt.flame=.9;}
                else{rkt.y=stopY;rkt.vy=0;rkt.flame=.28+.12*Math.sin(phaseT*13);if(phaseT>.9){phase='warn';phaseT=0;}}
                drawSmoke(rkt.x,rkt.y+52,phaseT);
                drawRocket(rkt.x,rkt.y,0,1,1,rkt.flame);
                for(let i=0;i<2;i++)addP(rkt.x+(Math.random()-.5)*5,rkt.y+44,(Math.random()-.5)*.8,1.5,13,'#ff7733',2);
            } else if(phase==='warn'){
                rkt.y=H*.36+Math.sin(phaseT*7)*3.5;
                rkt.flame=.18+.12*Math.abs(Math.sin(phaseT*14));
                drawRocket(rkt.x,rkt.y,0,1,1,rkt.flame);
                const bx=W/2,by=H*.2;
                [10,16,22,28].forEach((h,i)=>{
                    ctx.fillStyle=i<2?'#f0c040':'rgba(255,255,255,.18)';
                    ctx.beginPath();ctx.roundRect(bx-22+i*14,by-h,10,h,2);ctx.fill();
                });
                ctx.font='14px monospace';ctx.fillStyle='#f0c040';ctx.textAlign='center';
                ctx.fillText('سرعت اینترنت پایین است',bx,by+22);
                if(phaseT>5)stopAll();
            }
        }

        function tickOffline(dt){
            if(phase==='go'){
                const stopY=H*.4;
                if(rkt.y>stopY&&rkt.vy>-180)rkt.vy-=255*dt;
                rkt.y+=rkt.vy*dt;rkt.flame=.75;
                drawSmoke(rkt.x,rkt.y+52,phaseT);
                drawRocket(rkt.x,rkt.y,0,1,1,rkt.flame);
                for(let i=0;i<2;i++)addP(rkt.x+(Math.random()-.5)*5,rkt.y+44,(Math.random()-.5),1.4,13,'#ff6622',2);
                if(rkt.y<=stopY&&phaseT>.5){phase='fall';phaseT=0;rkt.vy=0;}
            } else if(phase==='fall'){
                rkt.vy+=370*dt;rkt.vx-=85*dt;
                rkt.y+=rkt.vy*dt;rkt.x+=rkt.vx*dt;
                rkt.angle=Math.min(rkt.angle+2.5*dt,Math.PI*.42);
                rkt.opacity=Math.max(0,1-phaseT*1.1);rkt.flame=0;
                drawRocket(rkt.x,rkt.y,rkt.angle,1,rkt.opacity,0);
                if(phaseT>.5)showMsg('اتصال اینترنت قطع است','لطفاً اتصال خود را بررسی کنید',true);
                if(phaseT>4.5)stopAll();
            }
        }

        function tickVPN(dt){
            vpn.y=H*.3;
            if(phase==='go'){
                rkt.vy-=255*dt;rkt.y+=rkt.vy*dt;rkt.flame=1;
                drawSmoke(rkt.x,rkt.y+52,phaseT);
                drawVPNBar(vpn.y,0,0);
                drawRocket(rkt.x,rkt.y,0,1,1,rkt.flame);
                for(let i=0;i<3;i++)addP(rkt.x+(Math.random()-.5)*6,rkt.y+44,(Math.random()-.5)*1.5,2+Math.random()*2,16,'#ff7733',2.5);
                if(rkt.y<=vpn.y+44&&!vpn.hit){
                    vpn.hit=true;vpn.hitT=0;rkt.vy=100;
                    spawnHit(rkt.x,vpn.y+14);phase='bounce';phaseT=0;
                }
            } else if(phase==='bounce'){
                vpn.hitT+=dt;
                const shk=vpn.hitT<.3?(Math.random()-.5)*7:0;
                const pulse=Math.max(0,1-vpn.hitT*3);
                rkt.vy+=175*dt;rkt.y+=rkt.vy*dt;
                rkt.y=Math.max(rkt.y,vpn.y+50);
                rkt.flame=.35+.13*Math.sin(phaseT*16);
                drawVPNBar(vpn.y,shk,pulse);
                drawRocket(rkt.x,rkt.y,0,1,1,rkt.flame);
                if(phaseT>.8)showMsg('VPN فعال است','برای ادامه، لطفاً VPN را خاموش کنید',true);
                if(phaseT>5)stopAll();
            }
        }

        /* ── detect connection state ── */
        function detectState(cb){
            if(!navigator.onLine){cb('offline');return;}
            const t0=performance.now();
            const img=new Image();
            const timeout=setTimeout(()=>{img.src='';cb('slow');},3000);
            img.onload=img.onerror=()=>{
                clearTimeout(timeout);
                cb(performance.now()-t0>1800?'slow':'ok');
            };
            img.src='https://1.1.1.1/favicon.ico?_='+Date.now();
        }

        function detectVPN(cb){
            const t0=performance.now();
            const img=new Image();
            const timeout=setTimeout(()=>{img.src='';cb(true);},800);
            img.onload=img.onerror=()=>{
                clearTimeout(timeout);
                cb(performance.now()-t0>600);
            };
            img.src='https://1.1.1.1/favicon.ico?v='+Date.now();
        }

        function startTransition(){
            if(raf)stopAll();
            resize();resetRkt();
            overlay.style.display='flex';
            showMsg('','',false);
            particles=[];curtainT=0;vpn.hit=false;

            detectVPN(isVPN=>{
                if(isVPN){
                    mode='vpn';phase='go';phaseT=0;lastTs=0;
                    raf=requestAnimationFrame(tick);return;
                }
                detectState(state=>{
                    mode=state==='ok'?'launch':state;
                    phase='go';phaseT=0;lastTs=0;
                    raf=requestAnimationFrame(tick);
                });
            });
        }

        document.addEventListener('livewire:navigate',   startTransition);
        document.addEventListener('livewire:navigating', startTransition);
    })();
</script>
<style>
    * { scrollbar-width: none !important; -ms-overflow-style: none !important; }
    *::-webkit-scrollbar { display: none !important; }
</style>
</body>

</html>
