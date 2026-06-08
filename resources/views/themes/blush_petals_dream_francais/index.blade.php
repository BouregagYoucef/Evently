<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <link rel="icon" type="image/png" href="/images/logo.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/studio-freight/lenis@1.0.29/bundled/lenis.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Montserrat:wght@300;400;500&family=Great+Vibes&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --blush-pink: #FFD1DC;
            --rose-gold: #B76E79;
            --soft-ivory: #FFFFF0;
            --pearl-white: #FDFBF7;
            --dusty-pink: #DCAE96;
            --text-dark: #4A3C31;
        }

        html.lenis { height: auto; }
        .lenis.lenis-smooth { scroll-behavior: auto; }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--pearl-white);
            color: var(--text-dark);
            margin: 0;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .font-script { font-family: 'Great Vibes', cursive; }
        .font-ink { font-family: 'Cormorant Garamond', serif; }

        /* Canvas Petals */
        #petals-canvas {
            position: fixed;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            pointer-events: none;
            z-index: 999;
            opacity: 0.8;
        }

        /* Rose Gold Text Effect */
        .text-rosegold {
            background: linear-gradient(45deg, #B76E79, #E0BFB8, #B76E79);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: 200% auto;
            animation: shine 5s linear infinite;
        }
        @keyframes shine {
            to { background-position: 200% center; }
        }

        /* Scene 1: Loader */
        #loader {
            position: fixed; inset: 0; background: var(--pearl-white); z-index: 10000;
            display: flex; flex-direction: column; justify-content: center; align-items: center;
        }

        /* Hero */
        .hero-bg {
            position: fixed; top: 0; left: 0; width: 100%; height: 100vh;
            z-index: 0; background-size: cover; background-position: center;
        }
        .hero-bg::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(to bottom, rgba(253,251,247,0.2) 0%, var(--pearl-white) 100%);
        }
        
        #main-content {
            position: relative; z-index: 20; background: transparent;
        }

        /* Album Flip */
        .album-container {
            perspective: 2000px;
            width: 100%;
            max-width: 500px;
            height: 600px;
            position: relative;
            margin: 0 auto;
        }
        .album-page {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: #fff;
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
            border: 1px solid rgba(183, 110, 121, 0.2);
            transform-origin: left center; /* LTR flip origin */
            transform-style: preserve-3d;
            will-change: transform;
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            backface-visibility: hidden;
        }
        .album-page img {
            width: 100%; height: 60%; object-fit: cover;
            border-radius: 8px; margin-bottom: 24px;
            box-shadow: 0 10px 20px rgba(183, 110, 121, 0.15);
        }

        /* Floral Tunnel */
        .tunnel-wrapper {
            position: relative;
            height: 200vh; 
            overflow: hidden;
        }
        .tunnel-layer {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100vh;
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            will-change: transform, opacity;
            pointer-events: none;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Venue Gate */
        .gate-wrapper {
            position: relative;
            width: 100%; max-width: 800px; height: 60vh;
            overflow: hidden; margin: 0 auto;
            box-shadow: 0 30px 60px rgba(183, 110, 121, 0.2);
            border-radius: 20px;
        }
        .venue-img {
            position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover;
        }
        .gate-half {
            position: absolute; top: 0; height: 100%; width: 50%;
            background: var(--soft-ivory);
            border: 2px solid var(--rose-gold);
            display: flex; justify-content: center; align-items: center;
            will-change: transform;
            z-index: 5;
            background-image: url('https://www.transparenttextures.com/patterns/white-marble.png');
        }
        .gate-left { left: 0; border-right: 1px solid var(--rose-gold); transform-origin: left; }
        .gate-right { right: 0; border-left: 1px solid var(--rose-gold); transform-origin: right; }
        .gate-decor { width: 40px; height: 100%; background: var(--rose-gold); opacity: 0.1; }

        /* Glassmorphism Pink */
        .glass-pink {
            background: rgba(255, 209, 220, 0.2);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 209, 220, 0.5);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(183, 110, 121, 0.15);
        }

        /* Forms */
        input, select, textarea {
            background: rgba(255,255,255,0.6) !important;
            border: 1px solid rgba(183, 110, 121, 0.3) !important;
            border-radius: 8px !important;
            color: var(--text-dark) !important;
            padding: 15px !important;
            transition: all 0.3s ease;
        }
        input:focus, select:focus, textarea:focus {
            outline: none !important;
            border-color: var(--rose-gold) !important;
            background: #fff !important;
            box-shadow: 0 0 15px rgba(183, 110, 121, 0.2);
        }
        .btn-submit, button[type="submit"] {
            background: var(--rose-gold) !important;
            color: #fff !important;
            border: none !important;
            border-radius: 30px !important;
            padding: 15px 40px !important;
            font-family: 'Montserrat', sans-serif !important;
            font-weight: 500 !important;
            letter-spacing: 1px !important;
            cursor: pointer;
            transition: all 0.5s ease !important;
            box-shadow: 0 10px 20px rgba(183, 110, 121, 0.3) !important;
        }
        .btn-submit:hover, button[type="submit"]:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 15px 25px rgba(183, 110, 121, 0.5) !important;
        }

    </style>
