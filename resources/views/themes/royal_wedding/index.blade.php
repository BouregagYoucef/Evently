<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="/images/logo.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title }}</title>
    @include('partials.og-tags')
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,400&family=Montserrat:wght@200;300;400&display=swap" rel="stylesheet">
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { gold: '#c5a059', dark: '#111111' },
                    fontFamily: { serif: ['Cormorant Garamond', 'serif'], sans: ['Montserrat', 'sans-serif'] }
                }
            }
        }
    </script>
    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <style>
        body { background-color: #fcfbf9; color: #333; overflow-x: hidden; }
        .hero { min-height: 100vh; position: relative; display: flex; align-items: center; justify-content: center; text-align: center; }
        .hero-bg { position: absolute; inset: 0; background-size: cover; background-position: center; z-index: 0; opacity: 0.15; filter: grayscale(50%); }
        .content-layer { position: relative; z-index: 10; }
        .divider { width: 1px; height: 80px; background-color: #c5a059; margin: 40px auto; }
        
        .fade-up { opacity: 0; transform: translateY(30px); }
        .split-text span { display: inline-block; opacity: 0; transform: translateY(20px); }
    </style>
</head>
<body class="font-sans antialiased">

    <!-- DATA INJECTION -->
    <script id="content-data" type="application/json">@json($event->content_data)</script>
    
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-bg" id="bg-layer" @if(!empty($event->content_data['cover_image'])) style="background-image: url('{{ $event->content_data['cover_image'] }}');" @endif></div>
        <div class="content-layer px-6">
            <p class="font-sans tracking-[0.3em] text-sm uppercase text-gray-500 mb-6 gs-reveal">You are invited to the wedding of</p>
            <h1 class="font-serif text-6xl md:text-8xl text-dark mb-4" id="main-title">{{ $event->title }}</h1>
            <p class="font-serif text-2xl italic text-gold gs-reveal">Reserved for {{ $invitation->guest_name ?? 'Guest' }}</p>
            <div class="divider gs-reveal"></div>
            <p class="font-sans text-sm tracking-widest uppercase gs-reveal">{{ \Carbon\Carbon::parse($event->event_datetime)->translatedFormat('d . m . Y') }}</p>
            <p class="font-sans text-xs tracking-widest uppercase mt-2 text-gray-500 gs-reveal">{{ $event->location_name }}</p>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="py-32 px-6 max-w-3xl mx-auto text-center">
        <h2 class="font-serif text-4xl text-gold mb-8 gs-reveal" id="story-title">Our Story</h2>
        <p class="font-serif text-xl leading-relaxed text-gray-700 gs-reveal" id="story-content"></p>
    </section>

    <!-- Gallery Section -->
    <section class="py-24 bg-dark text-white px-6">
        <div class="max-w-6xl mx-auto">
            <h2 class="font-serif text-4xl text-gold mb-16 text-center gs-reveal">Memories</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8" id="gallery-grid">
                <!-- Gallery items injected via JS -->
            </div>
        </div>
    </section>

    <!-- RSVP Section -->
    <section class="py-32 px-6 max-w-xl mx-auto text-center">
        <h2 class="font-serif text-4xl text-gold mb-8 gs-reveal">Will you join us?</h2>
        <div class="gs-reveal rsvp-container">
            @include('components.evently-rsvp', ['event' => $event, 'isPublic' => $isPublic ?? false, 'invitation' => $invitation ?? null, 'guest' => $guest ?? null])
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            gsap.registerPlugin(ScrollTrigger);

            // Read Data from JSON container
            const dataEl = document.getElementById('content-data');
            let contentData = {};
            if (dataEl) {
                try {
                    contentData = JSON.parse(dataEl.textContent);
                } catch(e) {}
            }

            // Inject Data into DOM
            if (contentData.story_title) {
                document.getElementById('story-title').innerText = contentData.story_title;
            }
            if (contentData.story_content) {
                document.getElementById('story-content').innerText = contentData.story_content;
            }

            // Build Gallery
            const galleryGrid = document.getElementById('gallery-grid');
            if (contentData.gallery && contentData.gallery.length > 0) {
                contentData.gallery.forEach((url, i) => {
                    if(url) {
                        const div = document.createElement('div');
                        div.className = 'aspect-[4/5] overflow-hidden rounded opacity-0 translate-y-8 gallery-item';
                        div.innerHTML = `<img src="${url}" class="w-full h-full object-cover hover:scale-105 transition duration-700">`;
                        galleryGrid.appendChild(div);
                    }
                });
            } else {
                galleryGrid.parentElement.style.display = 'none';
            }

            // --- ANIMATIONS ---
            
            // Hero Title Parallax & Fade
            gsap.from("#main-title", {
                duration: 2, y: 50, opacity: 0, ease: "power4.out", delay: 0.2
            });

            gsap.from(".gs-reveal", {
                duration: 1.5,
                y: 30,
                opacity: 0,
                stagger: 0.2,
                ease: "power3.out",
                delay: 0.5
            });

            // Scroll Triggers for Elements
            gsap.utils.toArray('.gs-reveal').forEach(elem => {
                gsap.fromTo(elem, 
                    { y: 50, opacity: 0 },
                    {
                        scrollTrigger: {
                            trigger: elem,
                            start: "top 85%",
                        },
                        duration: 1.5,
                        y: 0,
                        opacity: 1,
                        ease: "power3.out"
                    }
                );
            });

            // Gallery Stagger Reveal
            ScrollTrigger.batch(".gallery-item", {
                onEnter: batch => gsap.to(batch, {opacity: 1, y: 0, stagger: 0.2, duration: 1.5, ease: "power3.out"}),
                start: "top 85%",
            });
            
            // Background Parallax
            gsap.to("#bg-layer", {
                yPercent: 30,
                ease: "none",
                scrollTrigger: {
                    trigger: ".hero",
                    start: "top top",
                    end: "bottom top",
                    scrub: true
                } 
            });
        });
    </script>
</body>
</html>

