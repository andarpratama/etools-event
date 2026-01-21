<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Settings - Dashboard</title>

    <link href="{{ asset('admin-view/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset('admin-view/css/sb-admin-2.min.css') }}" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        @include('admin-view.partials.sidebar')

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('admin-view.partials.topbar')

                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Website Settings</h1>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">General Settings</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <!-- Left Column -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Logo Light (for dark backgrounds)</label>
                                            <div class="mb-2">
                                                @if($settings['logo_light'])
                                                    <img src="{{ $settings['logo_light'] }}" alt="Logo Light" style="max-height: 100px; max-width: 200px;" class="img-thumbnail" id="logo-light-preview">
                                                @else
                                                    <div class="border p-3 text-center bg-dark text-white" style="max-height: 100px; max-width: 200px;" id="logo-light-preview-placeholder">
                                                        <small>No light logo</small>
                                                    </div>
                                                @endif
                                            </div>
                                            <input type="file" class="form-control-file" id="logo_light" name="logo_light" accept="image/*" onchange="previewLogo(this, 'light')">
                                            <small class="form-text text-muted">Upload light logo for dark backgrounds (max 2MB, formats: jpeg, png, jpg, gif, webp)</small>
                                            @error('logo_light')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Logo Dark (for light backgrounds)</label>
                                            <div class="mb-2">
                                                @if($settings['logo_dark'])
                                                    <img src="{{ $settings['logo_dark'] }}" alt="Logo Dark" style="max-height: 100px; max-width: 200px;" class="img-thumbnail" id="logo-dark-preview">
                                                @else
                                                    <div class="border p-3 text-center bg-light" style="max-height: 100px; max-width: 200px;" id="logo-dark-preview-placeholder">
                                                        <small class="text-muted">No dark logo</small>
                                                    </div>
                                                @endif
                                            </div>
                                            <input type="file" class="form-control-file" id="logo_dark" name="logo_dark" accept="image/*" onchange="previewLogo(this, 'dark')">
                                            <small class="form-text text-muted">Upload dark logo for light backgrounds (max 2MB, formats: jpeg, png, jpg, gif, webp)</small>
                                            @error('logo_dark')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="website_name">Website Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('website_name') is-invalid @enderror" id="website_name" name="website_name" value="{{ old('website_name', $settings['website_name']) }}" required>
                                            @error('website_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="tagline">Tagline</label>
                                            <input type="text" class="form-control @error('tagline') is-invalid @enderror" id="tagline" name="tagline" value="{{ old('tagline', $settings['tagline']) }}" placeholder="Website tagline or slogan">
                                            @error('tagline')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" placeholder="Company/Office address">{{ old('address', $settings['address']) }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="contact">Contact</label>
                                            <input type="text" class="form-control @error('contact') is-invalid @enderror" id="contact" name="contact" value="{{ old('contact', $settings['contact']) }}" placeholder="Phone, Email, or other contact information">
                                            @error('contact')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Update Settings</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @include('admin-view.partials.footer')
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    @include('admin-view.partials.logout-modal')

    <script src="{{ asset('admin-view/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin-view/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin-view/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('admin-view/js/sb-admin-2.min.js') }}"></script>
    <script>
        function previewLogo(input, type) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var preview = document.getElementById('logo-' + type + '-preview');
                    var placeholder = document.getElementById('logo-' + type + '-preview-placeholder');
                    if (placeholder) placeholder.style.display = 'none';
                    if (preview) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    } else {
                        var img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-thumbnail';
                        img.style.maxHeight = '100px';
                        img.style.maxWidth = '200px';
                        img.id = 'logo-' + type + '-preview';
                        var container = input.closest('.form-group').querySelector('.mb-2');
                        container.appendChild(img);
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>

