<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ $settings['website_name'] ?? 'Admin' }}</title>

    <link href="{{ asset('admin-view/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset('admin-view/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .login-left {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            color: white;
        }
        .login-left-content {
            text-align: center;
        }
        .login-logo {
            max-width: 150px;
            margin-bottom: 1.5rem;
        }
        .login-right {
            background: white;
            padding: 3rem;
        }
        .form-control-icon {
            position: relative;
        }
        .form-control-icon input {
            padding-left: 45px;
        }
        .form-control-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
        .input-group-custom {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .input-group-custom input {
            border-radius: 10px;
            border: 2px solid #e3e6f0;
            padding: 12px 15px 12px 45px;
            transition: all 0.3s;
        }
        .input-group-custom input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .input-group-custom i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            z-index: 10;
        }
    </style>
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card login-card border-0 shadow-lg">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-lg-5 login-left d-none d-lg-flex">
                                <div class="login-left-content">
                                    @if($settings['logo_light'] ?? null)
                                        <img src="{{ $settings['logo_light'] }}" alt="{{ $settings['website_name'] ?? '' }}" class="login-logo">
                                    @else
                                        <div class="mb-4">
                                            <i class="fas fa-tools fa-4x mb-3"></i>
                                        </div>
                                    @endif
                                    <h2 class="font-weight-bold mb-3">{{ $settings['website_name'] ?? 'Admin Panel' }}</h2>
                                    <p class="mb-0">{{ $settings['tagline'] ?? 'Welcome to Admin Dashboard' }}</p>
                                </div>
                            </div>
                            <div class="col-lg-7 login-right">
                                <div class="p-4 p-md-5">
                                    <div class="text-center mb-4">
                                        <h3 class="font-weight-bold text-gray-900 mb-2">Welcome Back!</h3>
                                        <p class="text-muted">Sign in to continue to your account</p>
                                    </div>
                                    
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        
                                        @if($errors->any())
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <i class="fas fa-exclamation-circle me-2"></i>
                                                @foreach($errors->all() as $error)
                                                    {{ $error }}
                                                @endforeach
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        @endif

                                        <div class="input-group-custom">
                                            <i class="fas fa-envelope"></i>
                                            <input type="email" 
                                                class="form-control @error('email') is-invalid @enderror" 
                                                id="email" 
                                                name="email" 
                                                value="{{ old('email') }}" 
                                                placeholder="Email Address" 
                                                required 
                                                autofocus>
                                            @error('email')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="input-group-custom">
                                            <i class="fas fa-lock"></i>
                                            <input type="password" 
                                                class="form-control @error('password') is-invalid @enderror" 
                                                id="password" 
                                                name="password" 
                                                placeholder="Password" 
                                                required>
                                            @error('password')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                                                <label class="custom-control-label" for="remember">Remember Me</label>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-login btn-block w-100">
                                            <i class="fas fa-sign-in-alt me-2"></i>Sign In
                                        </button>
                                    </form>

                                    <div class="text-center mt-4">
                                        <a href="/" class="text-decoration-none text-muted small">
                                            <i class="fas fa-arrow-left me-1"></i>Back to Website
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('admin-view/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin-view/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin-view/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('admin-view/js/sb-admin-2.min.js') }}"></script>
</body>

</html>

