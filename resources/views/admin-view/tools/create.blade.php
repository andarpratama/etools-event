<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Tool - Dashboard</title>

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
                        <h1 class="h3 mb-0 text-gray-800">Create New Tool</h1>
                        <a href="{{ route('admin.tools.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
                        </a>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Tool Information</h6>
                        </div>
                        <div class="card-body">
                            <div id="alert-container"></div>
                            <form id="tool-form" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="row">
                                    <!-- Left Column -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="category">Category <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category') }}" required>
                                            @error('category')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="price">Price <span class="text-danger">*</span></label>
                                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required>
                                                    @error('price')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="min_order">Minimum Order <span class="text-danger">*</span></label>
                                                    <input type="number" min="1" class="form-control @error('min_order') is-invalid @enderror" id="min_order" name="min_order" value="{{ old('min_order', 1) }}" required>
                                                    <small class="form-text text-muted">Min quantity</small>
                                                    @error('min_order')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="badge_color">Badge Color <span class="text-danger">*</span></label>
                                            <select class="form-control @error('badge_color') is-invalid @enderror" id="badge_color" name="badge_color" required>
                                                <option value="primary" {{ old('badge_color') == 'primary' ? 'selected' : '' }}>Primary (Blue)</option>
                                                <option value="warning" {{ old('badge_color') == 'warning' ? 'selected' : '' }}>Warning (Yellow)</option>
                                                <option value="success" {{ old('badge_color') == 'success' ? 'selected' : '' }}>Success (Green)</option>
                                                <option value="danger" {{ old('badge_color') == 'danger' ? 'selected' : '' }}>Danger (Red)</option>
                                                <option value="info" {{ old('badge_color') == 'info' ? 'selected' : '' }}>Info (Cyan)</option>
                                                <option value="secondary" {{ old('badge_color') == 'secondary' ? 'selected' : '' }}>Secondary (Gray)</option>
                                            </select>
                                            @error('badge_color')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="image_url">Primary Image URL (Legacy/Backward Compatibility)</label>
                                            <input type="url" class="form-control @error('image_url') is-invalid @enderror" id="image_url" name="image_url" value="{{ old('image_url') }}" placeholder="Optional - for backward compatibility">
                                            @error('image_url')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ old('is_active') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">
                                                    Active
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Media Section - Full Width -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Media (Images/Videos) <span class="text-danger">*</span></label>
                                            <small class="form-text text-muted mb-2 d-block">You can upload files (images/videos) or enter URLs</small>
                                            <div id="images-container">
                                                <div class="mb-3 image-input-group border p-3 rounded">
                                                    <div class="form-group mb-2">
                                                        <label class="small">Media Type</label>
                                                        <select class="form-control media-type-select" name="media_types[]" required>
                                                            <option value="image">Image</option>
                                                            <option value="video">Video</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-2">
                                                        <label class="small">Option 1: Upload File</label>
                                                        <input type="file" class="form-control-file image-file-input" name="image_files[]" accept="image/*,video/*" data-required="true">
                                                    </div>
                                                    <div class="form-group mb-0">
                                                        <label class="small">Option 2: Media URL</label>
                                                        <input type="url" class="form-control image-url-input" name="images[]" placeholder="Or enter image/video URL" data-required="true">
                                                    </div>
                                                    <div class="preview-container mt-2"></div>
                                                    <div class="text-right mt-2">
                                                        <button type="button" class="btn btn-sm btn-danger remove-image" style="display: none;"><i class="fas fa-trash"></i> Remove</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-secondary mt-2" id="add-image"><i class="fas fa-plus"></i> Add Another Media</button>
                                            @error('images.*')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                            @error('image_files.*')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Buttons -->
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" id="submit-btn" class="btn btn-primary">
                                            <span id="submit-text">Create Tool</span>
                                            <span id="submit-spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                        </button>
                                        <a href="{{ route('admin.tools.index') }}" class="btn btn-secondary">Cancel</a>
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
            $('#add-image').click(function() {
                var newInput = '<div class="mb-3 image-input-group border p-3 rounded">' +
                    '<div class="form-group mb-2">' +
                    '<label class="small">Media Type</label>' +
                    '<select class="form-control media-type-select" name="media_types[]" required>' +
                    '<option value="image">Image</option>' +
                    '<option value="video">Video</option>' +
                    '</select>' +
                    '</div>' +
                    '<div class="form-group mb-2">' +
                    '<label class="small">Option 1: Upload File</label>' +
                    '<input type="file" class="form-control-file image-file-input" name="image_files[]" accept="image/*,video/*">' +
                    '</div>' +
                    '<div class="form-group mb-0">' +
                    '<label class="small">Option 2: Media URL</label>' +
                    '<input type="url" class="form-control image-url-input" name="images[]" placeholder="Or enter image/video URL">' +
                    '</div>' +
                    '<div class="preview-container mt-2"></div>' +
                    '<div class="text-right mt-2">' +
                    '<button type="button" class="btn btn-sm btn-danger remove-image"><i class="fas fa-trash"></i> Remove</button>' +
                    '</div></div>';
                $('#images-container').append(newInput);
                updateRemoveButtons();
            });

            $(document).on('click', '.remove-image', function() {
                $(this).closest('.image-input-group').remove();
                updateRemoveButtons();
            });

            $(document).on('change', '.image-file-input', function() {
                var urlInput = $(this).closest('.image-input-group').find('.image-url-input');
                var previewContainer = $(this).closest('.image-input-group').find('.preview-container');
                var file = this.files[0];
                
                if (file) {
                    urlInput.prop('required', false).removeAttr('data-required');
                    
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var isVideo = file.type.startsWith('video/');
                        if (isVideo) {
                            previewContainer.html('<video src="' + e.target.result + '" controls style="max-width: 200px; max-height: 150px;" class="img-thumbnail"></video>');
                        } else {
                            previewContainer.html('<img src="' + e.target.result + '" style="max-width: 200px; max-height: 150px;" class="img-thumbnail">');
                        }
                    };
                    reader.readAsDataURL(file);
                } else {
                    if (urlInput.attr('data-required') === 'true') {
                        urlInput.prop('required', true);
                    }
                    previewContainer.html('');
                }
            });

            $(document).on('input', '.image-url-input', function() {
                var fileInput = $(this).closest('.image-input-group').find('.image-file-input');
                var previewContainer = $(this).closest('.image-input-group').find('.preview-container');
                var url = $(this).val();
                
                if (url) {
                    fileInput.prop('required', false).removeAttr('data-required');
                    
                    var isVideo = /\.(mp4|mov|avi|webm)(\?.*)?$/i.test(url) || url.includes('video');
                    if (isVideo) {
                        previewContainer.html('<video src="' + url + '" controls style="max-width: 200px; max-height: 150px;" class="img-thumbnail"></video>');
                    } else {
                        previewContainer.html('<img src="' + url + '" style="max-width: 200px; max-height: 150px;" class="img-thumbnail" onerror="this.parentElement.innerHTML=\'\'">');
                    }
                } else {
                    if (fileInput.attr('data-required') === 'true') {
                        fileInput.prop('required', true);
                    }
                    previewContainer.html('');
                }
            });

            // Form validation
            function validateForm() {
                var isValid = true;
                $('.image-input-group').each(function() {
                    var fileInput = $(this).find('.image-file-input');
                    var urlInput = $(this).find('.image-url-input');
                    if (!fileInput.val() && !urlInput.val()) {
                        isValid = false;
                        $(this).addClass('border-danger');
                    } else {
                        $(this).removeClass('border-danger');
                    }
                });
                return isValid;
            }

            // Show alert
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

            // Clear validation errors
            function clearErrors() {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                $('.text-danger').remove();
            }

            // Show validation errors
            function showErrors(errors) {
                clearErrors();
                $.each(errors, function(field, messages) {
                    var input = $('[name="' + field + '"]');
                    if (input.length) {
                        input.addClass('is-invalid');
                        var errorHtml = '<div class="invalid-feedback">' + messages[0] + '</div>';
                        input.after(errorHtml);
                    }
                });
            }

            // Form submission with fetch
            $('#tool-form').on('submit', async function(e) {
                e.preventDefault();

                if (!validateForm()) {
                    showAlert('Please provide either a file upload or media URL for each media entry.', 'error');
                    return;
                }

                clearErrors();

                // Disable submit button
                $('#submit-btn').prop('disabled', true);
                $('#submit-text').text('Creating...');
                $('#submit-spinner').removeClass('d-none');

                // Prepare FormData
                var formData = new FormData(this);
                
                // Handle checkbox - if not checked, explicitly set to 0
                if (!$('#is_active').is(':checked')) {
                    formData.set('is_active', '0');
                } else {
                    formData.set('is_active', '1');
                }

                try {
                    const response = await fetch('{{ route("admin.tools.store") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || $('input[name="_token"]').val()
                        }
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        showAlert('Tool created successfully!', 'success');
                        setTimeout(function() {
                            window.location.href = '{{ route("admin.tools.index") }}';
                        }, 1500);
                    } else {
                        if (data.errors) {
                            showErrors(data.errors);
                            showAlert(data.message || 'Please fix the errors below.', 'error');
                        } else {
                            showAlert(data.message || 'An error occurred while creating the tool.', 'error');
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showAlert('An error occurred. Please try again.', 'error');
                } finally {
                    // Re-enable submit button
                    $('#submit-btn').prop('disabled', false);
                    $('#submit-text').text('Create Tool');
                    $('#submit-spinner').addClass('d-none');
                }
            });

            function updateRemoveButtons() {
                var count = $('.image-input-group').length;
                $('.remove-image').toggle(count > 1);
                if (count === 1) {
                    $('.image-input-group:first .remove-image').hide();
                }
            }

            updateRemoveButtons();
        });
    </script>
</body>

</html>

