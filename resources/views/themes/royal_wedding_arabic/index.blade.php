<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <link rel="icon" type="image/png" href="/images/logo.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title }}</title>
    @include('partials.og-tags')
    
    <!-- Libraries -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/studio-freight/lenis@1.0.29/bundled/lenis.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>
    
    <!-- Arabic Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Tajawal:wght@300;400;700&display=swap" rel="stylesheet">
    
    <style>
        /* Smooth Scroll */
        html.lenis { height: auto; }
        .lenis.lenis-smooth { scroll-behavior: auto; }
        
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #0a0a0a;
            color: #ffffff;
            margin: 0;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .font-royal { font-family: 'Amiri', serif; }
        .color-gold { color: #d4af37; }
        .bg-gold { background-color: #d4af37; }
        .border-gold { border-color: #d4af37; }

        /* Loader */
        #loader {
            position: fixed; inset: 0; background: #000; z-index: 9999;
            display: flex; flex-direction: column; justify-content: center; align-items: center;
        }
        .gold-particles {
            position: absolute; inset: 0; pointer-events: none;
            background-image: radial-gradient(circle, #d4af37 1px, transparent 1px);
            background-size: 50px 50px;
            opacity: 0.1;
            animation: drift 20s linear infinite;
        }
        @keyframes drift { 0% { transform: translateY(0); } 100% { transform: translateY(-50px); } }

        /* Hero */
        .hero-bg {
            position: fixed; top: 0; left: 0; width: 100%; height: 100vh;
            z-index: 0;
            background-size: cover; background-position: center;
        }
        .hero-bg::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(10,10,10,1) 100%);
        }
        
        #main-content {
            position: relative; z-index: 20; background: transparent;
        }
        .section-dark {
            background-color: #0a0a0a; position: relative; z-index: 20;
        }

        /* 3D Gallery */
        .gallery-item {
            transform-style: preserve-3d;
            transition: transform 0.5s;
        }
        .gallery-img {
            transform: translateZ(20px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.5);
        }

        /* Timeline */
        .timeline-line {
            position: absolute; right: 50%; transform: translateX(50%);
            width: 2px; background: rgba(212, 175, 55, 0.2); top: 0; bottom: 0;
        }
        .timeline-progress {
            position: absolute; right: 0; top: 0; width: 100%;
            background: #d4af37; height: 0;
        }
        
        /* Glass Card */
        .glass-card {
            background: rgba(25, 25, 25, 0.6);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.5);
        }

        /* RSVP Overrides */
        input, select, textarea {
            background: rgba(255,255,255,0.05) !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            border-radius: 8px !important;
            color: white !important;
            padding: 15px !important;
            transition: all 0.3s ease;
        }
        input:focus, select:focus, textarea:focus {
            outline: none !important;
            border-color: #d4af37 !important;
            background: rgba(255,255,255,0.1) !important;
        }
        .btn-submit, button[type="submit"] {
            background: #d4af37 !important;
            color: #000 !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 15px 40px !important;
            font-family: 'Tajawal', sans-serif !important;
            font-weight: 700 !important;
            font-size: 1.1rem !important;
            cursor: pointer;
            transition: all 0.4s ease !important;
        }
        .btn-submit:hover, button[type="submit"]:hover {
            background: #f4d068 !important;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(212, 175, 55, 0.2) !important;
        }
    </style>
