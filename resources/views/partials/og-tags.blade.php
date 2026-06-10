{{--
    Open Graph Meta Tags — Shared across all invitation themes
    Variables available: $event, $guest (optional), $isPublic, $invitation (optional)
--}}
@php
    // Build the page title
    $ogTitle = isset($guest) && $guest
        ? '💌 ' . $event->title . ' — دعوة خاصة لـ ' . $guest->name
        : '💌 ' . $event->title;

    // Build the description
    $ogDate = $event->event_datetime
        ? $event->event_datetime->translatedFormat('l، d F Y — H:i')
        : '';
    $ogLocation = $event->location_name ?? '';
    $ogDescription = collect([$ogDate, $ogLocation])->filter()->implode(' | ');
    if (empty($ogDescription)) {
        $ogDescription = 'أنت مدعو لحضور هذه المناسبة الخاصة. تفضل بالضغط لعرض الدعوة كاملة.';
    }

    // Find the best image from content_data
    $ogImage = null;
    if (is_array($event->content_data)) {
        foreach ($event->content_data as $value) {
            if (is_string($value) && !empty($value) && !str_starts_with($value, '#')) {
                // Looks like a URL or storage path
                if (str_starts_with($value, 'http')) {
                    $ogImage = $value;
                    break;
                } elseif (strlen($value) > 5) {
                    // Likely a storage path
                    $ogImage = asset('storage/' . $value);
                    break;
                }
            }
        }
    }

    // Fallback to a beautiful default invitation image
    if (!$ogImage) {
        $ogImage = asset('images/og-default.jpg');
    }

    $ogUrl = request()->url();
    $siteName = config('app.name', 'Evently');
@endphp

{{-- Primary Meta --}}
<meta name="description" content="{{ $ogDescription }}">

{{-- Open Graph (Facebook, WhatsApp, LinkedIn) --}}
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:url" content="{{ $ogUrl }}">
<meta property="og:title" content="{{ $ogTitle }}">
<meta property="og:description" content="{{ $ogDescription }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="{{ $ogTitle }}">
<meta property="og:locale" content="ar_DZ">

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $ogTitle }}">
<meta name="twitter:description" content="{{ $ogDescription }}">
<meta name="twitter:image" content="{{ $ogImage }}">
