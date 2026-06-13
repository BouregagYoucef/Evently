<!DOCTYPE html>
<html lang="en" dir="ltr">
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
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --moon-color: rgba(200, 220, 255, 0.15);
        }

        html.lenis { height: auto; }
        .lenis.lenis-smooth { scroll-behavior: auto; }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #000000;
            color: #ffffff;
            margin: 0;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .font-royal { font-family: 'Playfair Display', serif; }

        #moon-light {
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 80vw; height: 80vw;
            max-width: 800px; max-height: 800px;
            background: radial-gradient(circle, var(--moon-color) 0%, transparent 60%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 1;
            transition: background 2s ease, opacity 1s ease;
            opacity: 0;
        }

        #loader {
            position: fixed; inset: 0; background: #000; z-index: 9999;
            display: flex; justify-content: center; align-items: center;
        }

        .hero-bg {
            position: fixed; top: 0; left: 0; width: 100%; height: 100vh;
            z-index: 0; background-size: cover; background-position: center;
        }
        .hero-bg::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, #000000 100%);
        }
        
        #main-content {
            position: relative; z-index: 20; background: transparent;
        }
        .section-dark {
            position: relative; z-index: 20;
        }

        .char-reveal span {
            opacity: 0;
            display: inline-block;
            filter: blur(4px);
            transform: translateY(10px);
        }

        .polaroid-wrapper {
            position: relative;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .polaroid {
            background: #fff;
            padding: 15px 15px 50px 15px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.8);
            position: absolute;
            transform-origin: center center;
            will-change: transform;
        }
        .polaroid img {
            width: 100%; height: 100%; object-fit: cover;
            filter: grayscale(30%) contrast(1.2);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            box-shadow: 0 40px 100px rgba(0,0,0,0.9);
        }

        .curtain-reveal {
            clip-path: inset(0 50% 0 50%);
            will-change: clip-path;
        }

        input, select, textarea {
            background: rgba(255,255,255,0.05) !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            border-radius: 4px !important;
            color: white !important;
            padding: 15px !important;
            transition: all 0.3s ease;
        }
        input:focus, select:focus, textarea:focus {
            outline: none !important;
            border-color: rgba(255,255,255,0.4) !important;
            background: rgba(255,255,255,0.1) !important;
        }
        .btn-submit, button[type="submit"] {
            background: rgba(255,255,255,0.1) !important;
            backdrop-filter: blur(10px);
            color: #fff !important;
            border: 1px solid rgba(255,255,255,0.2) !important;
            border-radius: 4px !important;
            padding: 15px 40px !important;
            font-family: 'Montserrat', sans-serif !important;
            font-weight: 300 !important;
            letter-spacing: 2px !important;
            cursor: pointer;
            transition: all 0.5s ease !important;
        }
        .btn-submit:hover, button[type="submit"]:hover {
            background: rgba(255,255,255,0.9) !important;
            color: #000 !important;
            box-shadow: 0 0 30px rgba(255,255,255,0.5) !important;
        }
        
        .space-bg {
            background-image: radial-gradient(circle, #ffffff 1px, transparent 1px);
            background-size: 80px 80px;
            opacity: 0.1;
        }

        .moon-graphic {
            width: 200px; height: 200px;
            border-radius: 50%;
            box-shadow: inset -25px -25px 40px rgba(0,0,0,0.5), 0 0 50px rgba(200, 220, 255, 0.4);
            background: #d9e0e8;
            margin: 0 auto;
            animation: rotateMoon 120s linear infinite;
            background-image: url('https://www.transparenttextures.com/patterns/black-paper.png');
        }
        @keyframes rotateMoon { 100% { transform: rotate(360deg); } }

    </style>
</head>
<body x-data="moonlightData()" x-init="initExperience()">

    <script id="content-data" type="application/json">@json($event->content_data)</script>

    <div id="moon-light"></div>

    <div id="loader">
        <h1 class="font-royal text-3xl md:text-4xl text-gray-400 opacity-0 tracking-widest italic" id="bismillah-text">
            With all our love
        </h1>
    </div>

    <div class="hero-bg" id="hero-bg">
        <template x-if="data.hero_video && data.hero_video.includes('.mp4')">
            <video :src="data.hero_video" autoplay muted loop playsinline class="w-full h-full object-cover opacity-60"></video>
        </template>
        <template x-if="!data.hero_video || !data.hero_video.includes('.mp4')">
            <img :src="data.hero_video || 'https://images.unsplash.com/photo-1502481851512-e9e2529bfbf9?auto=format&fit=crop&q=80'" class="w-full h-full object-cover opacity-60 filter grayscale-[0.3]">
        </template>
    </div>

    <div id="main-content">
        <section class="h-[120vh] relative flex flex-col justify-center items-center text-center px-4" id="hero-section">
            <div class="sticky top-[35vh] w-full max-w-4xl">
                <h2 class="font-royal text-6xl md:text-8xl text-white drop-shadow-2xl flex flex-col justify-center items-center gap-6">
                    <div id="groom-name" class="char-reveal" x-text="data.groom_name"></div>
                    <div id="hero-amp" class="opacity-0 text-3xl text-gray-400 italic">&</div>
                    <div id="bride-name" class="char-reveal" x-text="data.bride_name"></div>
                </h2>
            </div>
        </section>

        <template x-if="data.story_content">
            <section class="section-dark py-[30vh] px-4 min-h-screen flex items-center" id="story-section">
                <div class="max-w-3xl mx-auto text-center space-y-24">
                    <template x-for="(line, idx) in splitLines(data.story_content)" :key="idx">
                        <p class="font-royal text-3xl md:text-5xl leading-relaxed text-gray-200 gs-story-line opacity-0 italic" x-text="line"></p>
                    </template>
                </div>
            </section>
        </template>

        <template x-if="data.gallery && data.gallery.length > 0">
            <section class="section-dark py-[20vh] px-4" id="gallery-section">
                <div class="polaroid-wrapper">
                    <template x-for="(img, idx) in data.gallery.slice(0, 3)" :key="idx">
                        <div class="polaroid" 
                             :style="`width: 280px; height: 350px; z-index: ${idx+1}; left: ${40 + (idx*10)}%; top: ${20 + (idx*15)}%; transform: translate(-50%, -50%) rotate(${idx % 2 == 0 ? -5 : 7}deg);`">
                            <img :src="img">
                        </div>
                    </template>
                </div>
            </section>
        </template>

        <section class="section-dark py-[30vh] px-4 flex justify-center" id="invitation-section">
            <div class="glass-card p-12 md:p-20 max-w-2xl text-center gs-fade-up">
                <p class="font-royal text-4xl md:text-5xl leading-loose text-white mb-8 italic">
                    We have the honor<br>to invite you to our wedding
                </p>
                <div class="w-12 h-px bg-white/30 mx-auto"></div>
            </div>
        </section>

        <template x-if="data.venue_name">
            <section class="section-dark py-[20vh] px-4 relative flex flex-col items-center" id="venue-section">
                <h3 class="font-royal text-2xl text-gray-500 mb-12 gs-fade-up tracking-widest uppercase">The Venue</h3>
                
                <div class="w-full max-w-5xl h-[60vh] curtain-reveal overflow-hidden relative shadow-2xl">
                    <img :src="data.venue_image" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center p-8 text-center">
                        <h4 class="font-royal text-5xl md:text-7xl text-white mb-6" x-text="data.venue_name"></h4>
                        <div class="w-16 h-px bg-white/50 mb-10"></div>
                        <p class="text-xl md:text-2xl text-gray-300 font-light" x-text="data.venue_address"></p>
                    </div>
                </div>

                <div class="mt-16 gs-fade-up">
                    <template x-if="data.map_link">
                        <a :href="data.map_link" target="_blank" class="inline-flex items-center gap-3 text-white border-b border-white/30 pb-1 hover:border-white transition text-sm tracking-widest uppercase">
                            View Map
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </a>
                    </template>
                </div>
            </section>
        </template>

        <section class="section-dark py-[25vh] px-4 text-center relative overflow-hidden" id="countdown-section">
            <div class="absolute inset-0 space-bg pointer-events-none"></div>
            
            <div class="relative z-10 gs-fade-up">
                <div class="moon-graphic mb-16 relative"></div>
                
                <div class="flex justify-center gap-8 md:gap-16">
                    <div class="text-center">
                        <div class="text-5xl md:text-7xl font-light mb-2 text-white" x-text="timeLeft.days">00</div>
                        <div class="text-xs tracking-widest text-gray-500 uppercase">Days</div>
                    </div>
                    <div class="text-center">
                        <div class="text-5xl md:text-7xl font-light mb-2 text-white" x-text="timeLeft.hours">00</div>
                        <div class="text-xs tracking-widest text-gray-500 uppercase">Hours</div>
                    </div>
                    <div class="text-center">
                        <div class="text-5xl md:text-7xl font-light mb-2 text-white" x-text="timeLeft.minutes">00</div>
                        <div class="text-xs tracking-widest text-gray-500 uppercase">Minutes</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-dark py-[20vh] px-4 relative flex justify-center" id="rsvp-section">
            <div class="w-full max-w-xl text-center relative z-10 gs-fade-up">
                
                @if(isset($guest) && $guest)
                    <p class="font-royal text-3xl text-gray-400 mb-4 tracking-wider italic">Welcome {{ $guest->name ?? '' }}</p>
                    <h2 class="font-royal text-4xl md:text-5xl mb-16 text-white leading-tight italic">We have prepared this invitation specially for you</h2>
                @else
                    <h2 class="font-royal text-4xl md:text-5xl mb-16 text-white italic">Confirm your Attendance</h2>
                @endif

                <div class="glass-card p-10 md:p-14 relative" id="rsvp-card">
                    <div x-show="rsvpStatus === 'success'" class="absolute inset-0 flex flex-col items-center justify-center bg-black/90 backdrop-blur-md rounded-2xl z-20" style="display: none;">
                        <h3 class="font-royal text-4xl text-[#d4af37] mb-4 italic">Attendance Confirmed</h3>
                        <p class="text-gray-400 text-lg">We can't wait to see you there.</p>
                    </div>

                    <div id="native-rsvp-wrapper">
                        @include('components.evently-rsvp', ['event' => $event, 'isPublic' => $isPublic ?? false, 'invitation' => $invitation ?? null, 'guest' => $guest ?? null])
                    </div>
                </div>

            </div>
        </section>

        <footer class="section-dark py-[30vh] text-center relative">
            <div class="absolute inset-0 space-bg pointer-events-none opacity-5"></div>
            
            <div class="relative z-10 gs-fade-up">
                <template x-if="data.closing_message">
                    <p class="font-royal text-3xl md:text-4xl text-gray-400 mb-16 italic" x-text="data.closing_message"></p>
                </template>
                <template x-if="!data.closing_message">
                    <p class="font-royal text-3xl md:text-4xl text-gray-400 mb-16 italic">Your presence will make this night unforgettable</p>
                </template>
                
                <p class="font-royal text-4xl text-white tracking-widest">
                    <span x-text="data.groom_name"></span> <span class="italic">&</span> <span x-text="data.bride_name"></span>
                </p>
            </div>
        </footer>
    </div>

    <script>
        function moonlightData() {
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

                    this.$nextTick(() => {
                        this.wrapChars(document.getElementById('groom-name'));
                        this.wrapChars(document.getElementById('bride-name'));
                        this.setupPreloader();
                    });
                },

                wrapChars(element) {
                    if(!element) return;
                    const text = element.innerText;
                    element.innerHTML = '';
                    for (let i = 0; i < text.length; i++) {
                        let span = document.createElement('span');
                        span.innerText = text[i] === ' ' ? '\u00A0' : text[i];
                        element.appendChild(span);
                    }
                },

                splitLines(text) {
                    if(!text) return [];
                    return text.split('\n').filter(l => l.trim() !== '');
                },

                setupPreloader() {
                    const tl = gsap.timeline({
                        onComplete: () => {
                            setTimeout(() => this.startExperience(), 100);
                        }
                    });
                    tl.to("#bismillah-text", {opacity: 1, duration: 2, ease: "power2.inOut"})
                      .to("#bismillah-text", {opacity: 0, duration: 1.5, delay: 1.5, ease: "power2.inOut"});
                },

                startExperience() {
                    document.getElementById('loader').style.display = 'none';
                    this.initScrollEngine();

                    const tl = gsap.timeline();
                    
                    tl.to("#groom-name span", {
                        opacity: 1, y: 0, filter: "blur(0px)",
                        duration: 1, stagger: 0.1, ease: "power2.out"
                    })
                    .to("#hero-amp", {opacity: 1, duration: 1, ease: "power2.out"}, "+=0.5")
                    .to("#bride-name span", {
                        opacity: 1, y: 0, filter: "blur(0px)",
                        duration: 1, stagger: 0.1, ease: "power2.out"
                    }, "+=0.5");
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
                        scrollTrigger: { trigger: "#hero-section", start: "top top", end: "bottom top", scrub: true }
                    });

                    gsap.to("#moon-light", {
                        opacity: 1,
                        scrollTrigger: {
                            trigger: "#story-section",
                            start: "top 80%",
                            end: "top 20%",
                            scrub: true
                        }
                    });

                    gsap.utils.toArray(".gs-story-line").forEach(line => {
                        gsap.fromTo(line, {y: 30, opacity: 0}, {
                            y: 0, opacity: 1, duration: 1.5, ease: "power2.out",
                            scrollTrigger: { trigger: line, start: "top 70%" }
                        });
                    });

                    const polaroids = document.querySelectorAll('.polaroid');
                    if(polaroids.length > 0) {
                        polaroids.forEach((p, i) => {
                            gsap.to(p, {
                                y: -150 - (i * 50), 
                                ease: "none",
                                scrollTrigger: {
                                    trigger: "#gallery-section",
                                    start: "top bottom",
                                    end: "bottom top",
                                    scrub: 1
                                }
                            });
                        });
                    }

                    if(document.querySelector('.curtain-reveal')) {
                        gsap.to('.curtain-reveal', {
                            clipPath: 'inset(0 0% 0 0%)',
                            ease: "power2.inOut",
                            scrollTrigger: {
                                trigger: "#venue-section",
                                start: "top 70%",
                                end: "top 20%",
                                scrub: true
                            }
                        });
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
                    document.documentElement.style.setProperty('--moon-color', 'rgba(212, 175, 55, 0.25)');
                });
            }
        });
    </script>
</body>
</html>

