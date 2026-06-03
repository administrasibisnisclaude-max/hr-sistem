<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HR Sistem</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #1e2a3a 0%, #3b5998 100%); min-height: 100vh; display: flex; align-items: center; }
        .login-card { border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.3); overflow: hidden; }
        .login-card .card-body { padding: 40px; }
        .login-logo { width: 60px; height: 60px; background: #3b5998; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
        .form-control { border-radius: 8px; padding: 12px 16px; border: 1.5px solid #dee2e6; }
        .form-control:focus { border-color: #3b5998; box-shadow: 0 0 0 0.2rem rgba(59,89,152,0.15); }
        .btn-login { background: #3b5998; border-radius: 8px; padding: 12px; font-weight: 600; letter-spacing: 0.5px; border: none; }
        .btn-login:hover { background: #2d4785; }
        .input-group-text { border-radius: 8px 0 0 8px; background: #f8f9fa; border: 1.5px solid #dee2e6; }
        .input-group .form-control { border-radius: 0 8px 8px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="login-card card">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="login-logo">
                                <i class="fas fa-users text-white fa-2x"></i>
                            </div>
                            <h4 class="fw-bold text-dark">HR Sistem</h4>
                            <p class="text-muted small">Masuk ke akun Anda</p>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger alert-sm">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form action="{{ route('login.post') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope text-muted"></i></span>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                        placeholder="Masukkan email" value="{{ old('email') }}" autofocus>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-dark">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock text-muted"></i></span>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Masukkan password">
                                </div>
                            </div>
                            <div class="mb-3 d-flex align-items-center justify-content-between">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label text-muted small" for="remember">Ingat saya</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-login btn-primary w-100 text-white">
                                <i class="fas fa-sign-in-alt me-2"></i>Masuk
                            </button>
                        </form>

                        <div class="mt-4 p-3 bg-light rounded text-center">
                            <small class="text-muted d-block mb-1"><strong>Demo Accounts:</strong></small>
                            <small class="text-muted">owner@hrsistem.com | password</small><br>
                            <small class="text-muted">admin@hrsistem.com | password</small><br>
                            <small class="text-muted">budi@hrsistem.com | password</small>
                        </div>
                    </div>
                </div>
                <p class="text-center text-white-50 mt-3 small">© {{ date('Y') }} HR Sistem. All rights reserved.</p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
