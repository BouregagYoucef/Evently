<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth">
<head>
    <link rel="icon" type="image/png" href="/images/logo.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Evently Invitation')</title>
    
    <!-- Meta Descriptions for SEO -->
    <meta name="description" content="@yield('meta_description', 'You are invited to a special event!')">

    <!-- Fonts (Example: Outfit / Cairo) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
        .font-outfit {
            font-family: 'Outfit', sans-serif;
        }
        /* Hide scrollbar for cinematic feel */
        ::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>
<body class="antialiased bg-black text-white overflow-x-hidden" x-data="themeEngine()">
    
    <!-- Dynamic JSON data injected from Backend -->
    <script id="content-data" type="application/json">
        @yield('json-data')
    </script>

    <!-- Content Slot -->
    <main id="lenis-container">
        @yield('content')
    </main>

</body>
</html>
