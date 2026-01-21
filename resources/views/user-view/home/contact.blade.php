<section class="page-section bg-light" id="contact">
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="mt-0">Kontak Kami</h2>
                <hr class="divider" />
                <p class="text-muted mb-5">
                    Hubungi kami langsung untuk pemesanan dan informasi sewa alat event.
                </p>
            </div>
        </div>

        <div class="row gx-4 gx-lg-5 justify-content-center align-items-stretch">

            <!-- Address Section -->
            @if($settings['address'] ?? null)
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="h-100 p-4 border rounded text-center">
                    <i class="bi-geo-alt fs-2 text-primary mb-3"></i>
                    <h5 class="mb-3">Alamat</h5>
                    <p class="text-muted mb-0">
                        {!! nl2br(e($settings['address'])) !!}
                    </p>
                </div>
            </div>
            @endif

            <!-- WhatsApp Section -->
            @if($settings['contact'] ?? null)
            @php
                $contact = $settings['contact'];
                $whatsappNumber = preg_replace('/[^0-9]/', '', $contact);
                $whatsappLink = 'https://wa.me/' . $whatsappNumber;
            @endphp
            <div class="col-md-6">
                <div class="h-100 p-4 border rounded text-center">
                    <i class="bi-whatsapp fs-2 text-success mb-3"></i>
                    <h5 class="mb-1">WhatsApp</h5>
                    <p class="text-muted mb-3">{{ $settings['contact'] }}</p>
                    <a class="btn btn-success btn-lg"
                       href="{{ $whatsappLink }}"
                       target="_blank">
                        Chat via WhatsApp
                    </a>
                </div>
            </div>
            @endif

        </div>
    </div>
</section>
