<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <link rel="icon" type="image/png" href="/images/logo.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $event->title }}</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&family=Amiri:ital,wght@0,400;0,700;1,400&family=Great+Vibes&family=Aref+Ruqaa&display=swap" rel="stylesheet">
    
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        cinzel: ['Cinzel', 'serif'],
                        amiri: ['Amiri', 'serif'],
                        greatvibes: ['Great Vibes', 'cursive'],
                        ruqaa: ['Aref Ruqaa', 'serif'],
                    },
                    colors: {
                        'rose-gold': '#d4af37',
                        'floral-brown': '#4a3b32',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            background-color: #f7f3ee;
            font-family: 'Amiri', serif;
            color: #4a3b32;
            overflow-x: hidden;
        }
        
        .floral-bg {
            background-image: url('/images/themes/floral_arch_bg.png');
            background-size: cover;
            background-position: center;
            background-attachment: scroll;
            min-height: 100vh;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 5vh 5vw;
        }

        .glass-card {
            background: transparent; /* Remove the heavy glass card to make it look exactly like the photo */
            padding: 40px 20px;
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        .english-text {
            font-family: 'Cinzel', serif;
        }

        /* Micro animations */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up {
            animation: fadeUp 1.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }
    </style>
</head>
<body>

    <!-- Main Invitation Section (Full Screen) -->
    <section class="floral-bg relative">
        <div class="glass-card mt-[10vh] md:mt-[12vh] animate-fade-up">
            
            <!-- Script Header -->
            <p class="font-greatvibes text-5xl md:text-6xl text-[#6b5545] mb-2" style="line-height: 1.2;">Save The Date</p>
            <p class="text-xs md:text-sm tracking-[0.3em] text-[#6b5545] uppercase mb-10 font-cinzel">For the wedding ceremony of</p>
            
            <!-- Event Title -->
            <h1 class="font-amiri text-5xl md:text-6xl text-[#3b2d25] mb-8 font-bold leading-tight">
                {{ $event->title }}
            </h1>

            <!-- Decorative Swirl -->
            <div class="flex items-center justify-center gap-4 mb-8 opacity-60">
                <svg width="100" height="15" viewBox="0 0 100 20">
                    <path d="M0 10 Q 25 0, 50 10 T 100 10" fill="none" stroke="#4a3b32" stroke-width="1.5"/>
                    <circle cx="50" cy="10" r="2" fill="#4a3b32"/>
                </svg>
            </div>

            <!-- Host Message -->
            <p class="font-ruqaa text-2xl md:text-3xl text-[#4a3b32] mb-12 leading-relaxed max-w-md mx-auto px-4" x-data="{ desc: '{{ addslashes($event->content_data['host_message'] ?? 'بكل الحب والود، نتشرف بدعوتكم لمشاركتنا فرحة العمر') }}' }" x-text="desc"></p>

            <!-- Date & Location -->
            <div class="font-amiri text-lg md:text-xl text-[#3b2d25] tracking-wide leading-loose mb-10 space-y-1">
                <p class="uppercase font-bold">{{ \Carbon\Carbon::parse($event->event_datetime)->translatedFormat('l, F j, Y') }}</p>
                <p class="uppercase text-sm tracking-widest text-[#6b5545] mt-2 mb-1">
                    FROM {{ \Carbon\Carbon::parse($event->event_datetime)->format('g:i A') }}
                </p>
                <p class="text-lg">في {{ $event->location_name }}</p>
            </div>

            <!-- Decorative Swirl 2 -->
            <div class="flex items-center justify-center gap-4 mb-8 opacity-60">
                <svg width="80" height="15" viewBox="0 0 100 20">
                    <path d="M0 10 Q 25 0, 50 10 T 100 10" fill="none" stroke="#4a3b32" stroke-width="1"/>
                </svg>
            </div>

            <!-- Footer -->
            <p class="font-cinzel text-xs md:text-sm tracking-[0.2em] text-[#6b5545] uppercase">Reception to follow</p>
            <p class="font-amiri text-sm text-[#6b5545] mt-2">نرجو تأكيد حضوركم بالأسفل</p>
        </div>
    </section>

    <!-- RSVP Section -->
    <section class="py-20 px-4 bg-[#f7f3ee] border-t border-rose-gold/20" id="rsvp">
        <div class="max-w-xl mx-auto">
            <h2 class="font-ruqaa text-4xl text-center text-[#4a3b32] mb-10">تأكيد الحضور</h2>
            
            <div class="bg-white/80 backdrop-blur-sm border border-rose-gold/30 rounded-2xl p-8 md:p-10 shadow-xl mx-auto">
                @if(isset($guest) && $guest->name)
                    <p class="font-amiri text-2xl text-center text-[#6b5545] mb-8">أهلاً بك يا <span class="font-bold text-[#3b2d25]">{{ $guest->name }}</span></p>
                @endif
                
                @include('components.evently-rsvp', ['event' => $event, 'isPublic' => $isPublic ?? false, 'invitation' => $invitation ?? null, 'guest' => $guest ?? null])
            </div>
        </div>
    </section>

</body>
</html>
