<x-filament-panels::page>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <style>
        .scan-wrapper {
            max-width: 1000px;
            margin: 0 auto;
            direction: rtl;
        }
        .scan-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .scan-header h2 {
            font-size: 2rem;
            font-weight: bold;
            color: var(--text-color, #1f2937);
            margin-bottom: 0.5rem;
        }
        .scan-header p {
            font-size: 0.875rem;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        .scan-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        @media(min-width: 768px) {
            .scan-grid {
                grid-template-columns: 1.2fr 1fr;
            }
        }
        .scanner-card {
            background: #fff;
            border-radius: 1.5rem;
            padding: 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }
        html.dark .scanner-card {
            background: #111827;
            border-color: #374151;
        }
        #reader {
            width: 100%;
            border-radius: 1rem;
            overflow: hidden;
            background: #000;
            min-height: 350px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .scanner-controls {
            margin-top: 1.5rem;
            text-align: center;
        }
        .btn-toggle {
            background: #C19A6B;
            color: #fff;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 9999px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(193, 154, 107, 0.3);
        }
        .btn-toggle:hover {
            background: #a8855a;
            transform: translateY(-2px);
        }
        .result-card {
            background: #fff;
            border-radius: 1.5rem;
            padding: 2.5rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            min-height: 350px;
        }
        html.dark .result-card {
            background: #1f2937;
            border-color: #374151;
        }
        .status-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem auto;
        }
        .icon-idle { background: #f3f4f6; color: #9ca3af; }
        .icon-scanning { background: rgba(193, 154, 107, 0.2); color: #C19A6B; border: 4px solid #C19A6B; border-top-color: transparent; animation: spin 1s linear infinite; }
        .icon-success { background: #10b981; color: #fff; box-shadow: 0 0 20px rgba(16, 185, 129, 0.4); }
        .icon-error { background: #ef4444; color: #fff; box-shadow: 0 0 20px rgba(239, 68, 68, 0.4); }
        
        .status-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: var(--text-color, #1f2937);
        }
        html.dark .status-title { color: #f9fafb; }
        .status-desc {
            color: #6b7280;
            font-size: 0.95rem;
            line-height: 1.5;
        }
        .guest-info-box {
            background: #f9fafb;
            border: 1px solid #f3f4f6;
            border-radius: 1rem;
            padding: 1.5rem;
            width: 100%;
            margin-top: 1.5rem;
        }
        html.dark .guest-info-box {
            background: #111827;
            border-color: #374151;
        }
        .guest-name {
            font-size: 1.25rem;
            font-weight: bold;
            color: #111827;
            margin-bottom: 1rem;
        }
        html.dark .guest-name { color: #f9fafb; }
        .guest-companions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
        }
        html.dark .guest-companions { border-color: #374151; }
        .badge {
            background: #C19A6B;
            color: #fff;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-weight: bold;
        }
        @keyframes spin { 100% { transform: rotate(360deg); } }
    </style>

    <div x-data="ticketScanner()" x-init="initScanner()" class="scan-wrapper">
        <div class="scan-header">
            <h2>بوابة فحص التذاكر</h2>
            <p>Evently VIP Access</p>
        </div>

        <div class="scan-grid">
            <!-- Scanner Area -->
            <div class="scanner-card">
                <div id="reader"></div>
                <div class="scanner-controls">
                    <button @click="toggleScanner" class="btn-toggle">
                        <span x-text="isScanning ? 'إيقاف الكاميرا' : 'تشغيل الكاميرا'"></span>
                    </button>
                </div>
            </div>

            <!-- Results Area -->
            <div class="result-card">
                
                <!-- IDLE -->
                <template x-if="status === 'idle'">
                    <div>
                        <div class="status-icon icon-idle">
                            <svg style="width: 40px; height: 40px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        </div>
                        <div class="status-title">في انتظار المسح</div>
                        <div class="status-desc">قم بتوجيه كاميرا الجهاز نحو رمز الـ QR الخاص بتذكرة الضيف للتحقق من الصلاحية.</div>
                    </div>
                </template>

                <!-- SCANNING -->
                <template x-if="status === 'scanning'">
                    <div>
                        <div class="status-icon icon-scanning"></div>
                        <div class="status-title">جاري التحقق...</div>
                        <div class="status-desc">يتم مراجعة قاعدة البيانات</div>
                    </div>
                </template>

                <!-- SUCCESS -->
                <template x-if="status === 'success'">
                    <div style="width: 100%;">
                        <div class="status-icon icon-success">
                            <svg style="width: 40px; height: 40px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div class="status-title" style="color: #10b981;">دخول مصرّح</div>
                        
                        <div class="guest-info-box">
                            <div style="font-size: 0.75rem; color: #6b7280; margin-bottom: 0.5rem;">اسم الضيف</div>
                            <div class="guest-name" x-text="guestName"></div>
                            
                            <div class="guest-companions">
                                <span>المرافقين</span>
                                <span class="badge" x-text="companions"></span>
                            </div>
                        </div>
                        
                        <div style="margin-top: 1.5rem; font-size: 0.85rem; color: #9ca3af;">سيتم استئناف المسح تلقائياً...</div>
                    </div>
                </template>

                <!-- ERROR -->
                <template x-if="status === 'error'">
                    <div style="width: 100%;">
                        <div class="status-icon icon-error">
                            <svg style="width: 40px; height: 40px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </div>
                        <div class="status-title" style="color: #ef4444;">مرفوض</div>
                        <div class="status-desc" style="color: #ef4444; margin-bottom: 1.5rem; font-weight: bold;" x-text="errorMessage"></div>
                        
                        <template x-if="guestName">
                            <div class="guest-info-box" style="margin-bottom: 1.5rem;">
                                <div style="font-size: 0.75rem; color: #6b7280; margin-bottom: 0.5rem;">الضيف</div>
                                <div class="guest-name" x-text="guestName"></div>
                            </div>
                        </template>
                        
                        <button @click="resetScanner" class="btn-toggle" style="background: #ef4444; width: 100%;">
                            مسح تذكرة أخرى
                        </button>
                    </div>
                </template>

            </div>
        </div>
    </div>

    <script>
        function ticketScanner() {
            return {
                html5QrcodeScanner: null,
                isScanning: true,
                status: 'idle', // idle, scanning, success, error
                guestName: '',
                companions: 0,
                errorMessage: '',
                eventId: '{{ $record->id }}',
                
                initScanner() {
                    // Wait for the library to load if it hasn't
                    if (typeof Html5QrcodeScanner === 'undefined') {
                        setTimeout(() => this.initScanner(), 200);
                        return;
                    }
                    
                    // html5-qrcode automatically adds its own UI, so we don't need our own start/stop buttons usually, 
                    // but we will keep them synchronized.
                    this.html5QrcodeScanner = new Html5QrcodeScanner(
                        "reader",
                        { fps: 10, qrbox: {width: 250, height: 250} },
                        /* verbose= */ false
                    );
                    
                    this.startScanner();
                },
                
                startScanner() {
                    this.html5QrcodeScanner.render(
                        (decodedText, decodedResult) => this.onScanSuccess(decodedText), 
                        (error) => this.onScanFailure(error)
                    );
                    this.isScanning = true;
                },
                
                stopScanner() {
                    if (this.html5QrcodeScanner) {
                        try {
                            this.html5QrcodeScanner.clear();
                        } catch (e) {
                            console.error('Failed to clear scanner', e);
                        }
                        this.isScanning = false;
                    }
                },
                
                toggleScanner() {
                    if (this.isScanning) {
                        this.stopScanner();
                    } else {
                        this.startScanner();
                    }
                },
                
                resetScanner() {
                    this.status = 'idle';
                    this.guestName = '';
                    this.companions = 0;
                    this.errorMessage = '';
                    
                    // We only need to restart if it was paused. html5-qrcode continues scanning automatically unless cleared.
                    // But we used clear() or paused it in onScanSuccess.
                    // In html5-qrcode, pause() is available in Html5Qrcode but not directly in Html5QrcodeScanner.
                    // If we called clear(), we need to call render() again.
                    if (!this.isScanning) {
                        this.startScanner();
                    }
                },
                
                onScanSuccess(decodedText) {
                    if (this.status === 'scanning' || this.status === 'success') return; // Prevent multiple scans
                    
                    this.stopScanner(); // Stop camera while processing
                    this.status = 'scanning';
                    
                    // Call Laravel API
                    fetch('/host/scan-ticket', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                        },
                        body: JSON.stringify({
                            qr_data: decodedText,
                            event_id: this.eventId
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.status = 'success';
                            this.guestName = data.guest.name;
                            this.companions = data.guest.companions_count;
                            
                            // Play success sound
                            this.playSound('success');
                            
                            // Auto-resume after 3 seconds
                            setTimeout(() => {
                                this.resetScanner();
                            }, 3000);
                        } else {
                            this.status = 'error';
                            this.errorMessage = data.message;
                            if (data.guest) this.guestName = data.guest;
                            
                            // Play error sound
                            this.playSound('error');
                        }
                    })
                    .catch(err => {
                        this.status = 'error';
                        this.errorMessage = 'حدث خطأ في الاتصال بالخادم';
                        this.playSound('error');
                    });
                },
                
                onScanFailure(error) {
                    // Ignore background scan failures
                },
                
                playSound(type) {
                    try {
                        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                        const oscillator = audioCtx.createOscillator();
                        const gainNode = audioCtx.createGain();
                        
                        oscillator.connect(gainNode);
                        gainNode.connect(audioCtx.destination);
                        
                        if (type === 'success') {
                            oscillator.type = 'sine';
                            oscillator.frequency.setValueAtTime(800, audioCtx.currentTime);
                            oscillator.frequency.exponentialRampToValueAtTime(1200, audioCtx.currentTime + 0.1);
                            gainNode.gain.setValueAtTime(0.5, audioCtx.currentTime);
                            gainNode.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.3);
                            oscillator.start();
                            oscillator.stop(audioCtx.currentTime + 0.3);
                        } else {
                            oscillator.type = 'sawtooth';
                            oscillator.frequency.setValueAtTime(300, audioCtx.currentTime);
                            oscillator.frequency.exponentialRampToValueAtTime(150, audioCtx.currentTime + 0.3);
                            gainNode.gain.setValueAtTime(0.5, audioCtx.currentTime);
                            gainNode.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.3);
                            oscillator.start();
                            oscillator.stop(audioCtx.currentTime + 0.3);
                        }
                    } catch(e) {}
                }
            };
        }
    </script>
    
    <style>
        .animate-pulse-fast {
            animation: pulse-fast 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse-fast {
            0%, 100% { opacity: 1; }
            50% { opacity: .7; }
        }
        
        .animate-scan-laser {
            animation: scan-laser 2.5s ease-in-out infinite alternate;
        }
        @keyframes scan-laser {
            0% { top: 5%; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 95%; opacity: 0; }
        }

        .animate-bounce-short {
            animation: bounce-short 0.5s ease-out 1;
        }
        @keyframes bounce-short {
            0% { transform: scale(0.8) translateY(20px); opacity: 0; }
            50% { transform: scale(1.1) translateY(-5px); opacity: 1; }
            100% { transform: scale(1) translateY(0); }
        }

        .animate-shake {
            animation: shake 0.4s cubic-bezier(.36,.07,.19,.97) both;
        }
        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }

        /* Customize html5-qrcode UI to blend nicely with our dark/luxury theme */
        #reader {
            border: none !important;
            border-radius: 1.4rem;
        }
        #reader__dashboard_section_csr span a,
        #reader a {
            display: none !important;
        }
        #reader__dashboard_section_csr {
            padding: 2rem !important;
        }
        #reader select {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            width: 100%;
            margin-bottom: 1rem;
            outline: none;
            backdrop-filter: blur(10px);
        }
        #reader select option {
            background: #1f2937;
            color: #fff;
        }
        #reader button {
            background: linear-gradient(135deg, rgba(var(--primary-600), 1) 0%, rgba(var(--primary-700), 1) 100%);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(var(--primary-500), 0.2);
        }
        #reader button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(var(--primary-500), 0.3);
        }
        #reader video {
            object-fit: cover !important;
            border-radius: 1.4rem !important;
            width: 100% !important;
            height: 100% !important;
            min-height: 350px !important;
        }
    </style>
</x-filament-panels::page>
