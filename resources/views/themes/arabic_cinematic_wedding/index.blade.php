<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <link rel="icon" type="image/png" href="/images/logo.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title }}</title>
    @include('partials.og-tags')
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- GSAP for core animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <!-- Lenis for smooth scrolling -->
    <script src="https://cdn.jsdelivr.net/gh/studio-freight/lenis@1.0.29/bundled/lenis.min.js"></script>
    
    <!-- Cinematic Typography -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Tajawal:wght@300;400;700&display=swap" rel="stylesheet">
    
    <style>
        /* Base Smooth Scroll Reset */
        html.lenis { height: auto; }
        .lenis.lenis-smooth { scroll-behavior: auto; }
        .lenis.lenis-smooth [data-lenis-prevent] { overscroll-behavior: contain; }
        .lenis.lenis-stopped { overflow: hidden; }
        .lenis.lenis-scrolling iframe { pointer-events: none; }
        
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #050505;
            color: #ffffff;
            margin: 0;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Preloader */
        #preloader {
            position: fixed;
            inset: 0;
            background: #000;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        /* Particles Canvas */
        #particles-canvas {
            position: absolute;
            inset: 0;
            z-index: 10;
            pointer-events: none;
        }

        /* Hero Background Video */
        .hero-video-container {
            position: fixed; /* Fix it so it stays while we scroll the content over it */
            top: 0; left: 0; width: 100%; height: 100vh;
            z-index: 0;
            overflow: hidden;
        }
        .hero-video-container::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.4) 0%, rgba(5,5,5,1) 100%);
            z-index: 1;
        }
        .hero-video-container video {
            width: 100%; height: 100%; object-fit: cover;
            transform: scale(1.05); /* To hide edges during slight parallax */
        }

        /* Content Overlays */
        #main-content {
            position: relative;
            z-index: 20; /* Keep content above fixed video */
            background: transparent;
        }

        .section-dark {
            background-color: #050505;
            position: relative;
            z-index: 20;
        }

        .text-reveal-mask {
            clip-path: polygon(0 0, 100% 0, 100% 100%, 0% 100%);
        }
        .text-reveal-mask span {
            display: inline-block;
            transform: translateY(100%);
        }

        /* Minimalist High-end Form */
        input, select, textarea {
            background: transparent !important;
            border: none !important;
            border-bottom: 1px solid #333 !important;
            border-radius: 0 !important;
            color: white !important;
            box-shadow: none !important;
            padding: 15px 5px !important;
            text-align: right;
            transition: border-color 0.4s ease;
        }
        input:focus, select:focus, textarea:focus {
            outline: none !important;
            border-bottom: 1px solid #fff !important;
        }
        .btn-submit {
            background: transparent !important;
            color: #fff !important;
            border: 1px solid #fff !important;
            border-radius: 0 !important;
            padding: 15px 40px !important;
            letter-spacing: 2px;
            transition: all 0.4s ease !important;
            margin-top: 20px !important;
        }
        .btn-submit:hover {
            background: #fff !important;
            color: #000 !important;
        }

        .scroll-indicator {
            width: 1px;
            height: 60px;
            background: rgba(255,255,255,0.2);
            position: relative;
            overflow: hidden;
        }
        .scroll-indicator::after {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: #fff;
            animation: scrollDown 2s infinite cubic-bezier(0.645, 0.045, 0.355, 1);
        }
        @keyframes scrollDown {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(100%); }
        }
    </style>