</head>
<body x-data="blushData()" x-init="initExperience()">

    <script id="content-data" type="application/json">@json($event->content_data)</script>

    <canvas id="petals-canvas"></canvas>

    <!-- Scene 1: Loader -->
    <div id="loader">
        <h1 class="font-script text-4xl md:text-6xl text-[#B76E79] opacity-0 mb-8" id="intro-text">
            A Love Story Begins
        </h1>
        <div id="intro-names" class="opacity-0 flex flex-col items-center">
            <h2 class="font-ink text-6xl md:text-8xl text-rosegold" x-text="data.bride_name"></h2>
            <span class="text-3xl text-[#DCAE96] my-4 font-ink italic">&</span>
            <h2 class="font-ink text-6xl md:text-8xl text-rosegold" x-text="data.groom_name"></h2>
        </div>
    </div>

    <!-- Scene 2: Hero -->
    <div class="hero-bg" id="hero-bg">
        <img :src="data.cover_image || 'https://images.unsplash.com/photo-1519225421980-715cb0215aed?auto=format&fit=crop&q=80'" class="w-full h-full object-cover opacity-80">
    </div>

    <div id="main-content">
        <section class="h-[100vh] relative flex flex-col justify-end items-center pb-20">
            <div class="gs-fade-up text-center">
                <p class="font-ink text-2xl text-rosegold mb-2 italic">Un Moment Inoubliable</p>
                <div class="w-px h-24 bg-[#B76E79] mx-auto opacity-50"></div>
            </div>
        </section>

        <!-- Scene 3: Memory Album Flip -->
        <template x-if="data.story_content">
            <section class="py-[20vh] px-4 min-h-screen" id="album-section">
                <div class="text-center mb-16 gs-fade-up">
                    <h2 class="font-ink text-5xl text-rosegold italic">Nos Souvenirs</h2>
                </div>
                
                <div class="album-container" id="album-container">
                    <template x-for="(line, idx) in splitLines(data.story_content)" :key="idx">
                        <div class="album-page" :style="`z-index: ${50 - idx};`">
                            <div class="w-full h-full border border-[#B76E79]/20 p-8 flex flex-col justify-center items-center">
                                <p class="font-ink text-3xl md:text-4xl leading-relaxed text-[#4A3C31] italic" x-text="line"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </section>
        </template>

        <!-- Scene 4: Floral Tunnel -->
        <section class="tunnel-wrapper bg-[var(--pearl-white)] relative z-20" id="tunnel-section">
            <div class="sticky top-0 h-screen overflow-hidden flex flex-col items-center justify-center">
                <h2 class="font-ink text-5xl md:text-7xl text-rosegold absolute z-50 text-center px-4 italic" id="tunnel-text">
                    Un Voyage vers l'Éternité
                </h2>
                <div class="tunnel-layer z-40" style="background-image: url('https://raw.githubusercontent.com/youcef/assets/main/floral_frame.png');"></div>
                <div class="tunnel-layer z-30 transform scale-75 opacity-50" style="background-image: url('https://raw.githubusercontent.com/youcef/assets/main/floral_frame.png');"></div>
                <div class="tunnel-layer z-20 transform scale-50 opacity-20" style="background-image: url('https://raw.githubusercontent.com/youcef/assets/main/floral_frame.png');"></div>
            </div>
        </section>

        <!-- Scene 5: Golden Frames Gallery -->
        <template x-if="data.gallery && data.gallery.length > 0">
            <section class="py-[20vh] px-4 bg-[var(--pearl-white)] relative z-20" id="gallery-section">
                <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-12 md:gap-8">
                    <template x-for="(img, idx) in data.gallery" :key="idx">
                        <div class="gallery-frame gs-fade-up border-[8px] border-[#D4AF37] p-2 bg-white shadow-2xl" 
                             :style="`transform: translateY(${idx%2==0 ? '0' : '50px'}) rotate(${idx%2==0 ? '-2deg' : '3deg'});`">
                            <img :src="img" class="w-full h-[400px] object-cover">
                        </div>
                    </template>
                </div>
            </section>
        </template>

        <!-- Scene 6: Bride's Message -->
        <template x-if="data.bride_message">
            <section class="py-[20vh] px-4 bg-[var(--pearl-white)] relative z-20 flex justify-center">
                <div class="max-w-2xl w-full bg-[var(--soft-ivory)] p-12 md:p-20 shadow-xl border border-[#DCAE96]/30 text-center relative gs-fade-up">
                    <svg class="absolute top-4 right-4 w-12 h-12 text-[#B76E79]/20" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z"/></svg>
                    <p class="font-script text-3xl md:text-5xl leading-loose text-[#B76E79]" x-text="data.bride_message"></p>
                </div>
            </section>
        </template>

        <!-- Scene 7: Venue Gate Reveal -->
        <template x-if="data.venue_name">
            <section class="py-[20vh] px-4 bg-[var(--pearl-white)] relative z-20" id="venue-section">
                <div class="text-center mb-12 gs-fade-up">
                    <h3 class="font-ink text-4xl text-rosegold uppercase tracking-widest text-sm">Le Lieu</h3>
                </div>
                
                <div class="gate-wrapper" id="gate-wrapper">
                    <img :src="data.venue_image" class="venue-img">
                    <div class="absolute inset-0 bg-black/30 flex flex-col items-center justify-center text-center z-10 p-8">
                        <h4 class="font-ink text-5xl md:text-7xl text-white mb-6 drop-shadow-lg italic" x-text="data.venue_name"></h4>
                        <template x-if="data.venue_location">
                            <a :href="data.venue_location" target="_blank" class="px-8 py-3 bg-white/20 backdrop-blur-sm border border-white/50 text-white rounded-full hover:bg-white hover:text-[#B76E79] transition uppercase tracking-widest text-xs">
                                Voir la Carte
                            </a>
                        </template>
                    </div>

                    <div class="gate-half gate-left">
                        <div class="w-12 h-12 rounded-full border border-[#B76E79] flex items-center justify-center absolute -right-6 bg-[var(--soft-ivory)] z-20">
                            <div class="w-6 h-6 rounded-full bg-[#B76E79]"></div>
                        </div>
                    </div>
                    <div class="gate-half gate-right"></div>
                </div>
            </section>
        </template>

        <!-- Scene 8: Countdown -->
        <section class="py-[20vh] px-4 bg-[var(--pearl-white)] relative z-20 text-center overflow-hidden">
            <div class="gs-fade-up">
                <div class="flex justify-center gap-8 md:gap-16">
                    <div class="text-center">
                        <div class="font-ink text-6xl md:text-8xl text-rosegold mb-2" x-text="timeLeft.days">00</div>
                        <div class="text-xs tracking-widest text-[#DCAE96] uppercase">Jours</div>
                    </div>
                    <div class="text-center">
                        <div class="font-ink text-6xl md:text-8xl text-rosegold mb-2" x-text="timeLeft.hours">00</div>
                        <div class="text-xs tracking-widest text-[#DCAE96] uppercase">Heures</div>
                    </div>
                    <div class="text-center">
                        <div class="font-ink text-6xl md:text-8xl text-rosegold mb-2" x-text="timeLeft.minutes">00</div>
                        <div class="text-xs tracking-widest text-[#DCAE96] uppercase">Minutes</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Scene 9: Pink Glass RSVP -->
        <section class="py-[20vh] px-4 bg-[var(--pearl-white)] relative z-20 flex justify-center" id="rsvp-section">
            <div class="w-full max-w-xl text-center relative z-10 gs-fade-up">
                
                @if(isset($guest) && $guest)
                    <p class="font-ink text-3xl text-[#DCAE96] mb-4 italic">Chère {{ $guest->name ?? '' }}</p>
                    <h2 class="font-ink text-4xl md:text-5xl mb-12 text-rosegold leading-tight italic">Nous avons réservé une place pour vous</h2>
                @else
                    <h2 class="font-ink text-4xl md:text-5xl mb-12 text-rosegold italic">Confirmer la Présence</h2>
                @endif

                <div class="glass-pink p-8 md:p-14 relative" id="rsvp-card">
                    <div x-show="rsvpStatus === 'success'" class="absolute inset-0 flex flex-col items-center justify-center bg-[var(--pearl-white)]/90 backdrop-blur-md rounded-3xl z-20" style="display: none;">
                        <h3 class="font-ink text-4xl text-rosegold mb-4 italic">Présence Confirmée</h3>
                        <p class="text-[#4A3C31] text-lg">Nous avons hâte de vous y voir.</p>
                    </div>

                    <div id="native-rsvp-wrapper">
                        @include('components.evently-rsvp', ['event' => $event, 'isPublic' => $isPublic ?? false, 'invitation' => $invitation ?? null, 'guest' => $guest ?? null])
                    </div>
                </div>

            </div>
        </section>

        <!-- Scene 10: Closing -->
        <footer class="py-[30vh] text-center bg-[var(--pearl-white)] relative z-20">
            <div class="gs-fade-up">
                <template x-if="data.closing_message">
                    <p class="font-ink text-3xl md:text-4xl text-[#B76E79] mb-16 italic" x-text="data.closing_message"></p>
                </template>
                <template x-if="!data.closing_message">
                    <p class="font-ink text-3xl md:text-4xl text-[#B76E79] mb-16 italic">L'amour est plus beau partagé...<br>Nous espérons que vous ferez partie de notre histoire</p>
                </template>
                
                <p class="font-ink text-5xl text-rosegold italic">
                    <span x-text="data.bride_name"></span> & <span x-text="data.groom_name"></span>
                </p>
            </div>
        </footer>
    </div>

    <script>
        class Petal {
            constructor(canvas) {
                this.canvas = canvas;
                this.ctx = canvas.getContext('2d');
                this.reset();
            }
            reset() {
                this.x = Math.random() * this.canvas.width;
                this.y = Math.random() * -this.canvas.height;
                this.size = Math.random() * 8 + 4;
                this.speedY = Math.random() * 1 + 0.5;
                this.speedX = Math.random() * 1 - 0.5;
                this.rotation = Math.random() * 360;
                this.rotationSpeed = Math.random() * 2 - 1;
                this.opacity = Math.random() * 0.5 + 0.3;
            }
            update() {
                this.y += this.speedY;
                this.x += this.speedX + Math.sin(this.y * 0.01) * 0.5; 
                this.rotation += this.rotationSpeed;
                if (this.y > this.canvas.height) this.reset();
            }
            draw() {
                this.ctx.save();
                this.ctx.translate(this.x, this.y);
                this.ctx.rotate((this.rotation * Math.PI) / 180);
                this.ctx.globalAlpha = this.opacity;
                this.ctx.fillStyle = '#FFD1DC';
                
                this.ctx.beginPath();
                this.ctx.moveTo(0, 0);
                this.ctx.bezierCurveTo(this.size, -this.size, this.size * 2, this.size, 0, this.size * 2);
                this.ctx.bezierCurveTo(-this.size * 2, this.size, -this.size, -this.size, 0, 0);
                this.ctx.fill();
                
                this.ctx.restore();
            }
        }

        function initPetals() {
            const canvas = document.getElementById('petals-canvas');
            const ctx = canvas.getContext('2d');
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            
            const petals = Array.from({length: 40}, () => new Petal(canvas));
            
            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                petals.forEach(p => { p.update(); p.draw(); });
                requestAnimationFrame(animate);
            }
            animate();

            window.addEventListener('resize', () => {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
            });
        }

        function triggerRSVPExplosion() {
            const duration = 3000;
            const end = Date.now() + duration;

            (function frame() {
                confetti({
                    particleCount: 5,
                    angle: 60,
                    spread: 55,
                    origin: { x: 0 },
                    colors: ['#FFD1DC', '#B76E79', '#ffffff']
                });
                confetti({
                    particleCount: 5,
                    angle: 120,
                    spread: 55,
                    origin: { x: 1 },
                    colors: ['#FFD1DC', '#B76E79', '#ffffff']
                });

                if (Date.now() < end) {
                    requestAnimationFrame(frame);
                }
            }());
        }

        function blushData() {
            return {
                data: {},
                targetDate: new Date("{{ \Carbon\Carbon::parse($event->event_datetime)->toIso8601String() }}").getTime(),
                timeLeft: { days: '00', hours: '00', minutes: '00' },
                rsvpStatus: 'idle',
                
                initExperience() {
                    const el = document.getElementById('content-data');
                    if (el) {
                        try { this.data = JSON.parse(el.textContent); } catch (e) { console.error("JSON Error"); }
                    }

                    this.startCountdown();
                    initPetals();

                    this.$nextTick(() => {
                        this.setupPreloader();
                    });
                },

                splitLines(text) {
                    if(!text) return [];
                    return text.split('\n').filter(l => l.trim() !== '');
                },

                setupPreloader() {
                    const tl = gsap.timeline({
                        onComplete: () => {
                            setTimeout(() => this.startExperience(), 500);
                        }
                    });
                    
                    tl.to("#intro-text", {opacity: 1, duration: 2, ease: "power2.inOut"})
                      .to("#intro-text", {opacity: 0, duration: 1.5, ease: "power2.inOut"})
                      .to("#intro-names", {opacity: 1, duration: 2, ease: "power2.out"})
                      .to("#intro-names", {opacity: 0, duration: 1.5, delay: 1, ease: "power2.inOut"});
                },

                startExperience() {
                    document.getElementById('loader').style.display = 'none';
                    this.initScrollEngine();
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

                    gsap.to("#hero-bg", {
                        yPercent: 15, 
                        ease: "none",
                        scrollTrigger: { trigger: "#main-content", start: "top top", end: "bottom top", scrub: true }
                    });

                    const pages = document.querySelectorAll('.album-page');
                    if(pages.length > 0) {
                        const albumTl = gsap.timeline({
                            scrollTrigger: {
                                trigger: "#album-section",
                                start: "top center",
                                end: "+=1500", 
                                scrub: 1,
                                pin: true
                            }
                        });
                        
                        for(let i = 0; i < pages.length - 1; i++) {
                            albumTl.to(pages[i], {
                                rotateY: -180, // LTR: Flips to left
                                opacity: 0,
                                duration: 1,
                                ease: "power1.inOut"
                            });
                        }
                    }

                    const tunnelLayers = document.querySelectorAll('.tunnel-layer');
                    if(tunnelLayers.length > 0) {
                        const tunnelTl = gsap.timeline({
                            scrollTrigger: {
                                trigger: "#tunnel-section",
                                start: "top top",
                                end: "+=2000",
                                scrub: true,
                                pin: true
                            }
                        });
                        
                        tunnelLayers.forEach((layer, index) => {
                            tunnelTl.to(layer, {
                                scale: 3,
                                opacity: 0,
                                duration: 1
                            }, index * 0.5); 
                        });
                        
                        tunnelTl.to("#tunnel-text", {opacity: 0, scale: 1.5, duration: 1}, 0);
                    }

                    if(document.getElementById('gate-wrapper')) {
                        const gateTl = gsap.timeline({
                            scrollTrigger: {
                                trigger: "#venue-section",
                                start: "top center",
                                end: "top 10%",
                                scrub: true
                            }
                        });
                        
                        gateTl.to('.gate-left', { xPercent: -100, ease: "power1.inOut" }, 0)
                              .to('.gate-right', { xPercent: 100, ease: "power1.inOut" }, 0);
                    }

                    gsap.utils.toArray(".gs-fade-up").forEach(elem => {
                        gsap.fromTo(elem, {y: 40, opacity: 0}, {
                            y: 0, opacity: 1, duration: 1.5, ease: "power2.out",
                            scrollTrigger: { trigger: elem, start: "top 85%" }
                        });
                    });
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
        
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('native-rsvp-wrapper');
            if(form) {
                form.addEventListener('submit', (e) => {
                    triggerRSVPExplosion();
                });
            }
        });
    </script>
</body>
</html>
