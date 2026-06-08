# 🚀 TRANSFER_PROJECT_SPEC.md
# Evently - Comprehensive Laravel Migration Blueprint

هذه الوثيقة هي المرجع النهائي والشامل (Source of Truth) لإعادة بناء منصة **Evently** باستخدام **Laravel + MySQL** مع الحفاظ على الواجهات (Tailwind + Alpine + GSAP/Canvas) ومنهجية (SLC - Simple, Lovable, Complete). 

تم تحليل النظام بالكامل وتوثيق أدق التفاصيل لضمان انتقال خالي من الثغرات.

---

## 1. تحليل النظام العام (System Overview & Philosophy)
- **الهدف:** منصة SaaS (البرمجيات كخدمة) فاخرة لإنشاء وإدارة الدعوات الإلكترونية (للزفاف والمناسبات الكبرى) بتجارب بصرية سينمائية عالية الأداء.
- **المنهجية:** Headless UI Architecture (قوالب مفصولة عن البيانات) تعتمد على JSON Schema لتوليد نماذج ديناميكية.
- **التكنولوجيا المستهدفة:** Laravel 11+, MySQL 8+, Redis, Queue Workers (لتوليد الـ PDF وإرسال الإيميلات).

---

## 2. الأدوار والصلاحيات (Roles, Permissions & Account States)

النظام يحتوي على 3 أطراف رئيسية:

### 2.1 Super Admin (المدير العام)
- **التحكمات:**
  - له الصلاحية المطلقة في إدارة كل شيء عبر لوحة تحكم الإدارة (FilamentPHP أو Nova أو قالب جاهز).
  - **نظام التفعيل:** أي `Host` جديد يسجل في المنصة يكون حسابه في حالة `is_approved = false`. لا يمكن للمضيف استخدام حسابه (إلا لتصفح باقات القوالب) حتى يقوم الـ Admin بتفعيل حسابه يدوياً.
  - **إدارة القوالب:** إضافة قوالب جديدة، تعديل `fields_schema` لكل قالب، وتفعيل/تعطيل القوالب.
  - **القيود:** يمكن للآدمن تعيين قيود (Quotas) لكل مضيف: (مثال: الحد الأقصى للأحداث، الحد الأقصى للمدعوين).

### 2.2 Host (صاحب الحدث / العروسين)
- **القيود الافتراضية (Quotas):**
  - **عدد الأحداث:** يمكنه إنشاء حدث واحد نشط (Active Event) في نفس الوقت لكل باقة قياسية. (أحداث إضافية تتطلب ترقية أو موافقة من الأدمن).
  - **عدد الدعوات:** بحد أقصى (مثلاً 500 دعوة للحدث).
  - **صلاحية الحدث (Duration/Expiration):** يظل الحدث قابلاً للتعديل والإرسال حتى يوم المناسبة. بعد تاريخ المناسبة بـ 30 يوماً، يتحول الحدث إلى وضع الأرشيف (`Archived / Read-only`) ولا يمكن إرسال دعوات جديدة منه، ولكن تبقى روابط الدعوات القديمة تعمل كذكرى.
- **الصلاحيات:** 
  - الدخول للوحة التحكم الخاصة بالحدث (Host Dashboard).
  - تعديل تفاصيل الحدث (الوقت، المكان) والمحتوى الديناميكي (الصور، القصة).
  - إدارة قائمة الضيوف (Guest List) واستيراد المدعوين عبر CSV.
  - مراقبة تحليلات الدعوات (Analytics).

### 2.3 Guest (المدعو)
- **القيود:** ليس لديه حساب ولا باسورد. يدخل عبر روابط مشفرة مخصصة أو رابط عام.
- **الصلاحيات:** تصفح الدعوة (Read-only)، إرسال نموذج الـ RSVP (لمرة واحدة أو السماح بتعديل الرد ضمن فترة سماح معينة). الوصول لـ QR Code الخاص بالدخول.

---

## 3. تفاصيل نظام الأحداث والقوالب المعمارية (Events & Templates Engine)

### 3.1 نظام القوالب (Templates System)
- القالب لا يحتوي على كود برمجي (PHP Logic) مدمج مع البيانات، بل يعتمد على `content_data` (كائن JSON).
- **Template Fields Schema:** 
  حقل `fields_schema` في قاعدة البيانات هو JSON Array يحدد للوحة تحكم المضيف (Host) شكل الـ Form الذي سيقوم بتعبئته. 
  مثال لمحتوى Schema:
  ```json
  [
    {"name": "bride_name", "type": "text", "label": "اسم العروس"},
    {"name": "cover_image", "type": "image_url", "label": "صورة الغلاف"},
    {"name": "gallery", "type": "gallery", "label": "معرض الصور"}
  ]
  ```
  *في Laravel:* Controller الـ Host سيقرأ هذا الـ Schema ويولد HTML Form ديناميكي (أو عبر Livewire/Vue).

