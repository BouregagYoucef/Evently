<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <link rel="icon" type="image/png" href="/images/logo.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title }}</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    
    <!-- Google Fonts: Amiri (Classic/Elegant) & Tajawal (Clean Modern) -->
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Tajawal:wght@300;400;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #FDFBFB;
            /* Soft floral/watercolor background pattern */
            background-image: url('data:image/svg+xml;utf8,<svg width="400" height="400" xmlns="http://www.w3.org/2000/svg"><g fill="%23f4e1e1" fill-opacity="0.2"><circle cx="200" cy="200" r="150" filter="blur(20px)"/></g></svg>');
            color: #4A4A4A;
        }
        .font-classic {
            font-family: 'Amiri', serif;
        }
        
        .color-rose-gold { color: #B76E79; }
        .bg-rose-gold { background-color: #B76E79; }
        .border-rose-gold { border-color: #B76E79; }
        
        .color-blush { color: #F9EAEA; }
        .bg-blush { background-color: #F9EAEA; }

        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 10px 40px rgba(183, 110, 121, 0.1);
        }

        /* RSVP override for elegant forms */
        input, select, textarea {
            background: transparent !important;
            border: none !important;
            border-bottom: 1px solid #B76E79 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            padding: 10px 5px !important;
            font-family: 'Tajawal', sans-serif !important;
            text-align: right;
        }
        input:focus, select:focus, textarea:focus {
            outline: none !important;
            border-bottom: 2px solid #B76E79 !important;
        }
        .btn-submit {
            background-color: #B76E79 !important;
            color: white !important;
            border-radius: 9999px !important;
            padding: 12px 30px !important;
            font-family: 'Tajawal', sans-serif !important;
            box-shadow: 0 4px 15px rgba(183, 110, 121, 0.3) !important;
            transition: all 0.5s ease !important;
        }
        .btn-submit:hover {
            background-color: #a05c66 !important;
            transform: translateY(-2px) !important;
        }
        
        .floral-divider {
            width: 100%;
            height: 40px;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 10" preserveAspectRatio="none"><path d="M50 0 C60 5 40 5 50 10 C60 5 40 5 50 0" fill="%23B76E79" opacity="0.3"/></svg>');
            background-size: 100px 10px;
            background-repeat: repeat-x;
        }
    </style>
</head>
<body class="antialiased overflow-x-hidden selection:bg-rose-200 selection:text-rose-900" x-data="weddingData()" x-init="initWedding()">

    <!-- Inject Content Data -->
    <script id="content-data" type="application/json">@json($event->content_data)</script>

    <!-- Hidden audio player for background music -->
    <template x-if="data.background_music">
        <audio id="bgMusic" loop>
            <source :src="data.background_music" type="audio/mpeg">
        </audio>
    </template>

    <!-- Music Control Button -->
    <template x-if="data.background_music">
        <button @click="toggleMusic()" class="fixed top-6 right-6 z-50 bg-white/80 backdrop-blur rounded-full p-3 shadow-lg border border-rose-100 text-rose-500 hover:text-rose-700 transition">
            <span x-show="!isPlaying">🎵 تشغيل الزفة</span>
            <span x-show="isPlaying">🔇 إيقاف</span>
        </button>
    </template>

    <!-- Hero Section -->
    <section class="min-h-screen flex flex-col items-center justify-center text-center px-4 relative py-20">
        
        <!-- Subtle Animated Circles Background -->
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none opacity-50">
            <div class="absolute top-10 left-10 w-64 h-64 bg-rose-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
            <div class="absolute top-40 right-10 w-72 h-72 bg-pink-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-40 w-72 h-72 bg-orange-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
        </div>

        <div class="relative z-10 hero-element opacity-0 w-full max-w-2xl">
            
            <p class="font-classic text-xl md:text-2xl color-rose-gold mb-8 italic tracking-wide">
                بكل حب وسعادة، ندعوكم لمشاركتنا فرحتنا
            </p>

            <div class="flex flex-col md:flex-row items-center justify-center gap-6 mb-10">
                <h1 class="font-classic text-5xl md:text-7xl text-gray-800" x-text="data.groom_name || 'اسم العريس'"></h1>
                <span class="font-classic text-4xl color-rose-gold">&</span>
                <h1 class="font-classic text-5xl md:text-7xl text-gray-800" x-text="data.bride_name || 'اسم العروس'"></h1>
            </div>

            <div class="floral-divider my-10"></div>

            <p class="font-classic text-2xl md:text-3xl text-gray-700 mb-4 leading-relaxed">
                {{ $event->title }}
            </p>

            <div class="mt-12 bg-white/50 inline-block p-6 rounded-2xl border border-rose-100 shadow-sm">
                <p class="text-xl mb-2 text-gray-600">
                    <span class="block text-sm color-rose-gold mb-1">الموعد</span>
                    {{ \Carbon\Carbon::parse($event->event_datetime)->translatedFormat('l، j F Y') }}
                </p>
                <div class="w-12 h-px bg-rose-300 mx-auto my-3"></div>
                <p class="text-xl text-gray-600">
                    <span class="block text-sm color-rose-gold mb-1">المكان</span>
                    {{ $event->location_name }}
                </p>
            </div>
            
        </div>
    </section>

    <!-- Wedding Quote -->
    <template x-if="data.wedding_quote">
        <section class="py-20 px-4 bg-rose-50/50">
            <div class="max-w-3xl mx-auto text-center gs-fade">
                <span class="text-4xl color-rose-gold opacity-50 block mb-6">❝</span>
                <p class="font-classic text-3xl md:text-4xl text-gray-700 leading-loose" x-text="data.wedding_quote"></p>
                <span class="text-4xl color-rose-gold opacity-50 block mt-6">❞</span>
            </div>
        </section>
    </template>

    <!-- Our Story -->
    <template x-if="data.our_story">
        <section class="py-24 px-4 relative">
            <div class="max-w-2xl mx-auto text-center gs-fade glass-card p-10 rounded-3xl">
                <h2 class="font-classic text-4xl color-rose-gold mb-8">قصتنا</h2>
                <p class="text-lg leading-loose text-gray-600 whitespace-pre-line" x-text="data.our_story"></p>
            </div>
        </section>
    </template>

    <!-- Event Details (Schedule & Dress Code) -->
    <template x-if="data.schedule || data.dress_code">
        <section class="py-24 px-4 bg-white border-y border-rose-50">
            <div class="max-w-4xl mx-auto text-center gs-fade">
                <h2 class="font-classic text-4xl color-rose-gold mb-12">تفاصيل الحفل</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <template x-if="data.schedule">
                        <div class="p-8 border border-rose-100 rounded-2xl bg-rose-50/30 hover:bg-rose-50 transition duration-500">
                            <h3 class="text-2xl font-bold text-gray-800 mb-6">البرنامج</h3>
                            <p class="text-gray-600 leading-loose whitespace-pre-line" x-text="data.schedule"></p>
                        </div>
                    </template>
                    
                    <template x-if="data.dress_code">
                        <div class="p-8 border border-rose-100 rounded-2xl bg-rose-50/30 hover:bg-rose-50 transition duration-500">
                            <h3 class="text-2xl font-bold text-gray-800 mb-6">قواعد اللباس</h3>
                            <p class="text-gray-600 leading-loose whitespace-pre-line" x-text="data.dress_code"></p>
                            <div class="mt-6 flex justify-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-rose-200"></span>
                                <span class="w-6 h-6 rounded-full bg-pink-100"></span>
                                <span class="w-6 h-6 rounded-full bg-white border border-gray-200"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </section>
    </template>

    <!-- Gallery -->
    <template x-if="data.gallery && data.gallery.length > 0">
        <section class="py-24 px-4 bg-rose-50/20">
            <div class="max-w-5xl mx-auto">
                <h2 class="font-classic text-4xl color-rose-gold mb-12 text-center gs-fade">لحظاتنا الجميلة</h2>
                <div class="columns-1 md:columns-2 lg:columns-3 gap-6 space-y-6">
                    <template x-for="(img, idx) in data.gallery" :key="idx">
                        <div class="gs-fade break-inside-avoid rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition duration-700">
                            <img :src="img" class="w-full h-auto transform hover:scale-105 transition duration-1000">
                        </div>
                    </template>
                </div>
            </div>
        </section>
    </template>

    <!-- RSVP Section -->
    <section class="py-24 px-4 text-center">
        <div class="max-w-xl mx-auto gs-fade">
            <h2 class="font-classic text-4xl color-rose-gold mb-4">تأكيد الحضور</h2>
            <p class="text-gray-500 mb-10">حضوركم يكتمل به فرحنا.. نرجو تأكيد حضوركم</p>
            
            <div class="glass-card p-10 rounded-3xl">
                @include('components.evently-rsvp', ['event' => $event, 'isPublic' => $isPublic ?? false, 'invitation' => $invitation ?? null, 'guest' => $guest ?? null])
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 bg-rose-50/50 text-center border-t border-rose-100">
        <div class="floral-divider mb-8"></div>
        <div class="font-classic text-2xl text-gray-600 mb-2">
            <span x-text="data.groom_name"></span> & <span x-text="data.bride_name"></span>
        </div>
        <p class="text-gray-400 text-sm">دمتم بحب وفرح 🌸</p>
    </footer>

    <script>
        function weddingData() {
            return {
                data: {},
                isPlaying: false,
                initWedding() {
                    const el = document.getElementById('content-data');
                    if (el) {
                        try {
                            this.data = JSON.parse(el.textContent);
                        } catch (e) {
                            console.error("Invalid JSON data");
                        }
                    }

                    // Soft GSAP Animations
                    this.$nextTick(() => {
                        gsap.registerPlugin(ScrollTrigger);
                        
                        // Ultra slow fade in for Hero
                        gsap.to(".hero-element", {
                            y: 0, 
                            opacity: 1, 
                            duration: 2.5, 
                            ease: "power2.out",
                            delay: 0.2
                        });

                        // Very gentle scroll reveals
                        gsap.utils.toArray(".gs-fade").forEach(function(elem) {
                            ScrollTrigger.create({
                                trigger: elem,
                                start: "top 85%",
                                onEnter: function() {
                                    gsap.fromTo(elem, 
                                        {y: 30, opacity: 0}, 
                                        {y: 0, opacity: 1, duration: 1.5, ease: "sine.out"}
                                    );
                                }
                            });
                        });
                    });
                },
                toggleMusic() {
                    const audio = document.getElementById('bgMusic');
                    if(audio) {
                        if(this.isPlaying) {
                            audio.pause();
                        } else {
                            // If it's a youtube url we can't play it directly via <audio>.
                            // For a real app, we'd need a hidden iframe for YT audio. 
                            // This implementation assumes a direct audio file url (.mp3).
                            audio.play().catch(e => alert("المتصفح يمنع التشغيل التلقائي للصوت."));
                        }
                        this.isPlaying = !this.isPlaying;
                    }
                }
            }
        }
    </script>
</body>
</html>
