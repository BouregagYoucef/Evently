<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تذكرة الدخول - {{ $invitation->event->title }}</title>
    <style>
        body { margin: 0; padding: 0; background-color: #f4f4f4; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
        .wrapper { width: 100%; padding: 40px 10px; background-color: #f4f4f4; text-align: center; }
        .card { 
            background-color: #FFFAF0; /* Ivory/Cream */
            margin: 0 auto; 
            max-width: 600px; 
            border: 2px solid #C19A6B; /* Gold border */
            border-radius: 12px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .inner-border {
            border: 1px dashed #C19A6B;
            margin: 15px;
            padding: 40px 30px;
            border-radius: 8px;
        }
        .header h1 { margin: 0; color: #C19A6B; font-size: 28px; font-weight: normal; letter-spacing: 1px; }
        .divider { height: 1px; background: linear-gradient(to right, transparent, #C19A6B, transparent); margin: 25px 0; }
        .greeting { font-size: 22px; color: #333333; margin-bottom: 20px; }
        .message { font-size: 16px; color: #555555; line-height: 1.8; margin-bottom: 30px; }
        .details-box { text-align: center; margin-bottom: 30px; padding: 20px; border: 1px solid rgba(193, 154, 107, 0.3); border-radius: 8px; background-color: rgba(193, 154, 107, 0.05); }
        .detail-row { margin-bottom: 10px; }
        .detail-label { color: #C19A6B; font-weight: bold; font-size: 14px; }
        .detail-value { color: #333333; font-size: 16px; font-weight: bold; }
        .btn-container { text-align: center; margin: 30px 0; }
        .btn { 
            display: inline-block; 
            background-color: #C19A6B; 
            color: #ffffff !important; 
            text-decoration: none; 
            padding: 16px 35px; 
            font-weight: bold; 
            font-size: 16px; 
            border-radius: 50px; 
            box-shadow: 0 4px 15px rgba(193, 154, 107, 0.4);
        }
        .footer { text-align: center; color: #888888; font-size: 12px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="inner-border">
                <div class="header">
                    <h1>تأكيد الحضور والتذكرة</h1>
                </div>
                <div class="divider"></div>
                
                <p class="greeting">أهلاً بك، <strong>{{ $invitation->guest->name }}</strong></p>
                <p class="message">
                    شكراً لتأكيد حضورك.<br>
                    تجدون مرفقاً مع هذه الرسالة تذكرة الدخول الرسمية (PDF).<br>يرجى تحميلها وإبراز رمز الاستجابة السريعة (QR Code) عند بوابة الدخول لتسهيل عملية وصولكم.
                </p>
                
                <div class="details-box">
                    <div class="detail-row">
                        <span class="detail-label">المناسبة:</span><br>
                        <span class="detail-value">{{ $invitation->event->title }}</span>
                    </div>
                    <div class="detail-row" style="margin-top: 15px;">
                        <span class="detail-label">التاريخ والوقت:</span><br>
                        <span class="detail-value">{{ \Carbon\Carbon::parse($invitation->event->event_datetime)->translatedFormat('l, d F Y - h:i A') }}</span>
                    </div>
                    <div class="detail-row" style="margin-top: 15px;">
                        <span class="detail-label">المكان:</span><br>
                        <span class="detail-value">{{ $invitation->event->location_name }}</span>
                    </div>
                </div>

                <div class="btn-container">
                    <a href="{{ url('/i/' . $invitation->uuid . '/ticket') }}" class="btn">تحميل التذكرة المرفقة</a>
                </div>
                
            </div>
        </div>
        <div class="footer">
            <p>تم إرسال هذه الدعوة عبر منصة Evently. دعوتك الفاخرة، بأسهل الطرق.</p>
        </div>
    </div>
</body>
</html>
