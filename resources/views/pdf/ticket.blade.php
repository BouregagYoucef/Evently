<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>VIP Ticket - {{ $event->title }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Aref+Ruqaa:wght@400;700&family=Amiri:ital,wght@0,400;0,700;1,400;1,700&display=swap');
        
        @page { margin: 0px; size: A4 landscape; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            color: #1a1a1a;
        }
        .bg-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -10;
        }
        
        /* CONTENT ZONE: occupies middle 60% */
        .content {
            position: absolute;
            top: 24%;
            left: 20%;
            width: 60%;
            height: 60%;
            z-index: 10;
            text-align: center;
        }
        
        table.upper-group {
            margin: 0 auto;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        
        td {
            vertical-align: middle;
        }

        /* SECTION 1: COUPLE NAMES */
        .names-cell {
            text-align: center;
            padding-right: 35px;
            border-right: 1px solid #C19A6B; /* Vertical gold divider */
        }
        
        .names {
            font-family: 'Aref Ruqaa', 'Amiri', 'DejaVu Sans', sans-serif;
            font-size: 60px;
            color: #1a1a1a;
            line-height: 1.1;
        }

        .ampersand {
            font-size: 40px;
            font-weight: normal;
            margin: 10px 0;
            color: #C19A6B;
        }
        
        /* SECTION 2: EVENT DATE BLOCK */
        .date-cell {
            text-align: center;
            padding-left: 35px;
        }

        .date-day {
            font-size: 60px;
            font-weight: bold;
            color: #1a1a1a;
            margin: 0;
            line-height: 1;
        }
        .date-month {
            font-size: 18px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #1a1a1a;
            margin-top: 5px;
            margin-bottom: 5px;
        }
        .date-year {
            font-size: 24px;
            color: #1a1a1a;
            margin-bottom: 10px;
        }
        .time-day {
            font-size: 18px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #333;
            margin-bottom: 2px;
        }

        /* SECTION 3: MAIN TITLE */
        .main-title {
            font-size: 34px;
            letter-spacing: 6px;
            color: #4a4a4a;
            margin-top: 10px;
            text-align: center;
            text-transform: uppercase;
            font-weight: normal;
        }

        /* SECTION 4: EVENT DESCRIPTION */
        .description {
            font-size: 20px;
            color: #666;
            text-align: center;
            margin-top: 15px;
            line-height: 1.6;
            max-width: 70%;
            margin-left: auto;
            margin-right: auto;
        }

        /* SECTION 5: OPTIONAL FOOTER */
        .footer {
            margin-top: 30px;
            font-size: 20px;
            color: #1a1a1a;
            text-align: center;
            font-weight: bold;
        }
        
        /* QR Code Absolute */
        .qr-wrapper {
            position: absolute;
            bottom: 30px;
            right: 40px;
            text-align: center;
        }
        .qr-code {
            width: 80px;
            height: 80px;
        }
        .ticket-id {
            color: #888;
            font-size: 10px;
            letter-spacing: 1px;
            margin-top: 2px;
        }
    </style>
</head>
<body>

@if(!empty($bgBase64))
<img src="data:image/jpeg;base64, {!! $bgBase64 !!}" class="bg-image">
@endif

<div class="content">
    
    <!-- UPPER GROUP: Names and Date -->
    <table class="upper-group" dir="ltr">
        <tr>
            <!-- Names on Left (visually left in LTR) -->
            <td class="names-cell">
                @php
                    $titleStr = $event->title;
                    // Split the title by &, و, or and to stack names
                    $names = preg_split('/\s+(&|و|and)\s+/i', $titleStr, -1, PREG_SPLIT_DELIM_CAPTURE);
                @endphp
                
                <div class="names" dir="rtl">
                    @if(count($names) >= 3)
                        <div>{{ $arabic->utf8Glyphs(trim($names[0])) }}</div>
                        <div class="ampersand">&amp;</div>
                        <div>{{ $arabic->utf8Glyphs(trim($names[2])) }}</div>
                    @else
                        <div>{{ $arabic->utf8Glyphs($titleStr) }}</div>
                    @endif
                </div>
            </td>
            
            <!-- Date Block on Right -->
            <td class="date-cell">
                @php
                    $dt = \Carbon\Carbon::parse($event->event_datetime);
                @endphp
                <div class="date-day">
                    {{ $dt->format('d') }}
                </div>
                <div class="date-month">
                    {{ $dt->translatedFormat('F') }}
                </div>
                <div class="date-year">
                    {{ $dt->format('Y') }}
                </div>
                <div class="time-day">
                    {{ $dt->format('H:i') }}<br>
                    {{ $arabic->utf8Glyphs('يوم ' . $dt->translatedFormat('l')) }}
                </div>
            </td>
        </tr>
    </table>
    
    <!-- MAIN TITLE -->
    <div class="main-title">
        A MEMORABLE DAY
    </div>
    
    <!-- EVENT DESCRIPTION -->
    <div class="description" dir="rtl">
        <div>{{ $arabic->utf8Glyphs('يسعدنا ويشرفنا حضوركم ومشاركتكم فرحتنا في هذا اليوم المميز.') }}</div>
        <div style="margin-top: 15px;">{{ $arabic->utf8Glyphs('دعوة خاصة لـ:') }}</div>
        <div style="color:#1a1a1a; font-size:24px; font-weight: bold; margin-top: 5px;">
            {{ $arabic->utf8Glyphs($guest->name) }}
            @if($guest->companions_count > 0)
                <span style="color:#666; font-size: 16px; font-weight: normal;">(+ {{ $guest->companions_count }} {{ $arabic->utf8Glyphs('مرافق') }})</span>
            @endif
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer" dir="rtl">
        {{ $arabic->utf8Glyphs($event->location_name) }}
    </div>

</div>

<!-- QR Code placed independently -->
<div class="qr-wrapper">
    <img src="data:image/svg+xml;base64, {!! $qrCodeBase64 !!}" class="qr-code" alt="QR Code">
    <div class="ticket-id">
        ID: {{ strtoupper(substr($invitation->uuid, 0, 8)) }}
    </div>
</div>

</body>
</html>
