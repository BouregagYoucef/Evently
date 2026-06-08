// Bootstrap removed
import Alpine from 'alpinejs';
import gsap from 'gsap';
import Lenis from '@studio-freight/lenis';
import confetti from 'canvas-confetti';
import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Global instances for the themes
window.Alpine = Alpine;
window.gsap = gsap;
window.confetti = confetti;

/**
 * Headless Theme Engine using Alpine.js
 */
document.addEventListener('alpine:init', () => {
    Alpine.data('themeEngine', () => ({
        contentData: {},
        isLoaded: false,
        
        init() {
            // Read injected JSON data from Blade
            const dataScript = document.getElementById('content-data');
            if (dataScript) {
                try {
                    this.contentData = JSON.parse(dataScript.textContent);
                } catch (e) {
                    console.error("Failed to parse content data", e);
                }
            }
            
            // Setup Smooth Scroll
            const lenis = new Lenis({
                duration: 1.2,
                easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
                direction: 'vertical',
                gestureDirection: 'vertical',
                smooth: true,
                mouseMultiplier: 1,
            });

            function raf(time) {
                lenis.raf(time);
                requestAnimationFrame(raf);
            }
            requestAnimationFrame(raf);

            // Intro Animations
            this.$nextTick(() => {
                this.isLoaded = true;
                this.animateIntro();
            });
        },

        animateIntro() {
            gsap.fromTo('.cinematic-fade-in', 
                { opacity: 0, y: 30 }, 
                { opacity: 1, y: 0, duration: 1.5, stagger: 0.2, ease: "power3.out" }
            );
        },

        triggerConfetti() {
            confetti({
                particleCount: 150,
                spread: 70,
                origin: { y: 0.6 },
                colors: ['#C19A6B', '#FFFFFF', '#FFD700']
            });
        },

        isSubmitting: false,
        rsvpStatus: null, // 'CONFIRMED' or 'DECLINED'

        async submitRsvp(uuid, isAttending) {
            if (this.isSubmitting) return;
            this.isSubmitting = true;

            try {
                const response = await window.axios.post(`/i/${uuid}/rsvp`, {
                    is_attending: isAttending
                });

                if (response.data.success) {
                    this.rsvpStatus = response.data.status;
                    if (isAttending) {
                        this.triggerConfetti();
                    }
                }
            } catch (error) {
                console.error("RSVP Submission Error:", error);
                alert("An error occurred while submitting your RSVP. Please try again.");
            } finally {
                this.isSubmitting = false;
            }
        }
    }));
});

Alpine.start();
