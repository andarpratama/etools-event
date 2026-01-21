<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>{{ $settings['website_name'] ?? 'ETools Event' }}@if($settings['tagline'] ?? null) - {{ $settings['tagline'] }}@endif</title>
        <!-- Favicon-->
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
