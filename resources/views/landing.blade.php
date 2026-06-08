<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Evently | Cinematic SaaS Invitations</title>
    <link rel="icon" type="image/png" href="/images/logo.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cairo:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts / Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    

    <style>
        body { font-family: 'Cairo', sans-serif; }
        [x-cloak] { display: none !important; }
        
        /* Page Loader */
        #page-loader {
            position: fixed; inset: 0; z-index: 9999;
            background: #FFFAF0;
            display: flex; justify-content: center; align-items: center;
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        #page-loader.hidden-loader { opacity: 0; visibility: hidden; transform: scale(1.05); }
        .loader-container { position: relative; display: flex; flex-direction: column; align-items: center; }
        
        .loader-ripple {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            width: 150px; height: 150px; background: rgba(193, 154, 107, 0.15);
            border-radius: 50%; animation: rippleAnim 2s infinite; z-index: 0;
        }
        .loader-ripple:nth-child(2) { animation-delay: 0.6s; }
        .loader-ripple:nth-child(3) { animation-delay: 1.2s; }
        @keyframes rippleAnim {
            0% { transform: translate(-50%, -50%) scale(0.5); opacity: 1; }
            100% { transform: translate(-50%, -50%) scale(3.5); opacity: 0; }
        }
        
        .loader-spinner {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            width: 180px; height: 180px; border-radius: 50%;
            border: 2px solid transparent; border-top-color: #C19A6B; border-left-color: rgba(193, 154, 107, 0.3);
            animation: spin 1.5s cubic-bezier(0.68, -0.55, 0.26, 1.55) infinite; z-index: 1;
        }
        .loader-logo { width: 100px; position: relative; z-index: 10; animation: pulse 2s infinite; }
        
        @keyframes spin { 100% { transform: translate(-50%, -50%) rotate(360deg); } }
        @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); } }

        /* Floating Hero Animations */
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-15px); } }
        .float-1 { animation: float 6s ease-in-out infinite; }
        .float-2 { animation: float 7s ease-in-out infinite; animation-delay: 1.5s; }
        .float-3 { animation: float 8s ease-in-out infinite; animation-delay: 3s; }
        
        .gradient-text {
            background: linear-gradient(135deg, #C19A6B 0%, #D4AF37 50%, #A8855A 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); border: 1px solid rgba(193, 154, 107, 0.2);
        }
    </style>

    <script>
        const translations = {
            ar: {
                home: 'الرئيسية', features: 'المميزات', pricing: 'الباقات والأسعار', contact: 'تواصل معنا', login: 'تسجيل الدخول', get_started: 'ابدأ الآن',
                hero_badge: '1# الخيار الأول للمناسبات', hero_title_1: 'دعوة مناسبتك..', hero_title_2: 'أسهل، أرقى، وذكية',
                hero_subtitle: 'أرسل دعواتك عبر واتساب، تابع حضور ضيوفك بسهولة، وادخلهم بباركود ذكي لحماية مناسبتك.',
                create_event: 'اصنع مناسبتك الآن', view_features: 'استعرض المميزات', events_count: '+4,000 مناسبة', rating: 'تقييم 4.9/5',
                sending_to: 'جاري الإرسال إلى', confirmed: 'أكدت حضورها', apologized: 'اعتذرت عن الحضور', guests_3: '✨ 3 ضيوف', wish_luck: 'نتمنى لها التوفيق', qr_entry: 'باركود الدخول', vip_access: 'VIP Access',
                how_it_works: 'كيف يعمل الموقع؟', three_steps: 'ثلاث خطوات بسيطة.. ومناسبتك جاهزة',
                step1_title: 'حمّل تصميمك', step1_desc: 'حمّل تصميم دعوتك أو اختر من قوالبنا السينمائية الفاخرة.', step2_title: 'أضف معازيمك', step2_desc: 'أضف أسماء ضيوفك بسهولة عبر إدخال يدوي أو استيراد Excel.', step3_title: 'إرسال وتتبع', step3_desc: 'بضغطة زر، ترسل الدعوات عبر واتساب وتابع الردود مباشرة.',
                feature_title: 'المميزات', feature_subtitle: 'كل ما تحتاجه لاستضافة المناسبة المثالية', feature_desc: 'أدوات قوية مصممة لجعل التخطيط وإدارة الضيوف أسهل وأرقى.',
                f1_title: 'إرسال دعوات عبر واتساب', f1_desc: 'أرسل دعواتك مباشرة عبر واتساب مع رابط خاص لكل ضيف.', f2_title: 'تأكيد الحضور التلقائي', f2_desc: 'متابعة لحظية لمن سيحضر، عدد المرافقين، ومن اعتذر.', f3_title: 'تذاكر الباركود', f3_desc: 'إصدار تذاكر بباركود لكل ضيف لضمان الدخول السلس.', f4_title: 'واجهات سينمائية', f4_desc: 'أبهر ضيوفك بواجهات متحركة احترافية.',
                pricing_title: 'باقات وأسعار بسيطة', pricing_subtitle: 'اختر الباقة التي تناسب حجم مناسبتك.', free: 'مجاني', starter: 'البداية', starter_desc: 'مثالية للمناسبات العائلية الصغيرة.', p1_f1: 'فعالية واحدة', p1_f2: 'حتى 50 ضيف', p1_f3: 'قوالب قياسية', popular: 'الأكثر طلباً', elite: 'الفاخرة (Elite)', elite_desc: 'لحفلات الزفاف والمناسبات الكبرى.', elite_price: '$49', per_event: '/ للفعالية', p2_f1: 'عدد غير محدود من الفعاليات', p2_f2: 'حتى 10,000 ضيف', p2_f3: 'قوالب سينمائية فاخرة', p2_f4: 'تصدير تذاكر الباركود', go_elite: 'اشترك الآن', footer_rights: 'جميع الحقوق محفوظة'
            },
            en: {
                home: 'Home', features: 'Features', pricing: 'Pricing', contact: 'Contact', login: 'Log In', get_started: 'Get Started',
                hero_badge: '#1 Choice for Elite Events', hero_title_1: 'Craft Unforgettable', hero_title_2: 'Digital Invitations',
                hero_subtitle: 'Send WhatsApp invites, track RSVPs seamlessly, and manage entry with smart QR codes.',
                create_event: 'Create Your Event', view_features: 'View Features', events_count: '4,000+ Events', rating: '4.9/5 Rating',
                sending_to: 'Sending to', confirmed: 'Confirmed', apologized: 'Apologized', guests_3: '✨ 3 Guests', wish_luck: 'Maybe next time', qr_entry: 'Entry QR Code', vip_access: 'VIP Access',
                how_it_works: 'How it Works?', three_steps: 'Three simple steps to your perfect event',
                step1_title: 'Upload Design', step1_desc: 'Upload your own design or choose from our premium cinematic templates.', step2_title: 'Add Guests', step2_desc: 'Easily add your guests manually or import them via Excel.', step3_title: 'Send & Track', step3_desc: 'Send invites via WhatsApp with one click and track RSVPs live.',
                feature_title: 'Features', feature_subtitle: 'Everything You Need for the Perfect Event', feature_desc: 'Powerful tools designed to make event planning and guest management elegant and effortless.',
                f1_title: 'WhatsApp Invitations', f1_desc: 'Send invites directly via WhatsApp with a unique link for each guest.', f2_title: 'Automated RSVP', f2_desc: 'Real-time tracking of who is attending, companions, and apologies.', f3_title: 'QR Code Tickets', f3_desc: 'Generate tickets with QR codes for secure check-in.', f4_title: 'Cinematic Themes', f4_desc: 'Wow your guests with professional animated interfaces.',
                pricing_title: 'Simple Pricing', pricing_subtitle: 'Choose the plan that fits your event size.', free: 'Free', starter: 'Starter', starter_desc: 'Perfect for small family gatherings.', p1_f1: '1 Event', p1_f2: 'Up to 50 Guests', p1_f3: 'Standard Templates', popular: 'POPULAR', elite: 'Elite Host', elite_desc: 'For weddings and major events.', elite_price: '$49', per_event: '/ event', p2_f1: 'Unlimited Events', p2_f2: 'Up to 10,000 Guests', p2_f3: 'Premium Cinematic Templates', p2_f4: 'Export QR Tickets', go_elite: 'Go Elite', footer_rights: 'All rights reserved', premium_themes: 'Premium Themes', crafted_for_luxury: 'Crafted for Luxury', live_preview: 'Live Preview'
            }
        };

        document.addEventListener('alpine:init', () => {
            Alpine.data('landing', () => ({
                lang: localStorage.getItem('appLang') || 'ar',
                mobileMenuOpen: false,
                scrolled: false,
                init() {
                    this.applyLang();
                    window.addEventListener('scroll', () => { this.scrolled = window.scrollY > 20; });
                },
                setLang(l) {
                    this.lang = l;
                    localStorage.setItem('appLang', l);
                    this.applyLang();
                },
                applyLang() {
                    document.documentElement.lang = this.lang;
                    document.documentElement.dir = this.lang === 'ar' ? 'rtl' : 'ltr';
                },
                t(key) { return translations[this.lang][key] || key; }
            }))
        });

        window.addEventListener('load', () => {
            setTimeout(() => { document.getElementById('page-loader').classList.add('hidden-loader'); }, 800);
            
            // Defer iframe loading
            setTimeout(() => {
                const iframes = document.querySelectorAll('.template-iframe');
                iframes.forEach(iframe => {
                    if(iframe.dataset.src) {
                        iframe.src = iframe.dataset.src;
                    }
                });
            }, 1000);
        });
    </script>
</head>
<body class="bg-[#FFFAF0] text-gray-900 overflow-x-hidden selection:bg-[#C19A6B] selection:text-white" x-data="landing">

    <!-- Page Loader -->
    <div id="page-loader">
        <div class="loader-container">
            <div class="loader-ripple"></div><div class="loader-ripple"></div><div class="loader-ripple"></div>
            <div class="loader-spinner"></div>
            <img src="/images/logo.png" class="loader-logo" alt="Evently Logo">
        </div>
    </div>

    <!-- Background glow elements -->
    <div class="fixed top-20 start-10 w-96 h-96 bg-[#C19A6B]/10 rounded-full blur-[100px] pointer-events-none z-[-1]"></div>
    <div class="fixed bottom-20 end-10 w-[500px] h-[500px] bg-yellow-600/5 rounded-full blur-[120px] pointer-events-none z-[-1]"></div>

    <!-- Navbar -->
    <nav :class="{ 'bg-white/90 backdrop-blur-md shadow-sm border-b border-gray-100': scrolled, 'bg-transparent': !scrolled }" class="fixed w-full z-50 transition-all duration-300 top-0">
        <div class="max-w-7xl mx-auto px-6 h-24 flex justify-between items-center">
            <!-- Logo -->
            <div class="flex items-center gap-3">
                <img src="/images/logo.png" alt="Evently Logo" class="h-10 w-auto object-contain drop-shadow-[0_0_15px_rgba(193,154,107,0.3)]">
                <span class="text-2xl font-black tracking-widest text-gray-900">EVENTLY</span>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-8 font-bold text-gray-600">
                <a href="#" class="hover:text-[#C19A6B] transition-colors" x-text="t('home')"></a>
                <a href="#features" class="hover:text-[#C19A6B] transition-colors" x-text="t('features')"></a>
                <a href="#templates" class="hover:text-[#C19A6B] transition-colors" x-text="t('premium_themes')"></a>
                <a href="#pricing" class="hover:text-[#C19A6B] transition-colors" x-text="t('pricing')"></a>
            </div>

            <div class="hidden md:flex items-center gap-4">
                <!-- Lang Switcher -->
                <div class="flex items-center gap-1 bg-gray-100 rounded-lg p-1 border border-gray-200">
                    <button @click="setLang('en')" :class="lang === 'en' ? 'bg-white text-[#C19A6B] shadow-sm' : 'text-gray-500 hover:text-gray-900'" class="px-3 py-1 text-xs font-bold rounded-md transition-all">EN</button>
                    <button @click="setLang('ar')" :class="lang === 'ar' ? 'bg-white text-[#C19A6B] shadow-sm' : 'text-gray-500 hover:text-gray-900'" class="px-3 py-1 text-xs font-bold rounded-md transition-all">عربي</button>
                </div>
                <a href="/dashboard/login" class="text-base font-bold text-gray-700 hover:text-[#C19A6B] transition-colors" x-text="t('login')"></a>
                <a href="/dashboard/register" class="px-8 py-3 rounded-full bg-[#C19A6B] text-white text-sm font-bold shadow-lg shadow-[#C19A6B]/30 hover:bg-yellow-600 hover:-translate-y-0.5 transition-all" x-text="t('get_started')"></a>
            </div>
            
            <!-- Mobile Toggle -->
            <button class="md:hidden text-gray-700" @click="mobileMenuOpen = !mobileMenuOpen">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-collapse x-cloak class="md:hidden bg-white border-b border-gray-100 shadow-xl">
            <div class="px-6 py-6 flex flex-col gap-6 text-center">
                <a href="#" @click="mobileMenuOpen = false" class="text-lg font-bold text-gray-700" x-text="t('home')"></a>
                <a href="#features" @click="mobileMenuOpen = false" class="text-lg font-bold text-gray-700" x-text="t('features')"></a>
                <a href="#templates" @click="mobileMenuOpen = false" class="text-lg font-bold text-gray-700" x-text="t('premium_themes')"></a>
                <a href="#pricing" @click="mobileMenuOpen = false" class="text-lg font-bold text-gray-700" x-text="t('pricing')"></a>
                
                <hr class="border-gray-100 my-2">
                
                <div class="flex justify-center gap-2">
                    <button @click="setLang('en')" :class="lang === 'en' ? 'bg-[#C19A6B] text-white shadow-sm' : 'bg-gray-100 text-gray-500'" class="px-4 py-2 text-sm font-bold rounded-lg transition-all">English</button>
                    <button @click="setLang('ar')" :class="lang === 'ar' ? 'bg-[#C19A6B] text-white shadow-sm' : 'bg-gray-100 text-gray-500'" class="px-4 py-2 text-sm font-bold rounded-lg transition-all">عربي</button>
                </div>
                
                <a href="/dashboard/login" class="text-lg font-bold text-gray-700" x-text="t('login')"></a>
                <a href="/dashboard/register" class="px-8 py-3 rounded-xl bg-[#C19A6B] text-white text-lg font-bold shadow-lg" x-text="t('get_started')"></a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-36 pb-24 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center relative z-10">
            <!-- Content -->
            <div class="flex flex-col items-center lg:items-start text-center lg:text-start" :class="lang === 'ar' ? 'lg:text-right' : 'lg:text-left'">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-card text-[#C19A6B] text-sm font-bold mb-8 shadow-sm">
                    <span class="relative flex h-2.5 w-2.5"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#C19A6B]"></span></span>
                    <span x-text="t('hero_badge')"></span>
                </div>
                
                <h1 class="text-5xl md:text-7xl font-black text-gray-900 tracking-tight mb-6 leading-tight">
                    <span x-text="t('hero_title_1')"></span><br>
                    <span class="gradient-text drop-shadow-sm" x-text="t('hero_title_2')"></span>
                </h1>
                
                <p class="text-xl text-gray-600 leading-relaxed mb-10 max-w-lg" x-text="t('hero_subtitle')"></p>
                
                <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                    <a href="/dashboard/register" class="px-10 py-4 rounded-full bg-[#C19A6B] text-white text-lg font-bold shadow-xl shadow-[#C19A6B]/40 hover:bg-yellow-600 hover:scale-[1.02] transition-all text-center" x-text="t('create_event')"></a>
                    <a href="#features" class="px-10 py-4 rounded-full bg-white border border-gray-200 text-gray-700 text-lg font-bold hover:border-[#C19A6B] hover:text-[#C19A6B] transition-all text-center" x-text="t('view_features')"></a>
                </div>
                
                <div class="flex items-center gap-6 text-sm text-gray-500 font-bold mt-10">
                    <div class="flex items-center gap-2"><span class="text-green-500">✓</span> <span x-text="t('events_count')"></span></div>
                    <div class="flex items-center gap-2"><span class="text-[#C19A6B]">★</span> <span x-text="t('rating')"></span></div>
                </div>
            </div>

            <!-- Floating Graphics -->
            <div class="relative h-[500px] hidden lg:block">
                <!-- Card 1: WhatsApp -->
                <div class="absolute top-10 end-10 float-1 glass-card p-4 rounded-2xl shadow-2xl flex items-center gap-4 w-72" x-data="{ names: ['Ahmed', 'Sarah', 'Khalid'], idx: 0 }" x-init="setInterval(()=>idx=(idx+1)%3, 3000)">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center font-bold text-2xl">W</div>
                    <div>
                        <div class="text-xs text-gray-400 font-bold">WhatsApp</div>
                        <div class="text-sm font-bold text-gray-900"><span x-text="t('sending_to')"></span> <span x-text="names[idx]" class="text-[#C19A6B]"></span>...</div>
                    </div>
                </div>
                
                <!-- Card 2: RSVP Status -->
                <div class="absolute top-1/2 start-0 -translate-y-1/2 float-2 glass-card p-4 rounded-2xl shadow-2xl border-s-4 border-green-500 flex items-center gap-4 w-72" x-data="{ statuses: [{n:'Noura', s:'confirmed'},{n:'Maha', s:'apologized'}], idx: 0 }" x-init="setInterval(()=>idx=(idx+1)%2, 3500)">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-white transition-colors duration-300" :class="statuses[idx].s === 'confirmed' ? 'bg-green-500' : 'bg-red-500'">✓</div>
                    <div>
                        <div class="text-sm font-bold text-gray-900"><span x-text="statuses[idx].n"></span> - <span x-text="statuses[idx].s === 'confirmed' ? t('confirmed') : t('apologized')" :class="statuses[idx].s === 'confirmed' ? 'text-green-600' : 'text-red-500'"></span></div>
                        <div class="text-xs text-gray-400 font-bold mt-1" x-text="statuses[idx].s === 'confirmed' ? t('guests_3') : t('wish_luck')"></div>
                    </div>
                </div>
                
                <!-- Card 3: QR Entry -->
                <div class="absolute bottom-10 end-20 float-3 bg-gray-900 p-4 rounded-2xl shadow-2xl border border-gray-800 flex items-center gap-4 w-64">
                    <div class="bg-white p-2 rounded-lg">
                        <svg class="w-10 h-10 text-gray-900" fill="currentColor" viewBox="0 0 16 16"><path d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1h-3zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5zM.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5zM4.5 5.5a.5.5 0 0 1 .5.5v4.5a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-4.5a.5.5 0 0 1 .5-.5h1zm2.5 0a.5.5 0 0 1 .5.5v4.5a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-4.5a.5.5 0 0 1 .5-.5h1z"/></svg>
                    </div>
                    <div class="text-white">
                        <div class="text-xs font-medium text-gray-400" x-text="t('qr_entry')"></div>
                        <div class="text-lg font-bold" x-text="t('vip_access')"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3 Steps Section -->
    <section class="py-24 bg-white/50 backdrop-blur-md border-y border-gray-100 relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-sm font-bold text-[#C19A6B] uppercase tracking-widest mb-2" x-text="t('how_it_works')"></h2>
                <h3 class="text-3xl md:text-4xl font-black text-gray-900" x-text="t('three_steps')"></h3>
            </div>
            <div class="grid md:grid-cols-3 gap-12 relative">
                <div class="hidden md:block absolute top-12 inset-x-[15%] h-0.5 bg-gradient-to-r from-[#FFFAF0] via-[#C19A6B] to-[#FFFAF0] z-0 opacity-30"></div>
                
                <!-- Step 1 -->
                <div class="relative z-10 flex flex-col items-center text-center">
                    <div class="w-24 h-24 rounded-full bg-white border-4 border-[#FFFAF0] text-[#C19A6B] shadow-xl flex items-center justify-center mb-6 text-3xl">🎨</div>
                    <h4 class="text-xl font-bold text-gray-900 mb-2" x-text="t('step1_title')"></h4>
                    <p class="text-gray-500 font-medium" x-text="t('step1_desc')"></p>
                </div>
                <!-- Step 2 -->
                <div class="relative z-10 flex flex-col items-center text-center">
                    <div class="w-24 h-24 rounded-full bg-white border-4 border-[#FFFAF0] text-[#C19A6B] shadow-xl flex items-center justify-center mb-6 text-3xl">👥</div>
                    <h4 class="text-xl font-bold text-gray-900 mb-2" x-text="t('step2_title')"></h4>
                    <p class="text-gray-500 font-medium" x-text="t('step2_desc')"></p>
                </div>
                <!-- Step 3 -->
                <div class="relative z-10 flex flex-col items-center text-center">
                    <div class="w-24 h-24 rounded-full bg-white border-4 border-[#FFFAF0] text-[#C19A6B] shadow-xl flex items-center justify-center mb-6 text-3xl">📨</div>
                    <h4 class="text-xl font-bold text-gray-900 mb-2" x-text="t('step3_title')"></h4>
                    <p class="text-gray-500 font-medium" x-text="t('step3_desc')"></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="py-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-sm font-bold text-gray-400 tracking-widest uppercase mb-2" x-text="t('feature_title')"></h2>
                <h3 class="text-3xl font-black text-gray-900 mb-4" x-text="t('feature_subtitle')"></h3>
                <p class="text-gray-500 font-medium" x-text="t('feature_desc')"></p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- F1 -->
                <div class="bg-white rounded-3xl p-8 border border-gray-100 hover:shadow-2xl hover:border-[#25D366]/50 transition-all group">
                    <div class="w-14 h-14 bg-green-50 text-[#25D366] rounded-2xl flex items-center justify-center text-2xl mb-6">W</div>
                    <h4 class="text-lg font-bold text-gray-900 mb-2" x-text="t('f1_title')"></h4>
                    <p class="text-gray-500 text-sm font-medium" x-text="t('f1_desc')"></p>
                </div>
                <!-- F2 -->
                <div class="bg-white rounded-3xl p-8 border border-gray-100 hover:shadow-2xl hover:border-[#C19A6B]/50 transition-all group">
                    <div class="w-14 h-14 bg-[#FFFAF0] text-[#C19A6B] rounded-2xl flex items-center justify-center text-2xl mb-6">✓</div>
                    <h4 class="text-lg font-bold text-gray-900 mb-2" x-text="t('f2_title')"></h4>
                    <p class="text-gray-500 text-sm font-medium" x-text="t('f2_desc')"></p>
                </div>
                <!-- F3 -->
                <div class="bg-white rounded-3xl p-8 border border-gray-100 hover:shadow-2xl hover:border-gray-900/50 transition-all group">
                    <div class="w-14 h-14 bg-gray-50 text-gray-900 rounded-2xl flex items-center justify-center text-2xl mb-6">📱</div>
                    <h4 class="text-lg font-bold text-gray-900 mb-2" x-text="t('f3_title')"></h4>
                    <p class="text-gray-500 text-sm font-medium" x-text="t('f3_desc')"></p>
                </div>
                <!-- F4 -->
                <div class="bg-white rounded-3xl p-8 border border-gray-100 hover:shadow-2xl hover:border-purple-500/50 transition-all group">
                    <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center text-2xl mb-6">✨</div>
                    <h4 class="text-lg font-bold text-gray-900 mb-2" x-text="t('f4_title')"></h4>
                    <p class="text-gray-500 text-sm font-medium" x-text="t('f4_desc')"></p>
                </div>
            </div>
        </div>
    </section>

        <!-- Templates Showcase Section -->
    <section id="templates" class="py-24 bg-[#FFFAF0] relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-sm font-bold text-[#C19A6B] uppercase tracking-widest mb-2" x-text="t('premium_themes')"></h2>
                <h3 class="text-3xl font-black text-gray-900 mb-4" x-text="t('crafted_for_luxury')"></h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($templates ?? [] as $template)
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 hover:shadow-2xl hover:border-[#C19A6B]/50 transition-all group">
                        <div class="flex justify-center mb-6">
                            <div class="relative w-full bg-gray-50 overflow-hidden shadow-lg group-hover:shadow-[0_0_30px_rgba(193,154,107,0.2)] transition-all duration-500 rounded-[20px] border-4 border-white" style="max-width: 320px; aspect-ratio: 9/16;">
                                <iframe data-src="{{ route('template.preview', $template->id) }}" class="template-iframe w-full h-full border-none pointer-events-none transition duration-500"></iframe>
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 bg-white/70 backdrop-blur-sm rounded-[16px]">
                                    <a href="{{ route('template.preview', $template->id) }}" target="_blank" class="px-5 py-3 rounded-full bg-[#C19A6B] text-white font-bold text-sm shadow-xl hover:scale-105 transition flex items-center gap-2" x-text="t('live_preview')"></a>
                                </div>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $template->name }}</h3>
                        <p class="text-gray-500 text-sm font-medium">Cinematic GSAP Theme</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    <!-- Pricing -->
    <section id="pricing" class="py-24 bg-white/30 border-y border-gray-100">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-black text-gray-900 mb-4" x-text="t('pricing_title')"></h2>
                <p class="text-gray-500 font-medium" x-text="t('pricing_subtitle')"></p>
            </div>
            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <div class="bg-white p-8 rounded-3xl border border-gray-200 shadow-sm">
                    <h3 class="text-2xl font-bold mb-2 text-gray-900" x-text="t('starter')"></h3>
                    <p class="text-gray-500 mb-6 font-medium" x-text="t('starter_desc')"></p>
                    <div class="text-4xl font-black mb-8 text-gray-900" x-text="t('free')"></div>
                    <ul class="space-y-4 mb-8 text-gray-600 font-bold">
                        <li>✓ <span x-text="t('p1_f1')"></span></li>
                        <li>✓ <span x-text="t('p1_f2')"></span></li>
                        <li>✓ <span x-text="t('p1_f3')"></span></li>
                    </ul>
                    <a href="/dashboard/register" class="block w-full text-center bg-gray-50 hover:bg-gray-100 border border-gray-200 text-gray-900 font-bold px-6 py-3 rounded-xl transition-colors" x-text="t('get_started')"></a>
                </div>
                <div class="bg-gray-900 p-8 rounded-3xl border border-[#C19A6B] relative shadow-2xl transform md:-translate-y-4">
                    <div class="absolute top-0 end-0 bg-[#C19A6B] text-white text-xs font-bold px-4 py-1.5 rounded-es-xl" x-text="t('popular')"></div>
                    <h3 class="text-2xl font-bold mb-2 text-[#C19A6B]" x-text="t('elite')"></h3>
                    <p class="text-gray-400 mb-6 font-medium" x-text="t('elite_desc')"></p>
                    <div class="text-4xl font-black mb-8 text-white"><span x-text="t('elite_price')"></span><span class="text-lg text-gray-500 font-medium" x-text="t('per_event')"></span></div>
                    <ul class="space-y-4 mb-8 text-gray-300 font-bold">
                        <li>✓ <span x-text="t('p2_f1')"></span></li>
                        <li>✓ <span x-text="t('p2_f2')"></span></li>
                        <li>✓ <span x-text="t('p2_f3')"></span></li>
                        <li>✓ <span x-text="t('p2_f4')"></span></li>
                    </ul>
                    <a href="/dashboard/register" class="block w-full text-center bg-[#C19A6B] text-white hover:bg-yellow-600 font-bold px-6 py-3 rounded-xl transition-colors shadow-lg shadow-[#C19A6B]/30" x-text="t('go_elite')"></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 text-center text-gray-400 font-bold">
        &copy; {{ date('Y') }} Evently. <span x-text="t('footer_rights')"></span>
    </footer>
</body>
</html>


