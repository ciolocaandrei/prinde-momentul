<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Prinde Momentul') }}</title>

        <!-- SEO Meta Tags -->
        <meta name="description" content="Prinde Momentul - Platforma pentru colectarea fotografiilor de la evenimente. Nunta, botez, majorat - toate fotografiile intr-un singur loc." inertia>
        <meta name="keywords" content="fotografii nunta, fotografii botez, fotografii eveniment, QR code nunta, galerie foto nunta, upload fotografii invitati" inertia>
        <meta name="author" content="Prinde Momentul">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ url()->current() }}" inertia>

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website" inertia>
        <meta property="og:url" content="{{ url()->current() }}" inertia>
        <meta property="og:title" content="{{ config('app.name', 'Prinde Momentul') }}" inertia>
        <meta property="og:description" content="Platforma pentru colectarea fotografiilor de la evenimente. Nunta, botez, majorat - toate fotografiile intr-un singur loc." inertia>
        <meta property="og:image" content="{{ asset('images/og-image.jpg') }}" inertia>
        <meta property="og:locale" content="ro_RO">
        <meta property="og:site_name" content="Prinde Momentul">

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image" inertia>
        <meta name="twitter:url" content="{{ url()->current() }}" inertia>
        <meta name="twitter:title" content="{{ config('app.name', 'Prinde Momentul') }}" inertia>
        <meta name="twitter:description" content="Platforma pentru colectarea fotografiilor de la evenimente. Nunta, botez, majorat." inertia>
        <meta name="twitter:image" content="{{ asset('images/og-image.jpg') }}" inertia>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

        <!-- Theme Color -->
        <meta name="theme-color" content="#7c3aed">
        <meta name="msapplication-TileColor" content="#7c3aed">

        <!-- TikTok Pixel Code Start -->
        <script>
        !function (w, d, t) {
          w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie","holdConsent","revokeConsent","grantConsent"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(
        var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var r="https://analytics.tiktok.com/i18n/pixel/events.js",o=n&&n.partner;ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=r,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};n=document.createElement("script")
        ;n.type="text/javascript",n.async=!0,n.src=r+"?sdkid="+e+"&lib="+t;e=document.getElementsByTagName("script")[0];e.parentNode.insertBefore(n,e)};

          ttq.load('D5OT4LRC77UFLMP0DL60');
          ttq.page();
        }(window, document, 'ttq');
        </script>
        <!-- TikTok Pixel Code End -->

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Structured Data -->
        <script type="application/ld+json">
        @php
        echo json_encode([
            "@context" => "https://schema.org",
            "@type" => "Organization",
            "name" => "Prinde Momentul",
            "url" => "https://prindemomentul.ro",
            "logo" => asset('images/logo.png'),
            "description" => "Platforma pentru colectarea fotografiilor de la evenimente - nunta, botez, majorat",
            "address" => [
                "@type" => "PostalAddress",
                "addressCountry" => "RO"
            ],
            "contactPoint" => [
                "@type" => "ContactPoint",
                "contactType" => "customer service"
            ],
            "sameAs" => []
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        @endphp
        </script>

        @if(isset($page['component']) && $page['component'] === 'Welcome')
        <script type="application/ld+json">
        @php
        echo json_encode([
            "@context" => "https://schema.org",
            "@type" => "Service",
            "name" => "Serviciu Colectare Fotografii Evenimente",
            "provider" => [
                "@type" => "Organization",
                "name" => "Prinde Momentul"
            ],
            "description" => "Colectam fotografiile de la nunta, botez, majorat intr-o galerie privata accesibila prin QR code",
            "areaServed" => "Romania",
            "offers" => [
                [
                    "@type" => "Offer",
                    "name" => "Pachet Standard Eveniment",
                    "price" => "299.99",
                    "priceCurrency" => "RON",
                    "description" => "Include link unic, QR code digital, galerie privata, descarcare nelimitata"
                ],
                [
                    "@type" => "Offer",
                    "name" => "Cartonas QR Imprimat",
                    "price" => "1.99",
                    "priceCurrency" => "RON",
                    "description" => "Cartonas fizic cu QR code personalizat"
                ],
                [
                    "@type" => "Offer",
                    "name" => "Fotografie Imprimata",
                    "price" => "1.49",
                    "priceCurrency" => "RON",
                    "description" => "Printare profesionala fotografii"
                ]
            ]
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        @endphp
        </script>
        @endif

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
