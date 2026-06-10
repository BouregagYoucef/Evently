@props(['event', 'isPublic' => true, 'invitation' => null, 'guest' => null])

<!-- RSVP Section (Only for Private Invites) -->
@if(!$isPublic && $invitation)
<div class="w-full max-w-3xl mx-auto px-6 py-10" x-data="{ 
    rsvpStatus: '{{ $invitation->status !== 'PENDING' && $invitation->status !== 'OPENED' ? $invitation->status : '' }}',
    isSubmitting: false,
    submitRsvp(uuid, isAttending) {
        this.isSubmitting = true;
        fetch('/i/' + uuid + '/rsvp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ is_attending: isAttending })
        })
        .then(res => res.json())
        .then(data => {
            this.isSubmitting = false;
            if (data.success) {
                this.rsvpStatus = data.status;
            } else {
                alert('حدث خطأ، يرجى المحاولة لاحقاً');
            }
        })
        .catch(() => {
            this.isSubmitting = false;
            alert('حدث خطأ بالشبكة، يرجى التحقق من اتصالك');
        });
    }
}">
    <div class="p-6 md:p-8 rounded-[1rem] text-center relative border border-gray-600/30 bg-black/50 backdrop-blur-md">
        
        <!-- If Not Yet Confirmed or Declined -->
        <template x-if="!rsvpStatus">
            <div>
                <h2 class="text-3xl text-white mb-4">أهلاً بك، {{ $guest->name ?? 'ضيفنا العزيز' }}</h2>
                <p class="text-gray-400 mb-10 text-lg">نرجو تأكيد حضوركم لمشاركتنا هذه الليلة الاستثنائية.</p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button type="button" @click="submitRsvp('{{ $invitation->uuid }}', true)" 
                            class="px-10 py-4 rounded-full bg-white text-black font-bold text-lg hover:bg-gray-200 transition disabled:opacity-50"
                            :disabled="isSubmitting">
                        <span x-show="!isSubmitting">تأكيد الحضور</span>
                        <span x-show="isSubmitting" class="flex items-center justify-center gap-2">
                            جاري التأكيد...
                        </span>
                    </button>
                    
                    <button type="button" @click="submitRsvp('{{ $invitation->uuid }}', false)" 
                            class="px-10 py-4 rounded-full border border-gray-600 text-gray-300 font-bold text-lg hover:bg-gray-800 transition disabled:opacity-50"
                            :disabled="isSubmitting">
                        أعتذر عن الحضور
                    </button>
                </div>
            </div>
        </template>

        <!-- If Confirmed -->
        <template x-if="rsvpStatus === 'CONFIRMED'">
            <div>
                <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-6 text-green-400 text-2xl">✓</div>
                <h2 class="text-3xl text-white mb-2">شكراً لتأكيد حضورك!</h2>
                <p class="text-gray-400 mb-8">لقد تم تجهيز تذكرة الدخول الخاصة بك.</p>
                
                <a :href="'/i/{{ $invitation->uuid }}/ticket'" target="_blank" class="inline-flex items-center gap-2 px-8 py-4 rounded-full border border-white/50 text-white hover:bg-white/10 transition">
                    تحميل تذكرة الدخول (PDF)
                </a>
            </div>
        </template>

        <!-- If Declined -->
        <template x-if="rsvpStatus === 'DECLINED'">
            <div>
                <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-6 text-red-400 text-2xl">✕</div>
                <h2 class="text-3xl text-white mb-2">شكراً لك</h2>
                <p class="text-gray-400 mb-8">نتمنى أن نراك في مناسبات قادمة بإذن الله.</p>
                
                <button type="button" @click="submitRsvp('{{ $invitation->uuid }}', true)" 
                        class="text-gray-300 hover:text-white underline transition disabled:opacity-50"
                        :disabled="isSubmitting">
                    <span x-show="!isSubmitting">تغيير رأيي وتأكيد الحضور</span>
                    <span x-show="isSubmitting">جاري التأكيد...</span>
                </button>
            </div>
        </template>

    </div>
</div>
@endif

