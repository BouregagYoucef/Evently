# Evently - Laravel Workspace Rules

This file defines the strict architectural, developmental, and aesthetic constraints for the Evently SaaS platform in its **Laravel + MySQL** environment. Always refer to these rules before generating code.

## 1. Core Philosophy & Workflow
- **Architecture First:** Never start coding features directly. Always design the Models, Migrations, Services, Policies, Controllers, and Blade Views before writing logic.
- **SLC Methodology:** Build features that are Simple, Lovable, and Complete.
- **Scale-Ready:** Write code assuming the platform will handle 10,000 users, 100,000 invitations, and heavy concurrent RSVP traffic.

## 2. Laravel Clean Architecture
- **Service Pattern is Mandatory:** Business logic MUST NEVER live inside Controllers or Blade templates.
  - *Bad:* Controller interacting directly with the Database for complex logic.
  - *Good:* Controller calls `app/Services/EventService.php` or `app/Services/InvitationService.php`.
- **Form Requests:** All input validation MUST be done using Laravel `FormRequest` classes. Never validate directly inside the Controller.
- **No Duplicate Logic:** Extract shared logic into Services, Traits, or Action classes.

## 3. Database & Eloquent Rules
- **No Raw SQL:** Use Laravel Eloquent ORM and Query Builder.
- **UUIDs for Security:** Never expose numeric auto-incrementing IDs in public URLs. Always use UUIDs for guest invitation links (`/invite/{uuid}`).
- **Timestamps:** Every table must have `created_at` and `updated_at` timestamps (except pure pivot or log tables).
- **Indexing:** Always add indexes to Foreign Keys, Status fields (e.g., `status` in invitations), and frequently searched columns to optimize analytics.
- **Eager Loading:** Prevent N+1 queries strictly. Always use `with()` or `load()` when fetching related models, especially for the Analytics Dashboard.

## 4. Headless Templates & Frontend Constraints
- **Tech Stack:** Tailwind CSS, Alpine.js, GSAP, Lenis (Smooth Scroll), and Vanilla Canvas API. Do NOT introduce React, Vue, or Angular.
- **Aesthetic Excellence:** Designs must feel premium, luxury, and cinematic (Awwwards-level). Use subtle micro-animations, glassmorphism, elegant typography, and carefully chosen color palettes.
- **Data Injection:** Templates are headless. Inject dynamic data into Blade views exclusively via JSON:
  ```html
  <script id="content-data" type="application/json">
      @json($event->content_data)
  </script>
  ```
- **Mobile-First:** All cinematic effects and canvas particle engines must be perfectly responsive and optimized for mobile screens (320px+).

## 5. Background Jobs & Performance
- **Queue Everything Heavy:** Never perform heavy operations synchronously in the HTTP request.
- Use Laravel Jobs (`app/Jobs`) and Queues for:
  - Sending Emails (Batched sending).
  - Generating PDFs (using DomPDF/Browsershot).
  - Generating QR Codes.
  - Importing large CSV files for guest lists.

## 6. Security & Permissions
- **Resource Ownership:** Always verify that the authenticated user owns the resource before modifying it using Laravel Policies/Gates.
  - *Example:* `$user->can('update', $event)`
- **Data Protection:** Never expose internal database structures, secret keys, or raw exception traces to the frontend.
- **Protection Measures:** Rely on Laravel's built-in CSRF protection, escape output in Blade (using `{{ }}`), and prevent Mass Assignment vulnerabilities by properly defining `$fillable` or `$guarded` in Models.

## 7. Invitations State Machine
- Strict statuses for invitations: `PENDING`, `OPENED`, `CONFIRMED`, `DECLINED`, `CHECKED_IN`. Do not invent new states.
- **Opened Event:** The first time a guest visits a private link, set status to `OPENED` and record `opened_at`.
- **RSVP Event:** Confirming attendance must trigger background Jobs to generate the PDF ticket and QR Code.

## 8. Logging & Auditing
- Maintain an Audit Log for critical host actions (e.g., Creating an event, importing guests, upgrading quotas, deleting resources).
