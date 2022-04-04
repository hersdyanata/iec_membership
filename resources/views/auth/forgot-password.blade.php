@extends('layouts.guest')
@section('bgcolor')
    bg-teal
@endsection
@section('content')
<div class="content d-flex justify-content-center align-items-center">
    <!-- Login card -->
    <form class="login-form" method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="card mb-0">
            <div class="card-body">
                <div class="text-center mb-3">
                    <i class="icon-question7 icon-2x text-teal border-teal border-3 rounded-pill p-3 mb-3 mt-1"></i>
                    <h5 class="mb-0">Lupa Password?</h5>
                    <span class="d-block text-muted">Silahkan masukkan email yang Anda gunakan untuk daftar.</span>
                </div>

                @if (session('status'))
                        {{ session('status') }}
                @endif

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

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Kirimkan Link Reset</button>
                </div>

                <div class="form-group text-center text-muted content-divider">
                    <span class="px-2">Sudah ingat password?</span>
                </div>

                <div class="form-group">
                    <a href="{{ route('login') }}" class="btn btn-teal btn-block">login</a>
                </div>

            </div>
        </div>
    </form>
    <!-- /login card -->    
</div>
@endsection