<!-- Public Registration Form -->
@if($isPublic)
<div class="w-full max-w-3xl mx-auto px-6 py-10" x-data="{
    isRegistering: false,
    registrationSuccess: false,
    uuid: null,
    form: { name: '', phone: '', email: '', companions_count: 0 },
    errors: {},
    submitRegistration() {
        this.isRegistering = true;
        this.errors = {};
        fetch('{{ route('public.invite.register', $event->slug) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(this.form)
        })
        .then(async response => {
            const data = await response.json();
            if (response.ok && data.success && data.uuid) {
                this.isRegistering = false;
                this.registrationSuccess = true;
                this.uuid = data.uuid;
            } else if (response.status === 422) {
                this.isRegistering = false;
                this.errors = data.errors || {};
            } else {
                this.isRegistering = false;
                alert('حدث خطأ أثناء التسجيل، يرجى المحاولة لاحقاً.');
            }
        })
        .catch(error => {
            this.isRegistering = false;
            alert('حدث خطأ بالشبكة، يرجى التحقق من اتصالك.');
        });
    }
}">
    <div class="p-8 md:p-14 rounded-[1rem] bg-black/50 backdrop-blur-md border border-gray-600/30 text-right">
        
        <template x-if="!registrationSuccess">
            <div>
                <div class="text-center mb-10">
                    <h2 class="text-3xl text-white mb-2">تسجيل الحضور</h2>
                    <p class="text-gray-400 text-lg">يرجى إدخال بياناتكم للحصول على دعوة خاصة.</p>
                </div>

                <form @submit.prevent="submitRegistration" class="space-y-6">
                    <div>
                        <label class="block text-sm text-gray-300 mb-2 font-bold">الاسم الكامل *</label>
                        <input type="text" x-model="form.name" required class="w-full bg-black/40 border border-gray-600/50 rounded-xl px-5 py-4 text-white focus:outline-none focus:border-white transition text-right" placeholder="أدخل اسمك الكريم">
                        <template x-if="errors.name"><p class="text-red-400 text-sm mt-1" x-text="errors.name[0]"></p></template>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm text-gray-300 mb-2 font-bold">رقم الهاتف</label>
                            <input type="tel" x-model="form.phone" dir="ltr" class="w-full bg-black/40 border border-gray-600/50 rounded-xl px-5 py-4 text-white focus:outline-none focus:border-white transition text-right" placeholder="+966...">
                            <template x-if="errors.phone"><p class="text-red-400 text-sm mt-1" x-text="errors.phone[0]"></p></template>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-300 mb-2 font-bold">البريد الإلكتروني</label>
                            <input type="email" x-model="form.email" dir="ltr" class="w-full bg-black/40 border border-gray-600/50 rounded-xl px-5 py-4 text-white focus:outline-none focus:border-white transition text-right" placeholder="email@example.com">
                            <template x-if="errors.email"><p class="text-red-400 text-sm mt-1" x-text="errors.email[0]"></p></template>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-300 mb-2 font-bold">عدد المرافقين</label>
                        <select x-model="form.companions_count" class="w-full bg-black/40 border border-gray-600/50 rounded-xl px-5 py-4 text-white focus:outline-none focus:border-white transition appearance-none text-right">
                            <option value="0" class="bg-gray-900 text-white">بدون مرافق</option>
                            <option value="1" class="bg-gray-900 text-white">مرافق واحد</option>
                            <option value="2" class="bg-gray-900 text-white">مرافقين اثنين</option>
                            <option value="3" class="bg-gray-900 text-white">3 مرافقين</option>
                            <option value="4" class="bg-gray-900 text-white">4 مرافقين</option>
                        </select>
                    </div>

                    <button type="submit" :disabled="isRegistering" class="w-full mt-8 py-4 bg-white text-black font-bold rounded-xl text-lg hover:bg-gray-200 transition disabled:opacity-50">
                        <span x-show="!isRegistering">طلب دعوة خاصة</span>
                        <span x-show="isRegistering">جاري التأكيد...</span>
                    </button>
                </form>
            </div>
        </template>

        <!-- Success State (Download Ticket) -->
        <template x-if="registrationSuccess">
            <div class="text-center py-8">
                <div class="w-20 h-20 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-6 text-green-400 text-4xl">✓</div>
                <h2 class="text-3xl text-white mb-4">تم تأكيد حضورك بنجاح!</h2>
                <p class="text-gray-400 mb-10 text-lg">يسعدنا انضمامك إلينا، تذكرتك جاهزة للتحميل.</p>
                
                <a :href="'/i/' + uuid + '/ticket'" target="_blank" class="inline-flex items-center gap-3 px-10 py-5 rounded-full bg-white text-black font-bold text-xl hover:bg-gray-200 transition shadow-lg shadow-white/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    تحميل تذكرة الدخول (PDF)
                </a>
            </div>
        </template>

    </div>
</div>
@endif