### 3.2 نظام الحدث (Event System)
- الحدث (Event) يمثل المناسبة الفعلية ويرتبط بـ `template_id`.
- يقوم المضيف بتعبئة النموذج (Form)، ويقوم النظام بحفظ كل البيانات المدخلة في حقل واحد في جدول الأحداث يُسمى `content_data` (كـ JSON Column).
- **العلاقة المشتركة:** 
  عند فتح الدعوة، النظام يعرض الـ Blade View المطابق لـ `theme_identifier` (مثال: `themes/rose_whisper_arabic/index.blade.php`) ويحقن الـ `$event->content_data` داخله كـ JSON Script `<script id="content-data">` ليقوم الـ Frontend بقرائته.

---

## 4. نظام التوزيع والروابط (Distribution & Links)

### 4.1 الرابط المخصص للضيف (Private UUID Link)
- **الهيكلة:** `https://evently.com/i/123e4567-e89b-12d3-a456-426614174000`
- **التفاصيل:** مخصص لضيف معين تم تسجيله من قبل الـ Host.
- **المميزات:** 
  - عند الفتح، يتم عرض رسالة ترحيبية باسم المدعو (Personalized).
  - لا يُطلب منه إدخال اسمه في الـ RSVP لأنه معروف مسبقاً، فقط خيار (سأحضر / أعتذر).
  - يُسجل فوراً في التحليلات أن الضيف الفلاني (Opened the invite).

### 4.2 الرابط العام للمناسبة (Public Event Link)
- **الهيكلة:** `https://evently.com/e/mohamed-sara-wedding`
- **التفاصيل:** يتم نشره في السوشيال ميديا أو الجروبات.
- **المميزات:**
  - عند الفتح، يتصفح المستخدم الدعوة بشكل عام (بدون اسم مخصص).
  - إذا أراد عمل RSVP، سيُطلب منه (إدخال اسمه ورقم هاتفه وبريده).
  - عند التأكيد، يقوم النظام تلقائياً بإنشاء سجل `Guest` جديد وإنشاء `Invitation` جديدة وربطها به، ثم إعطاؤه الـ QR Code.

### 4.3 طرق الإرسال (Delivery Methods)
- **WhatsApp:** يتم من لوحة تحكم المضيف تحديد ضيوف معينين والضغط على "Send via WhatsApp". النظام يولد رابط Click-to-Chat (مثال: `wa.me/PhoneNumber?text=رسالة الدعوة مع رابط الـ UUID`).
- **Email:** يتم عبر خدمة (Queue Worker) في Laravel. يستخدم الـ Mailables لإرسال إيميل أنيق يحتوي على رابط الـ UUID. يمكن ربطه لاحقاً بـ SMTP أو خدمات مثل (Postmark/Resend).

---

## 5. دورة الاستجابة وتوليد الملفات (RSVP & Artifacts Generation)

1. **الرد (RSVP Form):** الضيف يضغط على "تأكيد الحضور".
2. **الخلفية (Backend Job):**
   - يتغير حقل `status` في الـ Invitation من `OPENED` إلى `CONFIRMED`.
   - يتم توليد رمز QR Code (يحمل الـ UUID) ويُحفظ مساره في قاعدة البيانات.
   - يتم إرسال مهمة (Job) في الخلفية لتوليد تذكرة PDF (تحتوي على اسم الضيف والـ QR Code واسم المناسبة) وتُحفظ في Storage.
3. **الواجهة (Frontend):** الـ Canvas يستجيب لحالة الرد (مثلاً بانفجار الورود - Confetti) وتظهر شاشة نجاح العملية مع زر "تحميل بطاقة الدخول PDF".

---

## 6. هيكلة المسارات والصفحات (Pages & Navigation Flow)

### 6.1 Public & Guest Pages (واجهات عامة والضيوف)
- `GET /` : الصفحة الرئيسية التسويقية للمنصة (Landing Page).
- `GET /templates` : معرض القوالب المتاحة للعرض للعامة.
- `GET /i/{uuid}` : مسار الدعوة الخاصة (Private Invite).
- `POST /i/{uuid}/rsvp` : API Endpoint لمعالجة استجابة الحضور.
- `GET /i/{uuid}/ticket` : مسار لتحميل الـ PDF بعد التأكيد.
- `GET /e/{slug}` : مسار الدعوة العامة (Public Invite).

### 6.2 Host Dashboard (لوحة تحكم المضيف - تتطلب Auth)
- `GET /login`, `/register` : تسجيل الدخول. بعد التسجيل يرى صفحة "Pending Approval" إن لم يفعله الآدمن.
- `GET /dashboard` : الرئيسية (إحصائيات، رسوم بيانية لحالات RSVP، عداد تنازلي للحدث).
- `GET /event/setup` : شاشة إعداد الحدث (إنشاء / اختيار القالب / تعبئة الـ Schema).
- `GET /guests` : إدارة المدعوين (CRUD). يحتوي على زر إضافة يدوي، وزر استيراد (CSV Upload).
- `GET /invitations/send` : واجهة تحديد المدعوين وإرسال الروابط (توليد روابط واتساب أو إرسال إيميلات بالجملة).
- `GET /event/settings` : إعدادات الحدث العامة (تغيير الرابط العام، إيقاف الحدث).

