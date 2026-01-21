<footer class="bg-dark text-light py-5">
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 align-items-center">

            <!-- Company Info -->
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <h5 class="mb-2">{{ $settings['website_name'] ?? 'ETools Event' }}</h5>
                @if($settings['tagline'] ?? null)
                    <p class="small text-muted mb-2">
                        {{ $settings['tagline'] }}
                    </p>
                @endif
                @if($settings['address'] ?? null)
                    <p class="small text-muted mb-0">
                        <i class="bi-geo-alt me-1"></i>{{ $settings['address'] }}
                    </p>
                @endif
            </div>

            <!-- Contact Info -->
            <div class="col-md-6 text-center text-md-end">
                @if($settings['contact'] ?? null)
                    @php
                        $contact = $settings['contact'];
                        $whatsappNumber = preg_replace('/[^0-9]/', '', $contact);
                        $whatsappLink = 'https://wa.me/' . $whatsappNumber;
                    @endphp
                    <p class="small mb-1">
                        <i class="bi-whatsapp me-1"></i>
                        <a href="{{ $whatsappLink }}"
                           target="_blank"
                           class="text-decoration-none text-light">
                            {{ $settings['contact'] }}
                        </a>
                    </p>
                @endif
                <p class="small text-muted mb-0">
                    © {{ date('Y') }} {{ $settings['website_name'] ?? 'ETools Event' }}. All rights reserved.
                </p>
            </div>

        </div>
    </div>
</footer>
