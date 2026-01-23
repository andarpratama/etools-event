<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Portfolio - Dashboard</title>

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
                        <h1 class="h3 mb-0 text-gray-800">Edit Portfolio</h1>
                        <a href="{{ route('admin.portfolios.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
                        </a>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Portfolio Information</h6>
                        </div>
                        <div class="card-body">
                            <div id="alert-container"></div>
                            <form id="portfolio-form">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title">Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $portfolio->title) }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="category">Category <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="category" name="category" value="{{ old('category', $portfolio->category) }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="image_url">Image URL <span class="text-danger">*</span></label>
                                            <input type="url" class="form-control" id="image_url" name="image_url" value="{{ old('image_url', $portfolio->image_url) }}" required>
                                            <small class="form-text text-muted">Enter full image URL</small>
                                            <div id="image-preview" class="mt-2">
                                                <img src="{{ $portfolio->image_url }}" style="max-width: 300px; max-height: 200px;" class="img-thumbnail">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sort_order">Sort Order</label>
                                            <input type="number" min="0" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $portfolio->sort_order) }}">
                                            <small class="form-text text-muted">Lower numbers appear first</small>
                                        </div>

                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ old('is_active', $portfolio->is_active) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">
                                                    Active
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" id="submit-btn" class="btn btn-primary">
                                            <span id="submit-text">Update Portfolio</span>
                                            <span id="submit-spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                        </button>
                                        <a href="{{ route('admin.portfolios.index') }}" class="btn btn-secondary">Cancel</a>
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
        $(document).ready(function() {
            $('#image_url').on('input', function() {
                var url = $(this).val();
                if (url) {
                    $('#image-preview').html('<img src="' + url + '" style="max-width: 300px; max-height: 200px;" class="img-thumbnail" onerror="this.parentElement.innerHTML=\'\'">');
                } else {
                    $('#image-preview').html('');
                }
            });

            function showAlert(message, type) {
                var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
                    message +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span></button></div>';
                $('#alert-container').html(alertHtml);
                setTimeout(function() {
                    $('.alert').fadeOut();
                }, 5000);
            }

            $('#portfolio-form').on('submit', async function(e) {
                e.preventDefault();

                $('#submit-btn').prop('disabled', true);
                $('#submit-text').text('Updating...');
                $('#submit-spinner').removeClass('d-none');

                var formData = new FormData();
                formData.append('title', $('#title').val());
                formData.append('category', $('#category').val());
                formData.append('image_url', $('#image_url').val());
                formData.append('sort_order', $('#sort_order').val() || 0);
                formData.append('is_active', $('#is_active').is(':checked') ? 1 : 0);
                formData.append('_method', 'PUT');

                try {
                    const response = await fetch('{{ route("admin.portfolios.update", $portfolio->id) }}', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || $('input[name="_token"]').val()
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        showAlert('Portfolio updated successfully!', 'success');
                        setTimeout(function() {
                            window.location.href = '{{ route("admin.portfolios.index") }}';
                        }, 1500);
                    } else {
                        if (data.errors) {
                            showAlert(data.message || 'Please fix the errors below.', 'error');
                        } else {
                            showAlert(data.message || 'An error occurred while updating the portfolio.', 'error');
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showAlert('An error occurred. Please try again.', 'error');
                } finally {
                    $('#submit-btn').prop('disabled', false);
                    $('#submit-text').text('Update Portfolio');
                    $('#submit-spinner').addClass('d-none');
                }
            });
        });
    </script>
</body>

</html>

