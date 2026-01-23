@php
    use App\Models\Portfolio;
    $portfolios = Portfolio::where('is_active', true)->orderBy('sort_order')->get();
@endphp

<div id="portfolio">
    <div class="container-fluid p-0">
        <div class="row g-0">
            @forelse($portfolios as $portfolio)
                <div class="col-lg-4 col-sm-6">
                    <a class="portfolio-box"
                       href="{{ $portfolio->image_url }}"
                       title="{{ $portfolio->title }}">
                        <img class="img-fluid"
                             src="{{ $portfolio->image_url }}"
                             alt="{{ $portfolio->title }}"
                             loading="lazy"
                             width="800"
                             height="600">
                        <div class="portfolio-box-caption">
                            <div class="project-category text-white-50">{{ $portfolio->category }}</div>
                            <div class="project-name">{{ $portfolio->title }}</div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No portfolio items available.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
