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
            --soft-pink: #FFB6C1;
            --rose-gold: #B76E79;
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
            opacity: 0.9;
        }

        /* Gold Liquid Text Effect */
        .text-liquid-gold {
            background: linear-gradient(to right, #B76E79, #E0BFB8, #D4AF37, #B76E79);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: 300% auto;
            animation: liquid-gold 6s linear infinite;
        }
        @keyframes liquid-gold {
            0% { background-position: 0% center; }
            100% { background-position: 300% center; }
        }

        /* Date Petal Texture */
        .text-petal-texture {
            background-image: url('https://images.unsplash.com/photo-1518895949257-7621c3c786d7?auto=format&fit=crop&q=80');
            background-size: cover;
            background-position: center;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            color: transparent;
        }

        /* Scene 1: Prelude */
        #prelude {
            position: fixed; inset: 0; background: var(--pearl-white); z-index: 10000;
            display: flex; flex-direction: column; justify-content: center; align-items: center;
        }

        /* Hero */
        .hero-bg {
            position: fixed; top: 0; left: 0; width: 100%; height: 100vh;
            z-index: 0; background-size: cover; background-position: center;
            filter: blur(2px) contrast(1.1);
            transform: scale(1.05);
        }
        .hero-bg::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(to bottom, rgba(253,251,247,0.3) 0%, var(--pearl-white) 100%);
        }
        
        #main-content {
            position: relative; z-index: 20; background: transparent;
        }

        /* SVG Timeline */
        .timeline-wrapper {
            position: relative;
            padding: 50px 0;
        }
        .timeline-line {
            position: absolute;
            top: 0; bottom: 0; left: 50%;
            width: 2px;
            background: rgba(183, 110, 121, 0.2);
            transform: translateX(-50%);
        }
        .timeline-progress {
            position: absolute;
            top: 0; left: 50%;
            width: 2px;
            background: var(--rose-gold);
            transform: translateX(-50%);
            height: 0%;
        }
        .timeline-node {
            position: relative;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 15vh 0;
        }
        .node-flower {
            position: absolute;
            width: 80px; height: 80px;
            background-image: url('https://raw.githubusercontent.com/youcef/assets/main/rose_top.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            z-index: 10;
            transform: scale(0);
            filter: drop-shadow(0 10px 15px rgba(183, 110, 121, 0.3));
        }

        /* Blooming Gallery */
        .gallery-item {
            position: relative;
            width: 100%; aspect-ratio: 4/5;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(183, 110, 121, 0.1);
        }
        .gallery-image {
            width: 100%; height: 100%; object-fit: cover;
            clip-path: circle(0% at 50% 50%);
            transition: clip-path 1.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .gallery-cover-flower {
            position: absolute; inset: -20%;
            background-image: url('https://raw.githubusercontent.com/youcef/assets/main/rose_top.png');
            background-size: cover;
            background-position: center;
            z-index: 5;
            transition: transform 1.5s ease, opacity 1s ease;
        }
        .gallery-item.bloomed .gallery-image { clip-path: circle(150% at 50% 50%); }
        .gallery-item.bloomed .gallery-cover-flower { transform: scale(2) rotate(45deg); opacity: 0; pointer-events: none; }

        /* Venue Gate */
        .gate-wrapper {
            position: relative;
            width: 100%; max-width: 800px; height: 60vh;
            overflow: hidden; margin: 0 auto;
            border-radius: 12px;
            box-shadow: 0 30px 60px rgba(183, 110, 121, 0.2);
        }
        .venue-img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
        .gate-half {
            position: absolute; top: 0; height: 100%; width: 50%;
            background: var(--pearl-white);
            border: 1px solid var(--rose-gold);
            display: flex; justify-content: center; align-items: center;
            z-index: 5;
        }
        .gate-left { left: 0; border-right: none; transform-origin: left; }
        .gate-right { right: 0; border-left: none; transform-origin: right; }
        
        /* Glassmorphism */
        .glass-card {
            background: rgba(253, 251, 247, 0.5);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(183, 110, 121, 0.3);
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(183, 110, 121, 0.1);
        }

        /* Forms */
        input, select, textarea {
            background: rgba(255,255,255,0.8) !important;
            border: 1px solid rgba(183, 110, 121, 0.2) !important;
            border-radius: 8px !important;
            color: var(--text-dark) !important;
            padding: 15px !important;
            transition: all 0.3s ease;
        }
        input:focus, select:focus, textarea:focus {
            outline: none !important;
            border-color: var(--rose-gold) !important;
            box-shadow: 0 0 15px rgba(183, 110, 121, 0.2);
        }
        .btn-submit, button[type="submit"] {
            background: linear-gradient(45deg, #B76E79, #DCAE96) !important;
            color: #fff !important;
            border: none !important;
            border-radius: 30px !important;
            padding: 15px 40px !important;
            font-family: 'Montserrat', sans-serif !important;
            font-weight: 500 !important;
            letter-spacing: 1px !important;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease !important;
            box-shadow: 0 10px 20px rgba(183, 110, 121, 0.3) !important;
        }
        .btn-submit:hover, button[type="submit"]:hover {
            transform: translateY(-3px) scale(1.02) !important;
            box-shadow: 0 15px 30px rgba(183, 110, 121, 0.5) !important;
        }

    </style>
</head>
<body x-data="roseData()" x-init="initExperience()">

    <script id="content-data" type="application/json">@json($event->content_data)</script>

    <canvas id="petals-canvas"></canvas>

    <!-- Scene 1: Prelude -->
    <div id="prelude">
        <h1 class="font-script text-4xl md:text-5xl text-[#B76E79] opacity-0 text-center leading-relaxed" id="prelude-text">
            Chaque Histoire d'Amour<br>a son Propre Jardin
        </h1>
    </div>

    <!-- Scene 2: Hero -->
    <div class="hero-bg" id="hero-bg">
        <img :src="data.cover_image || 'https://images.unsplash.com/photo-1519225421980-715cb0215aed?auto=format&fit=crop&q=80'" class="w-full h-full object-cover opacity-80">
    </div>

    <div id="main-content" style="opacity: 0;">
        <section class="h-[100vh] relative flex flex-col justify-center items-center pb-20">
            <div id="intro-names" class="text-center">
                <h2 class="font-ink text-7xl md:text-9xl text-liquid-gold gs-float italic" x-text="data.bride_name"></h2>
                <div class="text-3xl text-[#DCAE96] my-6 font-ink gs-float italic" style="animation-delay: 1s;">&</div>
                <h2 class="font-ink text-7xl md:text-9xl text-liquid-gold gs-float italic" style="animation-delay: 2s;" x-text="data.groom_name"></h2>
            </div>
        </section>

        <!-- Scene 3: The Love Garden (Timeline) -->
        <template x-if="data.story_content">
            <section class="py-[15vh] px-4 min-h-screen relative" id="garden-section">
                <div class="text-center mb-24 gs-fade-up">
                    <h2 class="font-ink text-5xl text-rosegold italic">Notre Jardin des Souvenirs</h2>
                </div>
                
                <div class="timeline-wrapper max-w-4xl mx-auto" id="timeline-container">
                    <div class="timeline-line"></div>
                    <div class="timeline-progress" id="timeline-progress"></div>
                    
                    <template x-for="(line, idx) in splitLines(data.story_content)" :key="idx">
                        <div class="timeline-node gs-story-node">
                            <div class="node-flower"></div>
                            <div class="w-1/2" :class="idx % 2 === 0 ? 'pl-12 md:pl-24 text-left ml-auto' : 'pr-12 md:pr-24 text-right mr-auto'">
                                <p class="font-ink text-3xl md:text-4xl leading-relaxed text-liquid-gold italic" x-text="line"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </section>
        </template>

        <!-- Scene 4: Blooming Gallery -->
        <template x-if="data.gallery && data.gallery.length > 0">
            <section class="py-[20vh] px-4 relative z-20" id="gallery-section">
                <div class="text-center mb-16 gs-fade-up">
                    <h2 class="font-ink text-5xl text-rosegold italic">Moments Fleuris</h2>
                </div>
                <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 px-4">
                    <template x-for="(img, idx) in data.gallery" :key="idx">
                        <div class="gallery-item cursor-pointer">
                            <div class="gallery-cover-flower"></div>
                            <img :src="img" class="gallery-image">
                        </div>
                    </template>
                </div>
            </section>
        </template>

        <!-- Scene 5: Wedding Day Reveal (Petal Text) -->
        <section class="py-[20vh] px-4 relative z-20 text-center overflow-hidden" id="date-section">
            <div class="gs-fade-up max-w-4xl mx-auto relative">
                <p class="font-script text-4xl text-[#DCAE96] mb-6">Notre Éternité Commence Le</p>
                <div class="font-ink text-7xl md:text-[8rem] text-petal-texture leading-none drop-shadow-2xl date-text">
                    <span x-text="extractDay(data.event_date)">14</span><br>
                    <span class="text-5xl md:text-7xl" x-text="extractMonth(data.event_date)">Juin</span><br>
                    <span class="text-5xl md:text-7xl" x-text="extractYear(data.event_date)">2030</span>
                </div>
            </div>
        </section>

        <!-- Scene 6: Venue Experience -->
        <template x-if="data.venue_name">
            <section class="py-[20vh] px-4 relative z-20" id="venue-section">
                <div class="text-center mb-16 gs-fade-up">
                    <h3 class="font-ink text-4xl text-rosegold italic">Le Lieu où Notre Amour Fleurira</h3>
                </div>
                
                <div class="gate-wrapper" id="gate-wrapper">
                    <img :src="data.venue_image" class="venue-img">
                    <div class="absolute inset-0 bg-black/20 flex flex-col items-center justify-center text-center z-10 p-8">
                        <h4 class="font-script text-5xl md:text-7xl text-white mb-6 drop-shadow-lg" x-text="data.venue_name"></h4>
                        <template x-if="data.venue_location">
                            <a :href="data.venue_location" target="_blank" class="px-8 py-3 bg-white/30 backdrop-blur-md border border-white/50 text-white rounded-full hover:bg-white hover:text-[#B76E79] transition uppercase tracking-widest text-xs shadow-xl">
                                Voir la Carte
                            </a>
                        </template>
                    </div>

                    <div class="gate-half gate-left"></div>
                    <div class="gate-half gate-right"></div>
                </div>
            </section>
        </template>

        <!-- Scene 7: Countdown -->
        <section class="py-[15vh] px-4 relative z-20 text-center">
            <div class="gs-fade-up glass-card max-w-3xl mx-auto p-12 md:p-16 rounded-full relative">
                <div class="absolute inset-0 border-[4px] border-dashed border-[#B76E79]/20 rounded-full animate-[spin_60s_linear_infinite]"></div>
                <div class="flex justify-center gap-8 md:gap-16">
                    <div class="text-center">
                        <div class="font-ink text-6xl md:text-7xl text-rosegold mb-2" x-text="timeLeft.days">00</div>
                        <div class="text-xs tracking-widest text-[#DCAE96] uppercase">Jours</div>
                    </div>
                    <div class="text-center">
                        <div class="font-ink text-6xl md:text-7xl text-rosegold mb-2" x-text="timeLeft.hours">00</div>
                        <div class="text-xs tracking-widest text-[#DCAE96] uppercase">Heures</div>
                    </div>
                    <div class="text-center">
                        <div class="font-ink text-6xl md:text-7xl text-rosegold mb-2" x-text="timeLeft.minutes">00</div>
                        <div class="text-xs tracking-widest text-[#DCAE96] uppercase">Minutes</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Scene 8 & 9: Personalized RSVP -->
        <section class="py-[20vh] px-4 relative z-20 flex justify-center" id="rsvp-section">
            <div class="w-full max-w-xl text-center relative z-10 gs-fade-up">
                
                @if(isset($guest) && $guest)
                    <p class="font-ink text-4xl text-[#B76E79] mb-4 italic">Chère {{ $guest->name ?? '' }}</p>
                    <h2 class="font-ink text-3xl md:text-4xl mb-12 text-[#4A3C31] leading-tight italic">Votre présence ajoutera une autre rose<br>à notre beau jardin</h2>
                @else
                    <h2 class="font-ink text-4xl md:text-5xl mb-12 text-[#B76E79] italic">Confirmer la Présence</h2>
                @endif

                <template x-if="data.host_message">
                    <p class="font-script text-3xl text-[#DCAE96] mb-8" x-text="data.host_message"></p>
                </template>

                <div class="glass-card p-8 md:p-14 relative" id="rsvp-card">
                    <div x-show="rsvpStatus === 'success'" class="absolute inset-0 flex flex-col items-center justify-center bg-[var(--pearl-white)]/95 backdrop-blur-md rounded-2xl z-20" style="display: none;">
                        <h3 class="font-ink text-5xl text-rosegold mb-4 italic">Présence Confirmée</h3>
                        <p class="text-[#4A3C31] text-lg">Nous avons hâte de vous y voir.</p>
                    </div>

                    <div id="native-rsvp-wrapper">
                        @include('components.evently-rsvp', ['event' => $event, 'isPublic' => $isPublic ?? false, 'invitation' => $invitation ?? null, 'guest' => $guest ?? null])
                    </div>
                </div>

            </div>
        </section>

        <!-- Scene 10: Farewell -->
        <footer class="py-[30vh] text-center relative z-20">
            <div class="gs-fade-up">
                <template x-if="data.closing_message">
                    <p class="font-ink text-3xl md:text-4xl text-[#B76E79] mb-16 leading-relaxed italic" x-text="data.closing_message"></p>
                </template>
                <template x-if="!data.closing_message">
                    <p class="font-ink text-3xl md:text-4xl text-[#B76E79] mb-16 leading-relaxed italic">Certains moments ne sont complets<br>qu'avec ceux que nous aimons</p>
                </template>
                
                <p class="font-ink text-6xl text-liquid-gold italic">
                    <span x-text="data.bride_name"></span> & <span x-text="data.groom_name"></span>
                </p>
            </div>
        </footer>
    </div>

    <script>
        class PetalEngine {
            constructor(canvasId) {
                this.canvas = document.getElementById(canvasId);
                this.ctx = this.canvas.getContext('2d');
                this.petals = [];
                this.state = 'prelude'; 
                this.targetX = window.innerWidth / 2;
                this.targetY = window.innerHeight / 2;
                this.preludeIndex = 0;
                
                this.resize();
                window.addEventListener('resize', () => this.resize());
                
                setTimeout(() => this.spawnPetal(), 1000); 
                setTimeout(() => this.spawnPetal(), 3000); 
                setTimeout(() => this.spawnPetal(), 4500); 
                
                this.animate();
            }

            resize() {
                this.canvas.width = window.innerWidth;
                this.canvas.height = window.innerHeight;
                this.targetX = this.canvas.width / 2;
                this.targetY = this.canvas.height / 2;
            }

            spawnPetal() {
                this.petals.push({
                    x: Math.random() * this.canvas.width,
                    y: -50,
                    size: Math.random() * 8 + 4,
                    speedY: Math.random() * 1.5 + 0.5,
                    speedX: Math.random() * 2 - 1,
                    rotation: Math.random() * 360,
                    rotationSpeed: Math.random() * 2 - 1,
                    opacity: Math.random() * 0.6 + 0.4
                });
            }

            startContinuous() {
                this.state = 'drift';
                for(let i=0; i<35; i++) {
                    this.petals.push({
                        x: Math.random() * this.canvas.width,
                        y: Math.random() * this.canvas.height,
                        size: Math.random() * 8 + 4,
                        speedY: Math.random() * 1.5 + 0.5,
                        speedX: Math.random() * 2 - 1,
                        rotation: Math.random() * 360,
                        rotationSpeed: Math.random() * 2 - 1,
                        opacity: Math.random() * 0.6 + 0.4
                    });
                }
            }

            triggerTargetMode() {
                this.state = 'target';
            }

            drawPetal(p) {
                this.ctx.save();
                this.ctx.translate(p.x, p.y);
                this.ctx.rotate((p.rotation * Math.PI) / 180);
                this.ctx.globalAlpha = p.opacity;
                this.ctx.fillStyle = '#FFB6C1';
                
                this.ctx.beginPath();
                this.ctx.moveTo(0, 0);
                this.ctx.bezierCurveTo(p.size, -p.size, p.size * 2, p.size, 0, p.size * 2.5);
                this.ctx.bezierCurveTo(-p.size * 2, p.size, -p.size, -p.size, 0, 0);
                this.ctx.fill();
                
                this.ctx.restore();
            }

            animate() {
                this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

                for (let i = this.petals.length - 1; i >= 0; i--) {
                    let p = this.petals[i];
                    
                    if (this.state === 'target') {
                        p.x += (this.targetX - p.x) * 0.05;
                        p.y += (this.targetY - p.y) * 0.05;
                        p.rotation += 10;
                        
                        let dist = Math.hypot(this.targetX - p.x, this.targetY - p.y);
                        if (dist < 20) {
                            this.petals.splice(i, 1);
                        }
                    } else {
                        p.y += p.speedY;
                        p.x += p.speedX + Math.sin(p.y * 0.01) * 0.8; 
                        p.rotation += p.rotationSpeed;

                        if (p.y > this.canvas.height) {
                            if(this.state === 'drift') {
                                p.y = -50;
                                p.x = Math.random() * this.canvas.width;
                            } else {
                                this.petals.splice(i, 1);
                            }
                        }
                    }
                    
                    if(this.petals[i]) this.drawPetal(p);
                }

                if (this.state === 'target' && this.petals.length === 0) {
                    this.state = 'done';
                    triggerConfettiExplosion(this.targetX, this.targetY);
                }

                requestAnimationFrame(() => this.animate());
            }
        }

        let petalEngine;

        function triggerConfettiExplosion(x, y) {
            const originX = x / window.innerWidth;
            const originY = y / window.innerHeight;
            
            confetti({
                particleCount: 150,
                spread: 100,
                origin: { x: originX, y: originY },
                colors: ['#FFB6C1', '#B76E79', '#ffffff'],
                shapes: ['circle']
            });
        }

        function roseData() {
            return {
                data: {},
                targetDate: new Date("{{ \Carbon\Carbon::parse($event->event_datetime)->toIso8601String() }}").getTime(),
                timeLeft: { days: '00', hours: '00', minutes: '00' },
                rsvpStatus: 'idle',
                
                extractDay(iso) { if(!iso) return '14'; return new Date(iso).getDate(); },
                extractMonth(iso) { 
                    if(!iso) return 'Juin'; 
                    const m = new Date(iso).toLocaleString('fr-FR', { month: 'long' });
                    return m.charAt(0).toUpperCase() + m.slice(1);
                },
                extractYear(iso) { if(!iso) return '2030'; return new Date(iso).getFullYear(); },

                initExperience() {
                    const el = document.getElementById('content-data');
                    if (el) {
                        try { this.data = JSON.parse(el.textContent); } catch (e) { console.error("JSON Error"); }
                    }

                    this.startCountdown();
                    petalEngine = new PetalEngine('petals-canvas');

                    this.$nextTick(() => {
                        this.setupPrelude();
                    });
                },

                splitLines(text) {
                    if(!text) return [];
                    return text.split('\n').filter(l => l.trim() !== '');
                },

                setupPrelude() {
                    const tl = gsap.timeline({
                        onComplete: () => {
                            setTimeout(() => this.startExperience(), 500);
                        }
                    });
                    
                    tl.to("#prelude-text", {opacity: 1, duration: 2.5, delay: 6, ease: "power2.inOut"})
                      .to("#prelude-text", {opacity: 0, duration: 2, delay: 2, ease: "power2.inOut"});
                },

                startExperience() {
                    gsap.to("#prelude", {opacity: 0, duration: 1.5, display: "none"});
                    gsap.to("#main-content", {opacity: 1, duration: 2});
                    
                    petalEngine.startContinuous();
                    this.initScrollEngine();
                },

                initScrollEngine() {
                    const lenis = new Lenis({
                        duration: 1.8, 
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
                        yPercent: 10, 
                        ease: "none",
                        scrollTrigger: { trigger: "#main-content", start: "top top", end: "bottom top", scrub: true }
                    });

                    gsap.utils.toArray(".gs-float").forEach(elem => {
                        gsap.to(elem, {
                            y: -15, duration: 3, yoyo: true, repeat: -1, ease: "sine.inOut"
                        });
                    });

                    if(document.getElementById('timeline-container')) {
                        gsap.to("#timeline-progress", {
                            height: "100%",
                            ease: "none",
                            scrollTrigger: {
                                trigger: "#garden-section",
                                start: "top 30%",
                                end: "bottom 80%",
                                scrub: true
                            }
                        });

                        gsap.utils.toArray(".gs-story-node").forEach(node => {
                            let flower = node.querySelector('.node-flower');
                            gsap.to(flower, {
                                scale: 1,
                                ease: "back.out(1.7)",
                                duration: 1,
                                scrollTrigger: {
                                    trigger: node,
                                    start: "top 60%"
                                }
                            });
                        });
                    }

                    gsap.utils.toArray(".gallery-item").forEach(item => {
                        ScrollTrigger.create({
                            trigger: item,
                            start: "top 70%",
                            onEnter: () => item.classList.add('bloomed'),
                            once: true
                        });
                    });

                    if(document.getElementById('gate-wrapper')) {
                        const gateTl = gsap.timeline({
                            scrollTrigger: {
                                trigger: "#venue-section",
                                start: "top 60%",
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
                    const card = document.getElementById('rsvp-card');
                    if(card && petalEngine) {
                        const rect = card.getBoundingClientRect();
                        petalEngine.targetX = rect.left + rect.width / 2;
                        petalEngine.targetY = rect.top + rect.height / 2;
                        petalEngine.triggerTargetMode();
                    }
                });
            }
        });
    </script>
</body>
</html>