</head>
<body x-data="royalData()" x-init="initExperience()">

    <script id="content-data" type="application/json">@json($event->content_data)</script>

    <!-- Scene 1: Loader -->
    <div id="loader">
        <div class="gold-particles"></div>
        <div class="text-center z-10">
            <h1 class="font-royal text-6xl md:text-8xl color-gold mb-6 opacity-0" id="loader-names">
                <span x-text="data.groom_name"></span> <span class="text-4xl italic">&</span> <span x-text="data.bride_name"></span>
            </h1>
            <p class="text-sm tracking-widest text-gray-500 opacity-0 font-light" id="loader-text">جاري تحميل التجربة السينمائية...</p>
            <div class="w-0 h-px bg-gold mx-auto mt-6" id="loader-line"></div>
        </div>
    </div>

    <!-- Scene 2: Hero Parallax -->
    <div class="hero-bg" id="hero-bg">
        <template x-if="data.hero_video && data.hero_video.includes('.mp4')">
            <video :src="data.hero_video" autoplay muted loop playsinline class="w-full h-full object-cover"></video>
        </template>
        <template x-if="!data.hero_video || !data.hero_video.includes('.mp4')">
            <img :src="data.hero_video || 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&q=80'" class="w-full h-full object-cover opacity-60">
        </template>
    </div>

    <div id="main-content">
        <section class="h-[120vh] relative flex justify-center text-center px-4" id="hero-section">
            <div class="sticky top-[40vh] h-[20vh] w-full max-w-4xl hero-reveal-wrap">
                <p class="font-royal text-xl md:text-2xl text-gray-300 mb-8 drop-shadow-md">بسم الله الرحمن الرحيم</p>
                <h2 class="font-royal text-6xl md:text-8xl text-white mb-6 tracking-wide drop-shadow-2xl">
                    <span x-text="data.groom_name"></span>
                    <span class="color-gold text-5xl">&</span>
                    <span x-text="data.bride_name"></span>
                </h2>
                <p class="text-lg md:text-xl text-gray-300 drop-shadow-md mt-6">
                    ندعوكم لمشاركتنا فرحتنا
                </p>
            </div>
        </section>

        <!-- Scene 3: The Story Parallax -->
        <template x-if="data.story_text">
            <section class="section-dark py-40 px-4" id="story-section">
                <div class="max-w-3xl mx-auto text-center gs-fade-up">
                    <h3 class="font-royal text-4xl md:text-5xl color-gold mb-12">القصة</h3>
                    <p class="text-xl md:text-3xl leading-loose text-gray-300 font-light whitespace-pre-line" x-text="data.story_text"></p>
                </div>
            </section>
        </template>

        <!-- Scene 4: Interactive 3D Gallery -->
        <template x-if="data.gallery && data.gallery.length > 0">
            <section class="section-dark py-32 px-4" id="gallery-section">
                <div class="max-w-7xl mx-auto">
                    <h3 class="text-center font-royal text-4xl md:text-5xl color-gold mb-20 gs-fade-up">ذكرياتنا</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
                        <template x-for="(img, idx) in data.gallery" :key="idx">
                            <div class="gallery-item gs-fade-up" data-tilt data-tilt-max="10" data-tilt-speed="400" data-tilt-perspective="1000">
                                <div class="w-full h-96 relative overflow-hidden rounded-sm cursor-pointer" @click="openFullscreen(img)">
                                    <img :src="img" class="w-full h-full object-cover gallery-img transition duration-700 hover:scale-110">
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </section>
        </template>

        <!-- Scene 5: Animated Timeline -->
        <template x-if="data.timeline">
            <section class="section-dark py-40 px-4 relative" id="timeline-section">
                <h3 class="text-center font-royal text-4xl md:text-5xl color-gold mb-24 gs-fade-up">البرنامج الزمني</h3>
                
                <div class="max-w-3xl mx-auto relative pb-20">
                    <div class="timeline-line">
                        <div class="timeline-progress" id="timeline-progress"></div>
                    </div>
                    
                    <div class="space-y-24">
                        <template x-for="(line, idx) in splitLines(data.timeline)" :key="idx">
                            <div class="relative flex items-center justify-between w-full gs-timeline-item opacity-0">
                                <div class="absolute right-1/2 translate-x-1/2 w-4 h-4 rounded-full bg-gold z-10 shadow-[0_0_15px_rgba(212,175,55,0.6)]"></div>
                                
                                <div class="w-5/12" :class="idx % 2 === 0 ? 'text-right pr-8' : 'text-left pl-8 order-last'">
                                    <h4 class="font-royal text-3xl text-white mb-2" x-text="line.split(/[-:]/)[0]"></h4>
                                    <p class="text-gray-400 text-lg" x-text="line.substring(line.indexOf(line.split(/[-:]/)[0]) + line.split(/[-:]/)[0].length).replace(/^[-:]/, '').trim()"></p>
                                </div>
                                <div class="w-5/12"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </section>
        </template>

        <!-- Scene 6: Venue -->
        <template x-if="data.venue_name">
            <section class="section-dark py-32 px-4 border-y border-white/5" id="venue-section">
                <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
                    <div class="gs-fade-up">
                        <h3 class="font-royal text-4xl md:text-5xl color-gold mb-6">موقع الحفل</h3>
                        <h4 class="text-3xl mb-4 font-light text-white" x-text="data.venue_name"></h4>
                        <p class="text-gray-400 text-lg mb-8" x-text="data.venue_address"></p>
                        
                        <p class="text-2xl text-white mb-8">{{ \Carbon\Carbon::parse($event->event_datetime)->translatedFormat('l، d F Y - H:i') }}</p>

                        <template x-if="data.map_link">
                            <a :href="data.map_link" target="_blank" class="inline-block border border-gold text-gold px-10 py-3 hover:bg-gold hover:text-black transition duration-500 text-lg">
                                استعراض الخريطة
                            </a>
                        </template>
                    </div>
                    <div class="h-[60vh] overflow-hidden gs-fade-up">
                        <img :src="data.venue_image" class="w-full h-full object-cover filter brightness-75 hover:brightness-100 transition duration-1000">
                    </div>
                </div>
            </section>
        </template>

        <!-- Scene 7: Countdown -->
        <section class="section-dark py-40 px-4 text-center relative overflow-hidden" id="countdown-section">
            <div class="absolute inset-0 gold-particles opacity-20"></div>
            <div class="max-w-4xl mx-auto relative z-10 gs-fade-up">
                <div class="flex justify-center gap-8 md:gap-16 dir-ltr" style="direction: ltr;">
                    <div class="text-center">
                        <div class="text-5xl md:text-7xl font-light mb-4" x-text="timeLeft.days">00</div>
                        <div class="text-lg text-gray-500">يوماً</div>
                    </div>
                    <div class="text-center">
                        <div class="text-5xl md:text-7xl font-light mb-4" x-text="timeLeft.hours">00</div>
                        <div class="text-lg text-gray-500">ساعة</div>
                    </div>
                    <div class="text-center">
                        <div class="text-5xl md:text-7xl font-light mb-4" x-text="timeLeft.minutes">00</div>
                        <div class="text-lg text-gray-500">دقيقة</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Scene 8 & 9: Personal Message & RSVP Glass Card -->
        <section class="section-dark py-40 px-4 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')]" id="rsvp-section">
            <div class="max-w-2xl mx-auto gs-fade-up text-center">
                
                @if(isset($guest) && $guest)
                    <h2 class="font-royal text-4xl md:text-5xl mb-6 text-white">أهلاً بك يا {{ $guest->name ?? '' }}</h2>
                    <p class="text-gray-400 text-lg mb-12">يسعدنا حضورك لهذه المناسبة الخاصة التي لا تكتمل إلا بك.</p>
                @else
                    <h2 class="font-royal text-4xl md:text-5xl mb-6 text-white">تأكيد الحضور</h2>
                    <p class="text-gray-400 text-lg mb-12">يسعدنا أن تشاركونا فرحتنا، نرجو تأكيد حضوركم.</p>
                @endif

                <div class="glass-card p-10 md:p-16 relative" id="rsvp-card">
                    <!-- Loading / Success States -->
                    <div x-show="rsvpStatus === 'success'" class="absolute inset-0 flex flex-col items-center justify-center bg-black/80 backdrop-blur-md rounded-xl z-20" style="display: none;">
                        <svg class="w-20 h-20 text-green-400 mb-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <h3 class="font-royal text-4xl text-white">تم تأكيد حضورك</h3>
                        <p class="text-gray-400 mt-4 text-lg">بانتظارك بكل حب وشوق.</p>
                    </div>

                    <div id="native-rsvp-wrapper">
                        @include('components.evently-rsvp', ['event' => $event, 'isPublic' => $isPublic ?? false, 'invitation' => $invitation ?? null, 'guest' => $guest ?? null])
                    </div>
                </div>

            </div>
        </section>

        <!-- Scene 10: Closing -->
        <footer class="section-dark py-32 text-center border-t border-white/5">
            <template x-if="data.closing_message">
                <p class="font-royal text-3xl color-gold mb-12" x-text="data.closing_message"></p>
            </template>
            <p class="font-royal text-4xl text-white mt-12">
                <span x-text="data.groom_name"></span> & <span x-text="data.bride_name"></span>
            </p>
        </footer>
    </div>

    <!-- Fullscreen Image Modal -->
    <div x-show="fullscreenImage" style="display: none;" class="fixed inset-0 z-[10000] bg-black/95 backdrop-blur-sm flex items-center justify-center p-4" @click="fullscreenImage = null">
        <button class="absolute top-8 left-8 text-white text-4xl hover:text-gold transition">&times;</button>
        <img :src="fullscreenImage" class="max-w-full max-h-full object-contain shadow-2xl rounded-sm" @click.stop>
    </div>

    <script>
        function royalData() {
            return {
                data: {},
                fullscreenImage: null,
                targetDate: new Date("{{ \Carbon\Carbon::parse($event->event_datetime)->toIso8601String() }}").getTime(),
                timeLeft: { days: '00', hours: '00', minutes: '00' },
                rsvpStatus: 'idle',
                
                initExperience() {
                    const el = document.getElementById('content-data');
                    if (el) {
                        try { this.data = JSON.parse(el.textContent); } catch (e) { console.error("JSON Error"); }
                    }

                    this.startCountdown();

                    this.$nextTick(() => {
                        this.setupPreloader();
                        VanillaTilt.init(document.querySelectorAll("[data-tilt]"));
                    });
                },

                setupPreloader() {
                    const tl = gsap.timeline({
                        onComplete: () => {
                            setTimeout(() => this.startExperience(), 800);
                        }
                    });
                    tl.to("#loader-names", {opacity: 1, y: -20, duration: 1.5, ease: "power2.out", delay: 0.5});
                    tl.to("#loader-line", {width: "200px", duration: 1.5, ease: "power4.inOut"}, "-=0.5");
                    tl.to("#loader-text", {opacity: 1, duration: 1}, "-=0.5");
                },

                startExperience() {
                    const tl = gsap.timeline({
                        onComplete: () => {
                            document.getElementById('loader').style.display = 'none';
                            this.initScrollEngine();
                        }
                    });
                    tl.to("#loader > div", {opacity: 0, duration: 0.5});
                    tl.to("#loader", {yPercent: -100, duration: 1.2, ease: "power4.inOut"});
                },

                initScrollEngine() {
                    const lenis = new Lenis({
                        duration: 1.2,
                        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)), 
                        smooth: true,
                    });
                    function raf(time) { lenis.raf(time); requestAnimationFrame(raf); }
                    requestAnimationFrame(raf);

                    gsap.registerPlugin(ScrollTrigger);
                    lenis.on('scroll', ScrollTrigger.update);
                    gsap.ticker.add((time) => { lenis.raf(time * 1000) });
                    gsap.ticker.lagSmoothing(0);

                    // Hero Parallax
                    gsap.to("#hero-bg", {
                        yPercent: 20, 
                        ease: "none",
                        scrollTrigger: { trigger: "#hero-section", start: "top top", end: "bottom top", scrub: true }
                    });

                    // Hero Text fade out on scroll
                    gsap.to(".hero-reveal-wrap", {
                        opacity: 0, y: 100,
                        ease: "none",
                        scrollTrigger: { trigger: "#hero-section", start: "top top", end: "center top", scrub: true }
                    });

                    gsap.utils.toArray(".gs-fade-up").forEach(elem => {
                        gsap.fromTo(elem, {y: 60, opacity: 0}, {
                            y: 0, opacity: 1, duration: 1.5, ease: "power3.out",
                            scrollTrigger: { trigger: elem, start: "top 85%" }
                        });
                    });

                    // Timeline Animation
                    if(document.getElementById('timeline-section')) {
                        gsap.to("#timeline-progress", {
                            height: "100%", ease: "none",
                            scrollTrigger: { trigger: "#timeline-section", start: "top center", end: "bottom center", scrub: true }
                        });
                        gsap.utils.toArray(".gs-timeline-item").forEach(item => {
                            gsap.to(item, {
                                opacity: 1, x: 0, duration: 1, ease: "power2.out",
                                scrollTrigger: { trigger: item, start: "top 70%" }
                            });
                        });
                    }
                },

                splitLines(text) {
                    if(!text) return [];
                    return text.split('\n').filter(l => l.trim() !== '');
                },

                openFullscreen(imgUrl) {
                    this.fullscreenImage = imgUrl;
                },

                startCountdown() {
                    const update = () => {
                        const now = new Date().getTime();
                        const distance = this.targetDate - now;
                        if (distance < 0) return;
                        
                        this.timeLeft.days = Math.floor(distance / (1000 * 60 * 60 * 24)).toString().padStart(2, '0');
                        this.timeLeft.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toString().padStart(2, '0');
                        this.timeLeft.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)).toString().padStart(2, '0');
                    };
                    update();
                    setInterval(update, 60000); 
                }
            }
        }
        
        // Form Confetti Intercept
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('native-rsvp-wrapper');
            if(form) {
                form.addEventListener('submit', (e) => {
                    var duration = 3 * 1000;
                    var animationEnd = Date.now() + duration;
                    var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 9999, colors: ['#d4af37', '#ffffff'] };
                    var interval = setInterval(function() {
                        var timeLeft = animationEnd - Date.now();
                        if (timeLeft <= 0) return clearInterval(interval);
                        confetti(Object.assign({}, defaults, { particleCount: 50, origin: { x: 0.2, y: 0.5 } }));
                        confetti(Object.assign({}, defaults, { particleCount: 50, origin: { x: 0.8, y: 0.5 } }));
                    }, 250);
                });
            }
        });
    </script>
</body>
</html>

