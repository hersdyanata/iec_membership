@extends('layouts.guest')
@section('bgcolor')
    bg-indigo
@endsection
@section('content')
    <div class="page-content">
		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Inner content -->
			<div class="content-inner">

				<!-- Content area -->
				<div class="content d-flex justify-content-center align-items-center">
                    <div class="card col-lg-4">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <i class="icon-mention icon-2x text-indigo border-indigo border-3 rounded-pill p-3 mb-3 mt-1"></i>
                                    <h5 class="mb-0">- VERIFIKASI EMAIL -</h5>
                                </div>

                                <p class="text-center">
                                    Terima kasih telah mendaftar. Sebelum mengakses aplikasi ini, alamat email yang Anda gunakan harus terverifikasi terlebih dahulu. Kami telah mengirimkan link verifikasi ke email Anda.
                                </p>

                                @if (session('status') == 'verification-link-sent')
                                    <p class="text-info text-center">
                                        Kami telah mengirimkan link verifikasi ke alamat email Anda.
                                    </p>
                                @endif

                                <button type="submit" class="btn btn-primary btn-block">kirim ulang</button>
                            </div>
                        </form>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <div class="card-body">            
                                <button type="submit" class="btn btn-secondary btn-block">Logout</button>
                            </div>
                        </form>
                    </div>

				</div>
				<!-- /content area -->

			</div>
			<!-- /inner content -->

		</div>
		<!-- /main content -->

	</div>

@endsection
