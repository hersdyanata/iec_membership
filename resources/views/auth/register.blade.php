@extends('layouts.guest')
@section('bgcolor')
    bg-indigo
@endsection
@section('content')
<div class="content d-flex justify-content-center align-items-center">
    <!-- Login card -->
    <form class="login-form" method="POST" action="{{ route('register') }}">
        @csrf
        <div class="card mb-0">
            <div class="card-body">
                <div class="text-center mb-3">
                    <img src="{{ asset('assets') }}/global/images/iec_logo.png" style="height: 100px;"><br><br>
                    <h5 class="mb-0">- D A F T A R -</h5>
                    <span class="d-block text-muted">Lengkapi form dibawah ini</span>
                </div>

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <p class="text-danger text-center">{{ $error }}</p>
                    @endforeach
                @endif

                <div class="form-group form-group-feedback form-group-feedback-left">
                    <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Nama" autofocus>
                    <div class="form-control-feedback">
                        <i class="icon-user text-muted"></i>
                    </div>
                </div>

                <div class="form-group form-group-feedback form-group-feedback-left">
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email" required>
                    <div class="form-control-feedback">
                        <i class="icon-mention text-muted"></i>
                    </div>
                </div>

                <div class="form-group form-group-feedback form-group-feedback-left">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password" required autocomplete="new-password">
                    <div class="form-control-feedback">
                        <i class="icon-lock2 text-muted"></i>
                    </div>
                </div>

                <div class="form-group form-group-feedback form-group-feedback-left">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Konfirmasi password" required>
                    <div class="form-control-feedback">
                        <i class="icon-lock2 text-muted"></i>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">daftar</button>
                </div>

                <div class="form-group text-center text-muted content-divider">
                    <span class="px-2">Sudah punya akun?</span>
                </div>

                <div class="form-group">
                    <a href="{{ route('login') }}" class="btn btn-indigo btn-block">login</a>
                </div>

                <span class="form-text text-center text-muted">Dengan melanjutkan, berarti Anda telah menyetujui dan telah membaca <a href="#">Terms &amp; Conditions</a> and <a href="#">Cookie Policy</a></span>
            </div>
        </div>
    </form>
    <!-- /login card -->   
</div> 
@endsection
