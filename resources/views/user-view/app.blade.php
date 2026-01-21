<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        
        @php
            $websiteName = $settings['website_name'] ?? 'ETools Event';
            $tagline = $settings['tagline'] ?? 'Solusi Lengkap Sewa Alat Event Profesional';
            $seoDescription = $settings['seo_description'] ?? 'Menyediakan sewa sound system, lighting, tenda, panggung, dan perlengkapan event lainnya untuk acara indoor maupun outdoor. Praktis, lengkap, dan terpercaya.';
            $seoKeywords = $settings['seo_keywords'] ?? 'sewa alat event, sound system, lighting, tenda, panggung, event equipment rental';
            $baseUrl = $settings['base_url'] ?? config('app.url');
            $currentUrl = $baseUrl . request()->getPathInfo();
            $logoUrl = $settings['logo_light'] ?? $settings['logo_dark'] ?? $baseUrl . '/user-view/assets/favicon.ico';
            if ($settings['logo_light'] ?? null) {
                $logoUrl = str_starts_with($settings['logo_light'], 'http') ? $settings['logo_light'] : $baseUrl . '/' . ltrim($settings['logo_light'], '/');
            }
        @endphp
        
        <title>{{ $websiteName }}@if($tagline) - {{ $tagline }}@endif</title>
        <meta name="description" content="{{ $seoDescription }}" />
        <meta name="keywords" content="{{ $seoKeywords }}" />
        <meta name="author" content="{{ $websiteName }}" />
        <meta name="robots" content="index, follow" />
        <link rel="canonical" href="{{ $currentUrl }}" />
        
        <meta property="og:type" content="website" />
        <meta property="og:title" content="{{ $websiteName }}@if($tagline) - {{ $tagline }}@endif" />
        <meta property="og:description" content="{{ $seoDescription }}" />
        <meta property="og:url" content="{{ $currentUrl }}" />
        <meta property="og:image" content="{{ $logoUrl }}" />
        <meta property="og:site_name" content="{{ $websiteName }}" />
        <meta property="og:locale" content="id_ID" />
        
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="{{ $websiteName }}@if($tagline) - {{ $tagline }}@endif" />
        <meta name="twitter:description" content="{{ $seoDescription }}" />
        <meta name="twitter:image" content="{{ $logoUrl }}" />
        
        <script type="application/ld+json">
        @php
            $schema = [
                '@context' => 'https://schema.org',
                '@type' => 'LocalBusiness',
                'name' => $websiteName,
                'description' => $seoDescription,
                'url' => $baseUrl,
            ];
            if ($settings['logo_light'] ?? null) {
                $logoUrl = str_starts_with($settings['logo_light'], 'http') 
                    ? $settings['logo_light'] 
                    : $baseUrl . '/' . ltrim($settings['logo_light'], '/');
                $schema['logo'] = $logoUrl;
            }
            if ($settings['address'] ?? null) {
                $schema['address'] = [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $settings['address']
                ];
            }
            if ($settings['contact'] ?? null) {
                $schema['telephone'] = $settings['contact'];
            }
            $schema['priceRange'] = '$$';
            $schema['areaServed'] = [
                '@type' => 'Country',
                'name' => 'Indonesia'
            ];
        @endphp
        {!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
        </script>
        
        <link rel="icon" type="image/x-icon" href="{{ asset('user-view/assets/favicon.ico') }}" />
        <!-- Bootstrap Icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
        <!-- SimpleLightbox plugin CSS-->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="{{ asset('user-view/css/styles.css') }}" rel="stylesheet">
        <link href="{{ asset('user-view/css/custom.css') }}" rel="stylesheet">
    </head>
    <body id="page-top">
        <!-- Navigation-->
        @include('user-view.partials.navbar')
        @include('user-view.partials.header')
        <!-- Masthead-->
        
        <!-- About-->
        @include('user-view.home.about')
        @include('user-view.home.services')
        @include('user-view.home.portfolio')
        @include('user-view.home.tools')
        @include('user-view.home.contact')
        
        @include('user-view.partials.footer')

        @if($settings['contact'] ?? null)
            @php
                $contact = $settings['contact'];
                $whatsappNumber = preg_replace('/[^0-9]/', '', $contact);
                $whatsappLink = 'https://wa.me/' . $whatsappNumber;
            @endphp
            <a href="{{ $whatsappLink }}"
            class="whatsapp-float"
            target="_blank"
            aria-label="Chat WhatsApp">
                <i class="bi bi-whatsapp"></i>
                <span class="whatsapp-text">Chat CS</span>
            </a>
        @endif



        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- SimpleLightbox plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.js"></script>
        <!-- Core theme JS-->
        <script src="{{ asset('user-view/js/scripts.js') }}"></script>
        <script src="{{ asset('user-view/js/custom.js') }}"></script>
    </body>
</html>
