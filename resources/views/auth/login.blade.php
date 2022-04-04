@extends('layouts.guest')
@section('bgcolor')
    bg-secondary
@endsection
@section('content')
<div class="content d-flex justify-content-center align-items-center">
    <!-- Login card -->
    <form class="login-form" method="POST" action="{{ route('login') }}">
        @csrf
        <div class="card mb-0">
            <div class="card-body">
                <div class="text-center mb-3">
                    {{-- <i class="icon-people icon-2x text-secondary border-secondary border-3 rounded-pill p-3 mb-3 mt-1"></i> --}}
                    <img src="{{ asset('assets') }}/global/images/iec_logo.png" style="height: 100px;"><br><br>
                    <h5 class="mb-0">- L O G I N -</h5>
                    <span class="d-block text-muted">Menggunakan email dan password</span>
                </div>

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <p class="text-danger text-center">{{ $error }}</p>
                    @endforeach
                @endif

                <div class="form-group form-group-feedback form-group-feedback-left">
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email" autofocus>
                    <div class="form-control-feedback">
                        <i class="icon-user text-muted"></i>
                    </div>
                </div>

                <div class="form-group form-group-feedback form-group-feedback-left">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password" required autocomplete="current-password">
                    <div class="form-control-feedback">
                        <i class="icon-lock2 text-muted"></i>
                    </div>
                </div>

                <div class="form-group d-flex align-items-center">
                    <label class="custom-control custom-checkbox">
                        <input type="checkbox" name="remember" id="remember_me" class="custom-control-input">
                        <span class="custom-control-label">Ingat Saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        {{-- <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a> --}}
                        <a href="{{ route('password.request') }}" class="ml-auto">Lupa Password?</a>
                    @endif
                    {{-- <a href="login_password_recover.html" class="ml-auto">Forgot password?</a> --}}
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Sign in</button>
                </div>

                <div class="form-group text-center text-muted content-divider">
                    <span class="px-2">Jika belum punya akun</span>
                </div>

                <div class="form-group">
                    <a href="{{ route('register') }}" class="btn btn-indigo btn-block">Buat Disini</a>
                </div>

                <span class="form-text text-center text-muted">Dengan melanjutkan, berarti Anda telah menyetujui dan telah membaca <a href="#">Terms &amp; Conditions</a> and <a href="#">Cookie Policy</a></span>
            </div>
        </div>
    </form>
    <!-- /login card -->   
</div> 
@endsection
