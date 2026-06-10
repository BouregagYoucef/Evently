<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <link rel="icon" type="image/png" href="/images/logo.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title }}</title>
    @include('partials.og-tags')
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/studio-freight/lenis@1.0.29/bundled/lenis.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        html.lenis { height: auto; }
        .lenis.lenis-smooth { scroll-behavior: auto; }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #0d1117;
            color: #ffffff;
            margin: 0;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .font-royal { font-family: 'Playfair Display', serif; }
        .text-gold { color: #d4af37; }

        /* Scene 1: Loader */
        #loader {
            position: fixed; inset: 0; background: #000; z-index: 9999;
            display: flex; justify-content: center; align-items: center;
        }

        /* Scene 2 & 3: Hero & Petals */
        .hero-bg {
            position: fixed; top: 0; left: 0; width: 100%; height: 100vh;
            z-index: 0; background-size: cover; background-position: center;
        }
        .hero-bg::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.2) 0%, #0d1117 100%);
        }
        #petals-canvas {
            position: fixed; inset: 0; z-index: 5; pointer-events: none;
        }
        
        #main-content {
            position: relative; z-index: 20; background: transparent;
        }
        .section-dark {
            background-color: #0d1117; position: relative; z-index: 20;
        }

        /* Story Cards */
        .story-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(212,175,55,0.2);
            border-radius: 16px;
            backdrop-filter: blur(10px);
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        /* 3D Gallery */
        .gallery-item {
            transform-style: preserve-3d;
            transition: transform 0.5s;
        }
        .gallery-img {
            transform: translateZ(30px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.6);
            border-radius: 8px;
        }

        /* Countdown Flip */
        .flip-box {
            background: linear-gradient(180deg, #1f242d 0%, #0d1117 100%);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 20px 30px;
            box-shadow: inset 0 2px 0 rgba(255,255,255,0.1), 0 10px 20px rgba(0,0,0,0.5);
            position: relative;
            overflow: hidden;
        }
        .flip-box::after {
            content: ''; position: absolute; top: 50%; left: 0; right: 0;
            height: 2px; background: rgba(0,0,0,0.5); z-index: 10;
        }
        .night-sky {
            background-image: radial-gradient(circle, #ffffff 1px, transparent 1px);
            background-size: 60px 60px;
            background-position: 0 0;
            animation: move-stars 100s linear infinite;
            opacity: 0.2;
        }
        @keyframes move-stars {
            0% { background-position: 0 0; }
            100% { background-position: -1000px 1000px; }
        }

        /* Venue Gold Engraving */
        .gold-engrave {
            background: linear-gradient(to right, #bf953f, #fcf6ba, #b38728, #fbf5b7, #aa771c);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0px 2px 4px rgba(0,0,0,0.5);
        }

        /* Glass Card RSVP */
        .glass-card {
            background: rgba(20, 20, 25, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(212,175,55,0.3);
            border-radius: 20px;
            box-shadow: 0 40px 80px rgba(0,0,0,0.8);
        }

        input, select, textarea {
            background: rgba(0,0,0,0.2) !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            border-radius: 8px !important;
            color: white !important;
            padding: 15px !important;
            transition: all 0.3s ease;
        }
        input:focus, select:focus, textarea:focus {
            outline: none !important;
            border-color: #d4af37 !important;
            background: rgba(0,0,0,0.4) !important;
        }
        .btn-submit, button[type="submit"] {
            background: linear-gradient(to right, #bf953f, #b38728) !important;
            color: #fff !important;
            text-shadow: 0 1px 2px rgba(0,0,0,0.5);
            border: none !important;
            border-radius: 8px !important;
            padding: 15px 40px !important;
            font-family: 'Montserrat', sans-serif !important;
            font-weight: 600 !important;
            text-transform: uppercase;
            font-size: 1rem !important;
            cursor: pointer;
            transition: all 0.4s ease !important;
        }
        .btn-submit:hover, button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(212, 175, 55, 0.4) !important;
            filter: brightness(1.1);
        }
    </style>
</head>
<body x-data="gardenData()" x-init="initExperience()">

    <script id="content-data" type="application/json">@json($event->content_data)</script>

    <!-- Scene 1: Loader -->
    <div id="loader">
        <h1 class="font-royal text-4xl md:text-5xl text-gold opacity-0" id="bismillah-text">
            Au nom de Dieu, le Clément, le Miséricordieux
        </h1>
    </div>

    <!-- Scene 2: Hero Parallax -->
    <div class="hero-bg" id="hero-bg">
        <template x-if="data.hero_video && data.hero_video.includes('.mp4')">
            <video :src="data.hero_video" autoplay muted loop playsinline class="w-full h-full object-cover opacity-80"></video>
        </template>
        <template x-if="!data.hero_video || !data.hero_video.includes('.mp4')">
            <img :src="data.hero_video || 'https://images.unsplash.com/photo-1510076857177-7470076d4098?auto=format&fit=crop&q=80'" class="w-full h-full object-cover opacity-80 filter sepia-[0.3]">
        </template>
    </div>

    <!-- Scene 3: Petals Canvas -->
    <canvas id="petals-canvas"></canvas>

    <div id="main-content">
        <!-- Hero Text (Cinematic Reveal) -->
        <section class="h-[120vh] relative flex flex-col justify-center items-center text-center px-4" id="hero-section">
            <div class="sticky top-[40vh] h-[20vh] w-full max-w-4xl">
                <h2 class="font-royal text-6xl md:text-8xl text-white mb-6 drop-shadow-2xl flex flex-col md:flex-row justify-center items-center gap-4">
                    <span class="hero-name opacity-0 transform translate-y-4" x-text="data.groom_name"></span>
                    <span class="hero-amp opacity-0 text-gold text-5xl md:text-7xl italic">&</span>
                    <span class="hero-name opacity-0 transform translate-y-4" x-text="data.bride_name"></span>
                </h2>
                <div class="hero-welcome opacity-0 transform translate-y-4 mt-12">
                    <p class="font-royal text-2xl md:text-3xl text-gold mb-2 italic">Avec tout notre amour</p>
                    <p class="text-sm md:text-lg tracking-widest uppercase text-gray-300 drop-shadow-md">Nous vous invitons à partager le plus beau jour de notre vie</p>
                </div>
            </div>
        </section>

        <!-- Scene 4: Story Cards (Sliding) -->
        <template x-if="data.story_cards">
            <section class="section-dark py-40 px-4 overflow-hidden" id="story-section">
                <div class="max-w-5xl mx-auto">
                    <h3 class="text-center font-royal text-4xl md:text-5xl text-gold mb-20 gs-fade-up italic">Notre Histoire</h3>
                    <div class="space-y-12">
                        <template x-for="(cardText, idx) in splitCards(data.story_cards)" :key="idx">
                            <div class="story-card opacity-0 transform" :class="idx % 2 === 0 ? '-translate-x-[100px]' : 'translate-x-[100px] ml-auto'" style="max-w: 600px;">
                                <div class="text-gold opacity-50 mb-4">
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                                </div>
                                <p class="text-lg md:text-xl leading-loose text-gray-200 font-light" x-text="cardText"></p>
                            </div>
                        </template>
                    </div>
                </div>
            </section>
        </template>

        <!-- Scene 5: Interactive Art Gallery -->
        <template x-if="data.gallery && data.gallery.length > 0">
            <section class="section-dark py-32 px-4" id="gallery-section">
                <div class="max-w-7xl mx-auto">
                    <h3 class="text-center font-royal text-4xl md:text-5xl text-gold mb-20 gs-fade-up italic">Galerie</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-16">
                        <template x-for="(img, idx) in data.gallery" :key="idx">
                            <div class="gallery-item gs-fade-up" data-tilt data-tilt-max="15" data-tilt-speed="400" data-tilt-glare="true" data-tilt-max-glare="0.5">
                                <div class="w-full h-[400px] relative rounded-lg cursor-pointer" @click="openFullscreen(img)">
                                    <img :src="img" class="w-full h-full object-cover gallery-img transition duration-700">
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </section>
        </template>

        <!-- Scene 6: Mechanical Countdown (Night Sky) -->
        <section class="section-dark py-40 px-4 text-center relative overflow-hidden" id="countdown-section">
            <div class="absolute inset-0 night-sky"></div>
            <div class="max-w-4xl mx-auto relative z-10 gs-fade-up">
                <h3 class="font-royal text-4xl md:text-5xl text-white mb-16 drop-shadow-lg italic">Temps Restant</h3>
                <div class="flex justify-center gap-6 md:gap-12">
                    <div class="text-center">
                        <div class="flip-box mb-4">
                            <div class="text-5xl md:text-7xl font-bold font-mono text-white" x-text="timeLeft.days">00</div>
                        </div>
                        <div class="text-xs tracking-widest text-gold uppercase">Jours</div>
                    </div>
                    <div class="text-center">
                        <div class="flip-box mb-4">
                            <div class="text-5xl md:text-7xl font-bold font-mono text-white" x-text="timeLeft.hours">00</div>
                        </div>
                        <div class="text-xs tracking-widest text-gold uppercase">Heures</div>
                    </div>
                    <div class="text-center">
                        <div class="flip-box mb-4">
                            <div class="text-5xl md:text-7xl font-bold font-mono text-white" x-text="timeLeft.minutes">00</div>
                        </div>
                        <div class="text-xs tracking-widest text-gold uppercase">Minutes</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Scene 7: Venue (Gold Engraving) -->
        <template x-if="data.venue_name">
            <section class="section-dark py-40 px-4 border-y border-white/5 relative" id="venue-section">
                <div class="absolute inset-0 opacity-20 filter grayscale blur-sm bg-cover bg-center" :style="`background-image: url('${data.venue_image}')`"></div>
                
                <div class="max-w-4xl mx-auto text-center relative z-10 gs-fade-up">
                    <h3 class="font-royal text-3xl text-gray-400 mb-6 italic">Le Lieu</h3>
                    <!-- Gold Engraved Name -->
                    <h4 class="text-5xl md:text-7xl font-royal mb-8 gold-engrave" x-text="data.venue_name"></h4>
                    
                    <p class="text-xl text-gray-300 mb-8" x-text="data.venue_address"></p>
                    
                    <div class="inline-block border border-gold/30 bg-black/50 backdrop-blur-sm p-6 rounded-lg mb-12">
                        <p class="text-2xl text-white font-royal">{{ \Carbon\Carbon::parse($event->event_datetime)->translatedFormat('d F Y') }}</p>
                        <p class="text-gold mt-2 text-xl">{{ \Carbon\Carbon::parse($event->event_datetime)->translatedFormat('H:i') }}</p>
                    </div>

                    <div>
                        <template x-if="data.map_link">
                            <a :href="data.map_link" target="_blank" class="inline-block border border-gold text-gold px-12 py-4 hover:bg-gold hover:text-black transition duration-500 text-sm tracking-widest uppercase rounded-full">
                                Voir la Carte
                            </a>
                        </template>
                    </div>
                </div>
            </section>
        </template>

        <!-- Scene 8 & 9: Personal Message & Glass RSVP -->
        <section class="section-dark py-40 px-4 relative" id="rsvp-section">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')]"></div>
            <div class="max-w-2xl mx-auto gs-fade-up text-center relative z-10">
                
                @if(isset($guest) && $guest)
                    <h2 class="font-royal text-4xl md:text-5xl mb-6 text-gold italic">Bienvenue {{ $guest->name ?? '' }}</h2>
                    <p class="text-gray-300 text-lg mb-12">Votre présence rendra cette journée inoubliable.</p>
                @else
                    <template x-if="data.host_message">
                        <div>
                            <p class="text-gray-300 text-lg mb-12 whitespace-pre-line" x-text="data.host_message"></p>
                        </div>
                    </template>
                    <template x-if="!data.host_message">
                        <h2 class="font-royal text-4xl md:text-5xl mb-12 text-gold italic">Confirmez votre Présence</h2>
                    </template>
                @endif

                <div class="glass-card p-10 md:p-16 relative" id="rsvp-card">
                    <div x-show="rsvpStatus === 'success'" class="absolute inset-0 flex flex-col items-center justify-center bg-[#0d1117]/95 backdrop-blur-xl rounded-2xl z-20" style="display: none;">
                        <svg class="w-20 h-20 text-gold mb-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h3 class="font-royal text-3xl text-white gold-engrave">Inscription Confirmée</h3>
                        <p class="text-gray-400 mt-4 text-lg">Nous avons hâte de vous y voir.</p>
                    </div>

                    <div id="native-rsvp-wrapper">
                        @include('components.evently-rsvp', ['event' => $event, 'isPublic' => $isPublic ?? false, 'invitation' => $invitation ?? null, 'guest' => $guest ?? null])
                    </div>
                </div>

            </div>
        </section>

        <!-- Scene 10: Closing -->
        <footer class="section-dark py-40 text-center border-t border-white/5 relative overflow-hidden">
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-5">
                <h1 class="font-royal text-[15vw] whitespace-nowrap text-gold">
                    <span x-text="data.groom_name"></span> & <span x-text="data.bride_name"></span>
                </h1>
            </div>

            <div class="relative z-10">
                <template x-if="data.closing_message">
                    <p class="font-royal text-3xl text-gray-300 mb-12 leading-loose italic" x-text="data.closing_message"></p>
                </template>
                <p class="font-royal text-5xl text-gold mt-12">
                    <span x-text="data.groom_name"></span> <span class="italic">&</span> <span x-text="data.bride_name"></span>
                </p>
            </div>
        </footer>
    </div>

    <!-- Fullscreen Modal -->
    <div x-show="fullscreenImage" style="display: none;" class="fixed inset-0 z-[10000] bg-black/95 backdrop-blur-md flex items-center justify-center p-4" @click="fullscreenImage = null">
        <button class="absolute top-8 right-8 text-white text-5xl hover:text-gold transition">&times;</button>
        <img :src="fullscreenImage" class="max-w-[90vw] max-h-[90vh] object-contain shadow-2xl rounded-sm" @click.stop>
    </div>

    <script>
        function gardenData() {
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
                        this.initPetals();
                    });
                },

                setupPreloader() {
                    const tl = gsap.timeline({
                        onComplete: () => {
                            setTimeout(() => this.startExperience(), 1000);
                        }
                    });
                    tl.to("#bismillah-text", {opacity: 1, duration: 2, ease: "power2.inOut"})
                      .to("#bismillah-text", {opacity: 0, duration: 1.5, delay: 1.5, ease: "power2.inOut"});
                },

                startExperience() {
                    document.getElementById('loader').style.display = 'none';
                    this.initScrollEngine();

                    const tl = gsap.timeline();
                    const names = document.querySelectorAll('.hero-name');
                    
                    if(names.length > 0) {
                        tl.to(names[0], {opacity: 1, y: 0, duration: 1.5, ease: "power3.out"})
                          .to('.hero-amp', {opacity: 1, scale: 1, duration: 1, ease: "power2.out"}, "+=0.5")
                          .to(names[1], {opacity: 1, y: 0, duration: 1.5, ease: "power3.out"}, "+=0.5")
                          .to('.hero-welcome', {opacity: 1, y: 0, duration: 1.5, ease: "power2.out"}, "+=1");
                    }
                },

                initScrollEngine() {
                    const lenis = new Lenis({
                        duration: 1.5,
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
                        yPercent: 15, 
                        ease: "none",
                        scrollTrigger: { trigger: "#hero-section", start: "top top", end: "bottom top", scrub: true }
                    });

                    // General Fade Up
                    gsap.utils.toArray(".gs-fade-up").forEach(elem => {
                        gsap.fromTo(elem, {y: 60, opacity: 0}, {
                            y: 0, opacity: 1, duration: 1.5, ease: "power3.out",
                            scrollTrigger: { trigger: elem, start: "top 85%" }
                        });
                    });

                    // Story Cards Slide In
                    gsap.utils.toArray(".story-card").forEach(card => {
                        gsap.to(card, {
                            x: 0, opacity: 1, duration: 1.2, ease: "power2.out",
                            scrollTrigger: { trigger: card, start: "top 80%" }
                        });
                    });
                },

                splitCards(text) {
                    if(!text) return [];
                    return text.split('\n\n').filter(l => l.trim() !== '');
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
                },

                initPetals() {
                    const canvas = document.getElementById('petals-canvas');
                    const ctx = canvas.getContext('2d');
                    let width, height, petals = [];

                    function resize() {
                        width = canvas.width = window.innerWidth;
                        height = canvas.height = window.innerHeight;
                    }
                    window.addEventListener('resize', resize);
                    resize();

                    for (let i = 0; i < 40; i++) {
                        petals.push({
                            x: Math.random() * width,
                            y: Math.random() * height - height,
                            w: Math.random() * 8 + 8,
                            h: Math.random() * 4 + 4,
                            vx: Math.random() * 1 - 0.5,
                            vy: Math.random() * 1 + 0.5,
                            angle: Math.random() * 360,
                            spin: Math.random() * 0.1 - 0.05,
                            color: Math.random() > 0.5 ? '#ff4d4d' : '#ffb3b3'
                        });
                    }

                    function draw() {
                        ctx.clearRect(0, 0, width, height);
                        petals.forEach(p => {
                            p.x += p.vx;
                            p.y += p.vy;
                            p.angle += p.spin;

                            if (p.y > height) {
                                p.y = -20;
                                p.x = Math.random() * width;
                            }

                            ctx.save();
                            ctx.translate(p.x, p.y);
                            ctx.rotate(p.angle);
                            ctx.beginPath();
                            ctx.ellipse(0, 0, p.w, p.h, 0, 0, Math.PI * 2);
                            ctx.fillStyle = p.color;
                            ctx.globalAlpha = 0.6;
                            ctx.fill();
                            ctx.restore();
                        });
                        requestAnimationFrame(draw);
                    }
                    draw();
                }
            }
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('native-rsvp-wrapper');
            if(form) {
                form.addEventListener('submit', (e) => {
                    var duration = 4 * 1000;
                    var animationEnd = Date.now() + duration;
                    var defaults = { startVelocity: 25, spread: 360, ticks: 100, zIndex: 9999, colors: ['#FFD700', '#D4AF37', '#FFF8DC'] };

                    var interval = setInterval(function() {
                        var timeLeft = animationEnd - Date.now();
                        if (timeLeft <= 0) return clearInterval(interval);
                        confetti(Object.assign({}, defaults, { particleCount: 40, origin: { x: 0.5, y: 0.6 } }));
                    }, 250);
                });
            }
        });
    </script>
</body>
</html>

