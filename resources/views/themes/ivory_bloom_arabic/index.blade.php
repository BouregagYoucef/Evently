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
    
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Tajawal:wght@300;400;500&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --ivory: #FDFBF7;
            --gold: #D4AF37;
            --brown: #4A3C31;
            --sage: #A3B19B;
        }

        body {
            background-color: #EAE6DF;
            font-family: 'Tajawal', sans-serif;
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 40px;
            padding: 20px;
        }

        .font-serif-ar {
            font-family: 'Amiri', serif;
        }

        .card-container {
            background-color: var(--ivory);
            position: relative;
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            /* Responsive Dimensions */
            max-width: 450px; /* Mobile / Default */
            min-height: 800px; /* 9:16 approx for mobile */
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        @media (min-width: 768px) {
            .card-container {
                max-width: 600px;
                min-height: 750px; /* 4:5 approx for tablet */
            }
        }

        @media (min-width: 1024px) {
            .card-container {
                max-width: 800px;
                min-height: 1000px;
            }
        }

        .card-border {
            position: absolute;
            inset: 20px;
            border: 1px solid var(--gold);
            pointer-events: none;
            z-index: 10;
        }

        .floral-top-left {
            position: absolute;
            top: -20px;
            left: -20px;
            width: 250px;
            opacity: 0.85;
            pointer-events: none;
            z-index: 5;
        }

        .floral-bottom-right {
            position: absolute;
            bottom: -20px;
            right: -20px;
            width: 250px;
            transform: rotate(180deg);
            opacity: 0.85;
            pointer-events: none;
            z-index: 5;
        }

        .content-wrapper {
            position: relative;
            z-index: 20;
            padding: 60px 40px;
            text-align: center;
            color: var(--brown);
        }

        .divider {
            width: 60px;
            height: 1px;
            background-color: var(--gold);
            margin: 24px auto;
        }

        .text-gold-custom {
            color: var(--gold);
        }
    </style>
</head>
<body x-data="invitationData()" x-init="initData()">

    <script id="content-data" type="application/json">@json($event->content_data)</script>

    <div class="card-container">
        <!-- Thin Champagne Gold Border -->
        <div class="card-border"></div>

        <div class="content-wrapper">
            <!-- Header -->
            <p class="font-serif-ar text-xl md:text-2xl mb-8">بسم الله الرحمن الرحيم</p>
            <p class="text-sm md:text-base tracking-widest mb-6">يسر عائلتا</p>

            <!-- Names -->
            <h1 class="font-serif-ar text-5xl md:text-6xl text-gold-custom mb-4" x-text="data.groom_name"></h1>
            <p class="font-serif-ar text-3xl md:text-4xl text-gray-400 mb-4">و</p>
            <h1 class="font-serif-ar text-5xl md:text-6xl text-gold-custom mb-8" x-text="data.bride_name"></h1>

            <!-- Invitation Message -->
            <div class="divider"></div>
            <p class="text-lg md:text-xl leading-relaxed mb-8 max-w-sm mx-auto" x-text="data.invitation_message || 'يتشرفان بدعوتكم لمشاركتهم فرحة حفل زفافهما'"></p>

            <!-- Date & Time -->
            <div class="mb-8">
                <p class="font-serif-ar text-2xl md:text-3xl text-gold-custom mb-2" x-text="data.event_date"></p>
                <p class="text-base md:text-lg" x-text="data.event_time"></p>
            </div>

            <div class="divider"></div>

            <!-- Venue -->
            <div class="mb-10">
                <p class="font-serif-ar text-2xl md:text-3xl mb-2" x-text="data.venue_name"></p>
                <p class="text-sm md:text-base text-gray-500" x-text="data.venue_city"></p>
            </div>

            <!-- Closing -->
            <p class="text-sm md:text-base leading-relaxed italic" x-text="data.host_message || 'وجودكم يشرفنا ويزيد فرحتنا جمالاً'"></p>
        </div>
    </div>

    <!-- RSVP Section -->
    <div class="card-container" style="min-height: auto; padding-top: 40px; padding-bottom: 40px; margin-top: -20px;">
        <div class="card-border"></div>
        <div class="content-wrapper" style="padding: 20px 40px;">
            <p class="font-serif-ar text-2xl text-gold-custom mb-6">تأكيد الحضور</p>
            <div class="divider mb-8"></div>
            <div class="w-full text-right" style="direction: rtl;">
                @include('components.evently-rsvp', ['event' => $event, 'isPublic' => $isPublic ?? false, 'invitation' => $invitation ?? null, 'guest' => $guest ?? null])
            </div>
        </div>
    </div>

    <script>
        function invitationData() {
            return {
                data: {},
                initData() {
                    const el = document.getElementById('content-data');
                    if (el) {
                        try {
                            this.data = JSON.parse(el.textContent);
                        } catch (e) {
                            console.error("JSON Error");
                        }
                    }
                }
            }
        }
    </script>
</body>
</html>

