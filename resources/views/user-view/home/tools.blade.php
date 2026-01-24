<section class="page-section bg-light" id="tools">
    <div class="container px-4 px-lg-5">
        <h2 class="text-center mt-0">Daftar Alat Sewa</h2>
        <hr class="divider" />
        <p class="text-center text-muted mb-5">
            Pilih perlengkapan event sesuai kebutuhan Anda. Harga sewa per hari.
        </p>

        <div class="row gx-4 gx-lg-5" id="tools-container" data-whatsapp-number="@if($settings['contact'] ?? null){{ preg_replace('/[^0-9]/', '', $settings['contact']) }}@endif">
            <div class="col-12 text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</section>