</head>
<body x-data="cinematicData()" x-init="initExperience()">

    <script id="content-data" type="application/json">@json($event->content_data)</script>

    <template x-if="data.background_music">
        <audio id="cinematicMusic" loop>
            <source :src="data.background_music" type="audio/mpeg">
        </audio>
    </template>

    <!-- Scene 1: Preloader -->
    <div id="preloader">
        <template x-if="data.loading_logo">
            <img :src="data.loading_logo" id="preloader-logo" class="h-24 opacity-0 mb-8 object-contain" alt="Logo">
        </template>
        <div class="h-px w-0 bg-white" id="preloader-line"></div>
        <p id="preloader-text" class="mt-6 text-xs tracking-[0.3em] uppercase text-gray-500 opacity-0">Loading Digital Experience</p>
    </div>

    <!-- Background Hero Video -->
    <div class="hero-video-container" id="hero-bg">
        <template x-if="data.cinematic_video">
            <video :src="data.cinematic_video" autoplay muted loop playsinline></video>
        </template>
        <!-- CSS Particles overlay -->
        <canvas id="particles-canvas"></canvas>
    </div>

    <div id="main-content">
        
        <!-- Scene 2: Hero Titles -->
        <section class="h-[150vh] flex flex-col items-center justify-center relative text-center px-4" id="hero-section">
            <!-- Sticky wrapper so it stays centered while scrolling through 150vh -->
            <div class="sticky top-1/2 -translate-y-1/2 w-full flex flex-col items-center" id="hero-text-wrapper">
                
                <p class="text-sm md:text-base tracking-[0.5em] text-gray-400 mb-8 uppercase hero-fade opacity-0">Presents</p>
                
                <div class="flex flex-col md:flex-row items-center justify-center gap-4 md:gap-16 mb-12 text-reveal-mask">
                    <span class="text-6xl md:text-8xl font-light" x-text="data.groom_name"></span>
                    <span class="text-4xl md:text-6xl font-light text-gray-600 italic">&</span>
                    <span class="text-6xl md:text-8xl font-light" x-text="data.bride_name"></span>
                </div>
                
                <p class="text-xs md:text-sm tracking-[0.4em] text-gray-500 mt-12 hero-fade opacity-0 uppercase">Scroll to Discover</p>
                <div class="scroll-indicator mt-6 hero-fade opacity-0"></div>
            </div>
        </section>

        <!-- Scene 3: Storytelling Narrative -->
        <template x-if="data.story_title || data.story_content">
            <section class="section-dark py-40 px-4" id="story-section">
                <div class="max-w-4xl mx-auto text-center gs-fade-up">
                    <h2 class="text-3xl md:text-5xl font-light mb-12 tracking-wide text-white/90" x-text="data.story_title"></h2>
                    <p class="text-xl md:text-3xl leading-relaxed text-gray-400 font-light whitespace-pre-line" x-text="data.story_content"></p>
                </div>
            </section>
        </template>

        <!-- Scene 4: Interactive Gallery Parallax -->
        <template x-if="data.gallery && data.gallery.length > 0">
            <section class="section-dark py-32 px-4 overflow-hidden border-t border-white/5" id="gallery-section">
                <div class="max-w-7xl mx-auto">
                    <p class="text-sm tracking-[0.3em] text-gray-500 mb-16 text-center uppercase gs-fade-up">Moments</p>
                    <div class="flex flex-col md:flex-row gap-8 justify-center items-center">
                        <template x-for="(img, idx) in data.gallery" :key="idx">
                            <!-- Different data-speed for each card creates parallax difference -->
                            <div class="w-full md:w-1/3 h-[60vh] relative overflow-hidden gallery-card rounded-sm" :data-speed="1 - (idx * 0.2)">
                                <img :src="img" class="absolute inset-0 w-full h-[130%] object-cover gallery-img" style="top:-15%">
                            </div>
                        </template>
                    </div>
                </div>
            </section>
        </template>

        <!-- Scene 5: Venue Reveal -->
        <template x-if="data.venue_image || data.venue_description">
            <section class="section-dark py-40 px-4 border-t border-white/5" id="venue-section">
                <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
                    <div class="gs-fade-up">
                        <p class="text-sm tracking-[0.3em] text-gray-500 mb-4 uppercase">The Venue</p>
                        <h2 class="text-4xl md:text-6xl font-light mb-8">{{ $event->location_name }}</h2>
                        <p class="text-lg text-gray-400 leading-relaxed whitespace-pre-line mb-10" x-text="data.venue_description"></p>
                        <div class="inline-block border border-white/20 p-6 rounded-sm">
                            <p class="text-2xl text-white font-light">{{ \Carbon\Carbon::parse($event->event_datetime)->translatedFormat('l, F j, Y') }}</p>
                            <p class="text-gray-500 mt-2">{{ \Carbon\Carbon::parse($event->event_datetime)->translatedFormat('h:i A') }}</p>
                        </div>
                    </div>
                    <!-- Reveal Animation Target -->
                    <div class="relative h-[70vh] overflow-hidden rounded-sm gs-reveal-img">
                        <div class="absolute inset-0 bg-black z-10 gs-reveal-overlay"></div>
                        <template x-if="data.venue_image">
                            <img :src="data.venue_image" class="w-full h-full object-cover scale-110 gs-reveal-target filter grayscale hover:grayscale-0 transition duration-1000">
                        </template>
                    </div>
                </div>
            </section>
        </template>

        <!-- Scene 7: RSVP Experience -->
        <section class="section-dark py-40 px-4 border-t border-white/5 text-center" id="rsvp-section">
            <div class="max-w-2xl mx-auto gs-fade-up">
                <p class="text-sm tracking-[0.3em] text-gray-500 mb-4 uppercase">RSVP</p>
                <h2 class="text-4xl md:text-5xl font-light mb-16">تأكيد الحضور</h2>
                
                <div class="text-right border border-white/10 p-10 md:p-16 rounded-sm bg-black/50 backdrop-blur-md rsvp-wrapper">
                    @include('components.evently-rsvp', ['event' => $event, 'isPublic' => $isPublic ?? false, 'invitation' => $invitation ?? null, 'guest' => $guest ?? null])
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="section-dark py-12 text-center border-t border-white/5">
            <p class="text-gray-600 text-xs tracking-widest uppercase">© {{ $event->title }} Experience</p>
        </footer>
    </div>

    <!-- Scripts -->
    <script>
        function cinematicData() {
            return {
                data: {},
                initExperience() {
                    const el = document.getElementById('content-data');
                    if (el) {
                        try {
                            this.data = JSON.parse(el.textContent);
                        } catch (e) {
                            console.error("Invalid JSON data");
                        }
                    }

                    // Start Preloader Sequence immediately
                    this.$nextTick(() => {
                        this.setupPreloader();
                    });
                },

                setupPreloader() {
                    const tl = gsap.timeline({
                        onComplete: () => {
                            setTimeout(() => this.startExperience(), 800);
                        }
                    });
                    
                    if(document.getElementById('preloader-logo')) {
                        tl.to("#preloader-logo", {opacity: 1, y: -20, duration: 1.5, ease: "power2.out", delay: 0.5});
                    }
                    
                    tl.to("#preloader-line", {width: "200px", duration: 1.5, ease: "power4.inOut"}, "-=0.5");
                    tl.to("#preloader-text", {opacity: 1, duration: 1}, "-=0.5");
                },

                startExperience() {
                    // Audio
                    const audio = document.getElementById('cinematicMusic');
                    if(audio) audio.play().catch(e=>console.log("Audio play blocked by browser:", e));

                    // Hide preloader
                    const tl = gsap.timeline({
                        onComplete: () => {
                            document.getElementById('preloader').style.display = 'none';
                            this.initLenisAndGSAP();
                            this.initParticles();
                        }
                    });

                    tl.to("#preloader-logo, #preloader-line, #preloader-text", {opacity: 0, duration: 0.5, stagger: 0.1});
                    tl.to("#preloader", {yPercent: -100, duration: 1.2, ease: "power4.inOut"});
                    
                    // Hero Text Reveal (Curtain style text reveal)
                    tl.to(".text-reveal-mask span", {y: "0%", duration: 1.5, ease: "power4.out", stagger: 0.2}, "-=0.5");
                    tl.to(".hero-fade", {opacity: 1, duration: 1.5, stagger: 0.2}, "-=1");
                },

                initLenisAndGSAP() {
                    // 1. Lenis Smooth Scroll Setup
                    const lenis = new Lenis({
                        duration: 1.5, // High duration = smoother/cinematic feel
                        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)), 
                        direction: 'vertical',
                        gestureDirection: 'vertical',
                        smooth: true,
                    });

                    function raf(time) {
                        lenis.raf(time);
                        requestAnimationFrame(raf);
                    }
                    requestAnimationFrame(raf);

                    // 2. GSAP ScrollTrigger Integration
                    gsap.registerPlugin(ScrollTrigger);
                    
                    // Connect Lenis with ScrollTrigger
                    lenis.on('scroll', ScrollTrigger.update);
                    gsap.ticker.add((time) => { lenis.raf(time * 1000) });
                    gsap.ticker.lagSmoothing(0);

                    // --- Animation Definitions --- //

                    // Parallax Video Hero (Moves slightly up as you scroll down)
                    gsap.to("#hero-bg", {
                        yPercent: 30, 
                        ease: "none",
                        scrollTrigger: {
                            trigger: "#hero-section",
                            start: "top top",
                            end: "bottom top",
                            scrub: true
                        }
                    });

                    // Global Fade/Float Up for elements
                    gsap.utils.toArray(".gs-fade-up").forEach(elem => {
                        gsap.fromTo(elem, 
                            {y: 60, opacity: 0},
                            {
                                y: 0, opacity: 1, duration: 1.8, ease: "power3.out",
                                scrollTrigger: {
                                    trigger: elem,
                                    start: "top 85%", // Trigger when element is 85% from top of viewport
                                }
                            }
                        );
                    });

                    // Gallery Parallax Effect
                    gsap.utils.toArray(".gallery-card").forEach(card => {
                        const img = card.querySelector(".gallery-img");
                        const speed = parseFloat(card.getAttribute("data-speed"));
                        
                        gsap.to(img, {
                            yPercent: 30 * speed, // Different speeds create depth
                            ease: "none",
                            scrollTrigger: {
                                trigger: "#gallery-section",
                                start: "top bottom",
                                end: "bottom top",
                                scrub: true
                            }
                        });
                    });

                    // Venue Image Reveal (Black Curtain slide down, Image scales down to normal)
                    gsap.utils.toArray(".gs-reveal-img").forEach(container => {
                        const overlay = container.querySelector(".gs-reveal-overlay");
                        const img = container.querySelector(".gs-reveal-target");
                        
                        const tl = gsap.timeline({
                            scrollTrigger: {
                                trigger: container,
                                start: "top 75%",
                            }
                        });
                        
                        tl.to(overlay, {height: 0, duration: 1.5, ease: "power4.inOut"})
                          .to(img, {scale: 1, duration: 2.5, ease: "power2.out"}, "-=1.5");
                    });
                },

                // Cinematic Dust Particles System
                initParticles() {
                    const canvas = document.getElementById('particles-canvas');
                    const ctx = canvas.getContext('2d');
                    let width, height, particles;

                    function resize() {
                        width = canvas.width = window.innerWidth;
                        height = canvas.height = window.innerHeight;
                    }
                    
                    window.addEventListener('resize', resize);
                    resize();

                    particles = [];
                    const particleCount = 120; // Dense cinematic dust

                    for(let i=0; i<particleCount; i++) {
                        particles.push({
                            x: Math.random() * width,
                            y: Math.random() * height,
                            r: Math.random() * 1.5 + 0.2, // Tiny specs
                            vx: (Math.random() - 0.5) * 0.3, // Very slow drift
                            vy: (Math.random() - 0.5) * 0.3,
                            alpha: Math.random() * 0.5 + 0.1
                        });
                    }

                    function draw() {
                        ctx.clearRect(0, 0, width, height);
                        particles.forEach(p => {
                            p.x += p.vx;
                            p.y += p.vy - 0.1; // Slight constant upward drift
                            
                            // Wrap around edges seamlessly
                            if(p.x < 0) p.x = width;
                            if(p.x > width) p.x = 0;
                            if(p.y < 0) p.y = height;
                            if(p.y > height) p.y = 0;

                            ctx.beginPath();
                            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                            ctx.fillStyle = `rgba(255, 255, 255, ${p.alpha})`;
                            ctx.fill();
                        });
                        requestAnimationFrame(draw);
                    }
                    draw();
                }
            }
        }
    </script>
</body>
</html>

