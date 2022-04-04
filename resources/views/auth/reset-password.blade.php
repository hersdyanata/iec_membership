@extends('layouts.guest')
@section('bgcolor')
    bg-teal
@endsection
@section('content')
<div class="content d-flex justify-content-center align-items-center">
    <!-- Login card -->
    <form class="login-form" method="POST" action="{{ route('password.update') }}">
        @csrf
        <div class="card mb-0">
            <div class="card-body">
                <div class="text-center mb-3">
                    <i class="icon-user-lock icon-2x text-teal border-teal border-3 rounded-pill p-3 mb-3 mt-1"></i>
                    <h5 class="mb-0">Reset Password</h5>
                    <span class="d-block text-muted">Silahkan masukkan password baru untuk akun Anda.</span>
                </div>

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <p class="text-danger text-center">{{ $error }}</p>
                    @endforeach
                @endif

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="form-group form-group-feedback form-group-feedback-left">
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" class="form-control" placeholder="Email" required readonly>
                    <div class="form-control-feedback">
                        <i class="icon-user text-muted"></i>
                    </div>
                </div>

                <div class="form-group form-group-feedback form-group-feedback-left">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password" required autocomplete="new-password" autofocus>
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
                    <button type="submit" class="btn btn-primary btn-block">Submit</button>
                </div>
            </div>
        </div>
    </form>
    <!-- /login card -->    
</div>
@endsection
