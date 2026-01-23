<nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="#page-top">
            @if($settings['logo_light'] ?? null)
                <img src="{{ $settings['logo_light'] }}" alt="" class="navbar-logo navbar-logo-light" style="max-height: 40px; margin-right: 10px;" width="40" height="40" onerror="this.style.display='none';">
            @endif
            @if($settings['logo_dark'] ?? null)
                <img src="{{ $settings['logo_dark'] }}" alt="" class="navbar-logo navbar-logo-dark" style="max-height: 40px; margin-right: 10px; display: none;" width="40" height="40" onerror="this.style.display='none';">
            @endif
            <span class="navbar-brand-text">{{ $settings['website_name'] ?? '' }}</span>
        </a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigasi">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ms-auto my-2 my-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="#about">Tentang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#services">Layanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#portfolio">Portofolio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tools">Alat</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">Kontak</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