### 6.3 Super Admin Panel (لوحة تحكم الإدارة)
- **إدارة المستخدمين:** تفعيل الحسابات الجديدة للـ Hosts (`Approve / Reject`).
- **إدارة الأحداث:** مراقبة شاملة، تغيير الباقات.
- **إدارة القوالب:** إضافة/تعديل/تفعيل القوالب ووضع تعريفات הـ `fields_schema`.

---

## 7. Database Architecture & Relationships (هيكلة الجداول بدقة)

### `users`
- `id` (bigIncrements)
- `name` (string)
- `email` (string, unique)
- `password` (string)
- `role` (enum: 'superadmin', 'host')
- `is_approved` (boolean, default: false) - *مهمة لآلية التفعيل*
- `timestamps`

### `templates`
- `id` (bigIncrements)
- `name` (string) - *ex: Rose Whisper*
- `theme_identifier` (string, unique) - *ex: rose_whisper_arabic*
- `preview_image` (string)
- `fields_schema` (json)
- `is_active` (boolean, default: true)
- `timestamps`

### `events`
- `id` (bigIncrements)
- `host_id` (foreignId -> users.id, cascade)
- `template_id` (foreignId -> templates.id)
- `slug` (string, unique) - *للروابط العامة*
- `title` (string)
- `event_datetime` (datetime)
- `location_name` (string)
- `content_data` (json) - *بيانات القالب الديناميكية*
- `max_guests` (integer, default: 500)
- `status` (enum: 'DRAFT', 'PUBLISHED', 'ARCHIVED')
- `timestamps`

### `guests`
- `id` (bigIncrements)
- `event_id` (foreignId -> events.id, cascade)
- `name` (string)
- `phone` (string, nullable)
- `email` (string, nullable)
- `type` (enum: 'VIP', 'REGULAR') - *اختياري لتخصيص المعاملة*
- `companions_count` (integer, default: 0)
- `timestamps`

### `invitations`
- `id` (bigIncrements)
- `uuid` (uuid, unique) - *معرف الرابط الخاص*
- `event_id` (foreignId -> events.id, cascade)
- `guest_id` (foreignId -> guests.id, cascade)
- `status` (enum: 'PENDING', 'OPENED', 'CONFIRMED', 'DECLINED', 'CHECKED_IN')
- `qr_code_path` (string, nullable)
- `pdf_path` (string, nullable)
- `opened_at` (timestamp, nullable)
- `confirmed_at` (timestamp, nullable)
- `declined_at` (timestamp, nullable)
- `timestamps`

---

## 8. ملاحظات معمارية متقدمة (Advanced Architectural Notes)

### 8.1 معالجة الـ Queues & Jobs
يجب استخدام نظام `Queues` في Laravel بشكل مكثف لعدم تعطيل الـ Host:
- `ProcessCsvImportJob`: عند رفع ملف ضيوف ضخم، يتم تفريغه وإدخال الضيوف في قاعدة البيانات عبر الـ Queue.
- `GenerateInvitationAssetsJob`: توليد PDF والـ QR Code يتم في الخلفية عند تأكيد حضور الضيف لتسريع استجابة الـ AJAX الخاصة بالـ RSVP.
- `SendBatchEmailsJob`: إرسال دعوات عبر الإيميل لأعداد كبيرة يتم في الخلفية على دفعات (Batches).

### 8.2 نظام التحليلات (Analytics Engine)
- يجب عمل Repository أو Service تُسمى `AnalyticsService`.
- يتم استخدام دوال Laravel المتقدمة (Eloquent Aggregates) لعمل لوحة الإحصائيات بأقل عدد من الاستعلامات (Avoid N+1 queries).
  مثال:
  `$stats = Invitation::selectRaw("status, count(*) as count")->where('event_id', $eventId)->groupBy('status')->get();`

### 8.3 نظام التخزين (Storage & Files)
- رفع صور المعرض (Gallery) وصورة الغلاف التي يرفعها הـ Host يتم حفظها في `storage/app/public/events/{event_id}`.
- الـ PDFs والـ QR Codes تحفظ في مسارات مخصصة لتجنب خلط البيانات.

### 8.4 Form Validation (التحقق الأمني)
- يجب استخدام `FormRequests` مخصصة في Laravel لضمان خلو المدخلات من ثغرات XSS، خاصة لأن حقل `content_data` يحتوي على JSON الذي سيُطبع مباشرة في الـ DOM في الـ Frontend عبر `@json()`.

---
## نهاية التوثيق
هذا الملف يعتبر الـ Blueprint الشامل والأساسي (Single Source of Truth).
بإمكانك الاعتماد عليه مباشرة لبدء إنشاء الـ Migrations، Models، Controllers، وتطوير المنصة في بيئة Laravel. 🚀
