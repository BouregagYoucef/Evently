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
    <!-- Google Fonts: Cairo -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700;900&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #fcfbf9;
            color: #333;
        }
        .luxury-gold {
            color: #d4af37;
        }
        .luxury-bg {
            background-color: #1a1a1a;
        }
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        /* RSVP specific overrides for RTL */
        input, select, textarea {
            text-align: right !important;
            direction: rtl;
        }
    </style>
</head>
<body class="antialiased overflow-x-hidden" x-data="themeData()" x-init="initTheme()">

    <!-- Inject Content Data securely -->
    <script id="content-data" type="application/json">@json($event->content_data)</script>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center text-center px-4 overflow-hidden">
        <!-- Background Image or Color -->
        <div class="absolute inset-0 z-0">
            <template x-if="data.background_image">
                <img :src="data.background_image" class="w-full h-full object-cover opacity-80" alt="Background">
            </template>
            <template x-if="!data.background_image">
                <div class="w-full h-full bg-gradient-to-br from-gray-900 to-black"></div>
            </template>
            <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        </div>

        <div class="relative z-10 glass p-10 md:p-16 rounded-3xl shadow-2xl max-w-3xl w-full hero-content">
            <!-- Logo -->
            <template x-if="data.logo">
                <img :src="data.logo" class="h-24 mx-auto mb-6 object-contain" alt="Logo">
            </template>

            <!-- Event Subtitle -->
            <h2 x-show="data.event_subtitle" x-text="data.event_subtitle" class="text-xl md:text-2xl luxury-gold mb-2 tracking-widest font-light"></h2>
            
            <h1 class="text-4xl md:text-6xl font-black text-gray-900 mb-4 leading-tight">{{ $event->title }}</h1>
            
            <div class="w-24 h-1 bg-yellow-500 mx-auto my-6 rounded"></div>

            <p class="text-lg text-gray-700 mb-2">
                <span class="font-bold">التاريخ:</span> {{ \Carbon\Carbon::parse($event->event_datetime)->translatedFormat('l, j F Y') }}
            </p>
            <p class="text-lg text-gray-700 mb-6">
                <span class="font-bold">الموقع:</span> {{ $event->location_name }}
            </p>
            
            <div class="mt-8">
                <a href="#rsvp" class="inline-block bg-black text-white px-8 py-3 rounded-full hover:bg-gray-800 transition duration-300 font-bold shadow-lg">
                    تأكيد الحضور
                </a>
            </div>
        </div>
    </section>

    <!-- Intro Video -->
    <template x-if="data.intro_video || data.youtube_url">
        <section class="py-20 luxury-bg text-center px-4">
            <h2 class="text-3xl font-bold luxury-gold mb-10">فيديو ترويجي</h2>
            <div class="max-w-4xl mx-auto rounded-2xl overflow-hidden shadow-2xl aspect-video bg-black gs-reveal">
                <template x-if="data.intro_video">
                    <video :src="data.intro_video" controls class="w-full h-full object-cover"></video>
                </template>
                <template x-if="data.youtube_url && !data.intro_video">
                    <iframe :src="getYoutubeEmbedUrl(data.youtube_url)" class="w-full h-full" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </template>
            </div>
        </section>
    </template>

    <!-- Story Section -->
    <template x-if="data.story_title || data.story_content || data.host_message">
        <section class="py-24 px-4 bg-white text-center">
            <div class="max-w-3xl mx-auto gs-reveal">
                <h2 x-show="data.story_title" x-text="data.story_title" class="text-4xl font-black mb-6 text-gray-900"></h2>
                <p x-show="data.story_content" x-text="data.story_content" class="text-xl leading-relaxed text-gray-600 mb-10 whitespace-pre-line"></p>
                
                <template x-if="data.host_message">
                    <div class="bg-gray-50 border-r-4 border-yellow-500 p-8 rounded-lg shadow-sm text-right italic text-lg text-gray-700">
                        " <span x-text="data.host_message"></span> "
                    </div>
                </template>
            </div>
        </section>
    </template>

    <!-- Event Details (Schedule & Rules) -->
    <template x-if="data.schedule || data.dress_code || data.special_notes">
        <section class="py-20 luxury-bg text-white px-4 border-t-4 border-yellow-500">
            <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 gs-reveal">
                
                <div x-show="data.schedule" class="bg-gray-900 p-8 rounded-2xl border border-gray-800 hover:border-yellow-500 transition">
                    <div class="text-yellow-500 text-3xl mb-4">📅</div>
                    <h3 class="text-2xl font-bold mb-4">البرنامج الزمني</h3>
                    <p x-text="data.schedule" class="text-gray-300 leading-relaxed whitespace-pre-line"></p>
                </div>

                <div x-show="data.dress_code" class="bg-gray-900 p-8 rounded-2xl border border-gray-800 hover:border-yellow-500 transition">
                    <div class="text-yellow-500 text-3xl mb-4">👔</div>
                    <h3 class="text-2xl font-bold mb-4">قواعد اللباس</h3>
                    <p x-text="data.dress_code" class="text-gray-300 leading-relaxed"></p>
                </div>

                <div x-show="data.special_notes" class="bg-gray-900 p-8 rounded-2xl border border-gray-800 hover:border-yellow-500 transition">
                    <div class="text-yellow-500 text-3xl mb-4">✨</div>
                    <h3 class="text-2xl font-bold mb-4">ملاحظات هامة</h3>
                    <p x-text="data.special_notes" class="text-gray-300 leading-relaxed whitespace-pre-line"></p>
                </div>

            </div>
        </section>
    </template>

    <!-- Venue Section -->
    <template x-if="data.venue_description || data.venue_image">
        <section class="py-24 px-4 bg-gray-50">
            <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center gs-reveal">
                <div>
                    <h2 class="text-4xl font-black mb-6 text-gray-900">موقع الحدث</h2>
                    <h3 class="text-2xl font-bold mb-4 luxury-gold">{{ $event->location_name }}</h3>
                    <p x-show="data.venue_description" x-text="data.venue_description" class="text-lg text-gray-600 leading-relaxed mb-6 whitespace-pre-line"></p>
                    <p x-show="data.contact_info" class="text-gray-800 font-bold">
                        📞 للتواصل: <span x-text="data.contact_info" class="font-normal text-gray-600"></span>
                    </p>
                </div>
                <div x-show="data.venue_image" class="rounded-3xl overflow-hidden shadow-2xl">
                    <img :src="data.venue_image" class="w-full h-auto object-cover hover:scale-105 transition duration-700">
                </div>
            </div>
        </section>
    </template>

    <!-- Gallery Section -->
    <template x-if="data.gallery_images && data.gallery_images.length > 0">
        <section class="py-20 luxury-bg text-center px-4">
            <h2 class="text-4xl font-black text-white mb-12">معرض الصور</h2>
            <div class="max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-3 gap-4">
                <template x-for="(img, idx) in data.gallery_images" :key="idx">
                    <div class="aspect-square rounded-xl overflow-hidden shadow-lg gs-reveal">
                        <img :src="img" class="w-full h-full object-cover hover:scale-110 transition duration-500">
                    </div>
                </template>
            </div>
        </section>
    </template>

    <!-- Extra Information (Speakers, Sponsors, FAQ) -->
    <template x-if="data.speakers || data.sponsors || data.faq">
        <section class="py-24 px-4 bg-white">
            <div class="max-w-4xl mx-auto space-y-16 gs-reveal">
                
                <template x-if="data.speakers">
                    <div>
                        <h2 class="text-3xl font-bold mb-6 border-r-4 border-yellow-500 pr-4">أبرز الحضور / المتحدثين</h2>
                        <div class="flex flex-wrap gap-3">
                            <template x-for="speaker in splitComma(data.speakers)" :key="speaker">
                                <span class="bg-gray-100 px-4 py-2 rounded-full font-bold text-gray-700 border border-gray-200" x-text="speaker.trim()"></span>
                            </template>
                        </div>
                    </div>
                </template>

                <template x-if="data.sponsors">
                    <div>
                        <h2 class="text-3xl font-bold mb-6 border-r-4 border-yellow-500 pr-4">الرعاة الرسميون</h2>
                        <div class="flex flex-wrap gap-3">
                            <template x-for="sponsor in splitComma(data.sponsors)" :key="sponsor">
                                <span class="bg-indigo-50 px-4 py-2 rounded-lg font-bold text-indigo-700" x-text="sponsor.trim()"></span>
                            </template>
                        </div>
                    </div>
                </template>

                <template x-if="data.faq">
                    <div>
                        <h2 class="text-3xl font-bold mb-6 border-r-4 border-yellow-500 pr-4">أسئلة شائعة (FAQ)</h2>
                        <p x-text="data.faq" class="text-lg text-gray-600 leading-relaxed whitespace-pre-line bg-gray-50 p-6 rounded-xl"></p>
                    </div>
                </template>

            </div>
        </section>
    </template>

    <!-- RSVP Section -->
    <section id="rsvp" class="py-24 bg-gray-100 text-center px-4 border-t border-gray-200">
        <div class="max-w-2xl mx-auto gs-reveal">
            <h2 class="text-4xl font-black text-gray-900 mb-6">هل ستشاركنا الفرحة؟</h2>
            <p x-show="data.closing_message" x-text="data.closing_message" class="text-xl text-gray-600 mb-10"></p>
            
            <div class="bg-white p-8 md:p-12 rounded-3xl shadow-xl text-right">
                @include('components.evently-rsvp', ['event' => $event, 'isPublic' => $isPublic ?? false, 'invitation' => $invitation ?? null, 'guest' => $guest ?? null])
            </div>
            
            <template x-if="data.gift_registry">
                <div class="mt-12">
                    <a :href="data.gift_registry" target="_blank" class="inline-flex items-center text-yellow-600 font-bold hover:text-yellow-700 bg-white px-6 py-3 rounded-full shadow-md">
                        <span class="ml-2">🎁</span> رابط قائمة الهدايا (Gift Registry)
                    </a>
                </div>
            </template>
        </div>
    </section>

    <!-- Footer -->
    <footer class="luxury-bg py-10 text-center text-gray-400">
        <h3 x-show="data.event_type" x-text="data.event_type" class="text-lg mb-2 luxury-gold"></h3>
        <p>ننتظركم بكل حب ❤️</p>
        <template x-if="data.spotify_playlist_url">
            <div class="mt-6">
                <a :href="data.spotify_playlist_url" target="_blank" class="inline-block border border-green-500 text-green-500 px-6 py-2 rounded-full hover:bg-green-500 hover:text-white transition duration-300 text-sm">
                    🎵 استمع إلى قائمة الأغاني الخاصة بنا على Spotify
                </a>
            </div>
        </template>
    </footer>

    <script>
        function themeData() {
            return {
                data: {},
                initTheme() {
                    const el = document.getElementById('content-data');
                    if (el) {
                        try {
                            this.data = JSON.parse(el.textContent);
                        } catch (e) {
                            console.error("Invalid JSON data");
                        }
                    }

                    // GSAP Animations setup
                    this.$nextTick(() => {
                        gsap.registerPlugin(ScrollTrigger);
                        
                        // Reveal Elements on scroll
                        gsap.utils.toArray(".gs-reveal").forEach(function(elem) {
                            ScrollTrigger.create({
                                trigger: elem,
                                start: "top 80%",
                                onEnter: function() {
                                    gsap.fromTo(elem, 
                                        {y: 40, opacity: 0}, 
                                        {y: 0, opacity: 1, duration: 1, ease: "power2.out"}
                                    );
                                }
                            });
                        });
                    });
                },
                splitComma(str) {
                    if(!str) return [];
                    return str.split(',');
                },
                getYoutubeEmbedUrl(url) {
                    if(!url) return '';
                    let videoId = '';
                    if(url.includes('youtu.be/')) videoId = url.split('youtu.be/')[1];
                    else if(url.includes('v=')) videoId = url.split('v=')[1].split('&')[0];
                    return videoId ? `https://www.youtube.com/embed/${videoId}` : url;
                }
            }
        }
    </script>
</body>
</html>
