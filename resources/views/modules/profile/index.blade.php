@extends('layouts.app')
@section('header')
    {{ $title }}
@endsection
@section('content')
	<!-- Inner content -->
	<div class="content-inner">
		<!-- Content area -->
		<div class="content pt-0">

			<!-- Inner container -->
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header">
						<h6 class="card-title">Profile information</h6>
					</div>
					<div class="card-body">
						<div id="alert_profile"></div>
						<form id="form_data">
							@csrf
							@method('put')
							<input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}" class="form-control" readonly>
							<div class="form-group">
								<div class="row">
									<div class="col-lg-6">
										<label>Nama</label>
										<input type="text" value="{{ Auth::user()->name }}" class="form-control" readonly>
									</div>
									<div class="col-lg-6">
										<label>Email</label>
										<input type="text" value="{{ Auth::user()->email }}" class="form-control" readonly>
									</div>
								</div>
							</div>
				
							<div class="form-group">
								<div class="row">
									<div class="col-lg-6">
										<label>Nomor Anggota <span class="text-danger" id="star_profile_no_anggota"></span></label>
										<input type="text" name="profile_no_anggota" id="profile_no_anggota" class="form-control" value="{{ $profile['collections']->profile_no_anggota }}">
									</div>
									<div class="col-lg-6">
										<label>Jenis Kelamin</label>
										<select class="select" name="profile_gender" id="profile_gender">
											<option value="">-- Pilih Jenis Kelamin --</option> 
											<option value="L" {{ $profile['collections']->profile_gender == 'L' ? 'selected' : '' }}>Laki-laki</option> 
											<option value="P" {{ $profile['collections']->profile_gender == 'P' ? 'selected' : '' }}>Perempuan</option>
										</select>
									</div>
								</div>
							</div>
				
							<div class="form-group">
								<div class="row">
									<div class="col-lg-6">
										<label>Tempat Lahir <span class="text-danger" id="star_profile_tempat_lahir"></span></label>
										<input type="text" name="profile_tempat_lahir" id="profile_tempat_lahir" class="form-control" value="{{ $profile['collections']->profile_tempat_lahir }}">
									</div>
									<div class="col-lg-6">
										<label>Tanggal Lahir <span class="text-danger" id="star_profile_tgl_lahir"></span></label>
										<input type="text" name="profile_tanggal_lahir" id="profile_tanggal_lahir" class="form-control pickadate-selectors" value="{{ $profile['collections']->profile_tanggal_lahir }}">
									</div>
								</div>
							</div>
				
							<div class="form-group">
								<div class="row">
									<div class="col-lg-3">
										<label>Provinsi <span class="text-danger" id="star_profile_provinsi"></span></label>
										<select class="select-search" name="profile_provinsi" id="profile_provinsi">
											<option value="">-- PILIH PROVINSI --</option>
											@foreach ($provinsi as $p)
												<option value="{{ $p->kode }}">{{ $p->nama }}</option>
											@endforeach
										</select>
									</div>
									<div class="col-lg-3">
										<label>Kota <span class="text-danger" id="star_profile_kota"></span></label>
										<select class="select-search" name="profile_kota" id="profile_kota">
											<option value="">-- PILIH KOTA --</option>
										</select>
									</div>
									<div class="col-lg-3">
										<label>Kecamatan <span class="text-danger" id="star_profile_kecamatan"></span></label>
										<select class="select-search" name="profile_kecamatan" id="profile_kecamatan">
											<option value="">-- PILIH KECAMATAN --</option>
										</select>
									</div>
									<div class="col-lg-3">
										<label>Kelurahan <span class="text-danger" id="star_profile_kelurahan"></span></label>
										<select class="select-search" name="profile_kelurahan" id="profile_kelurahan">
											<option value="">-- PILIH KELURAHAN --</option>
										</select>
									</div>
								</div>
							</div>
				
							<div class="form-group">
								<div class="row">
									<div class="col-lg-9">
										<label>Alamat <span class="text-danger" id="star_profile_alamat"></span></label>
										<input type="text" name="profile_alamat" id="profile_alamat" class="form-control" value="{{ $profile['collections']->profile_alamat }}">
									</div>
									<div class="col-lg-3">
										<label>Kode POS <span class="text-danger" id="star_profile_kodepos"></span></label>
										<input type="text" name="profile_kodepos" id="profile_kodepos" class="form-control" value="{{ $profile['collections']->profile_kodepos }}">
									</div>
								</div>
							</div>
				
							<div class="form-group">
								<div class="row">
									<div class="col-lg-4">
										<label>Regional</label>
										<select class="select" name="profile_regional" id="profile_regional">
											<option value="">-- Pilih Regional --</option> 
											@foreach ($regionals as $r)
												<option value="{{ $r->reg_id }}" {{ $profile['collections']->profile_regional == $r->reg_id ? 'selected' : '' }}>{{ $r->reg_nama }}</option>
											@endforeach
										</select>
									</div>
									<div class="col-lg-4">
										<label>Saya adalah seorang</label>
										<select class="select" name="profile_member_type" id="profile_member_type">
											<option value="">-- Pilih Jenis Anggota --</option> 
											@foreach ($member_type as $t)
												<option value="{{ $t->type_id }}" {{ $profile['collections']->profile_member_type == $t->type_id ? 'selected' : '' }}>{{ $t->type_nama }}</option>
											@endforeach
										</select>
									</div>
									<div class="col-lg-4">
										<label>No. Whatsapp <span class="text-danger" id="star_profile_wa"></span></label>
										<input type="text" name="profile_wa" id="profile_wa" class="form-control" value="{{ $profile['collections']->profile_wa }}">
									</div>
								</div>
							</div>
				
							<div class="text-left">
								<button type="button" onclick="simpan_profile()" class="btn btn-primary">Simpan</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- /inner container -->

		</div>
		<!-- /content area -->


	</div>
	<!-- /inner content -->
@endsection

@section('page_resources')
    <script src="{{ asset('assets/global/js/plugins/pickers/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/global/js/plugins/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('assets/global/js/plugins/pickers/pickadate/picker.date.js') }}"></script>
    <script src="{{ asset('assets/global/js/plugins/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('assets/global/js/plugins/pickers/pickadate/legacy.js') }}"></script>
    <script src="{{ asset('assets/global/js/demo_pages/picker_date.js') }}"></script>


	<script src="{{ asset('assets/global/js/plugins/forms/inputs/typeahead/handlebars.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/plugins/forms/inputs/alpaca/alpaca.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/plugins/forms/inputs/alpaca/price_format.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/global/js/demo_pages/alpaca_advanced.js') }}"></script> --}}
@endsection

@section('page_js')
<script>

	var bprovinsi = "{{ $profile['collections']->profile_provinsi }}";
	var bkota = "{{ $profile['collections']->profile_kota }}";
	var bkecamatan = "{{ $profile['collections']->profile_kecamatan }}";
	var bkelurahan = "{{ $profile['collections']->profile_kelurahan }}";

	$('#profile_provinsi').on('change', function(){
		kota(this.value);
	});

	$('#profile_kota').on('change', function(){
		kecamatan(this.value);
	});

	$('#profile_kecamatan').on('change', function(){
		kelurahan(this.value);
	});

	status_kelengkapan_profile();
	provinsi();

	function provinsi(){
		$.ajax({
			url: "{{ route('masterdata.wilayah.provinsi') }}",
			type: "GET",
			datatype: "JSON",
			beforeSend: function(){
				small_loader_open('profile_provinsi');
			},
			success: function(res){
				$('#profile_provinsi').html(res);
			},
			error: function (jqXHR, textStatus, errorThrown){
				alert('Error get data from ajax');
			},
			complete: function(){
				if(bprovinsi != ''){
					$('#profile_provinsi').val(bprovinsi);
					kota(bprovinsi);
				}

				small_loader_close('profile_provinsi');
			}
		});
	}

	function kota(id){
		$.ajax({
			url: "{{ route('masterdata.wilayah.kota', ':id') }}".replace(':id', id),
			type: "GET",
			datatype: "JSON",
			beforeSend: function(){
				small_loader_open('profile_kota');
			},
			success: function(res){
				$('#profile_kota').html(res);
			},
			error: function (jqXHR, textStatus, errorThrown){
				alert('Error get data from ajax');
			},
			complete: function(){
				if(bkota != ''){
					$('#profile_kota').val(bkota);
					kecamatan(bkota);
				}
				
				small_loader_close('profile_kota');
			}
		});
	}

	function kecamatan(id){
		$.ajax({
			url: "{{ route('masterdata.wilayah.kecamatan', ':id') }}".replace(':id', id),
			type: "GET",
			datatype: "JSON",
			beforeSend: function(){
				small_loader_open('profile_kecamatan');
			},
			success: function(res){
				$('#profile_kecamatan').html(res);
			},
			error: function (jqXHR, textStatus, errorThrown){
				alert('Error get data from ajax');
			},
			complete: function(){
				if(bkecamatan != ''){
					$('#profile_kecamatan').val(bkecamatan);
					kelurahan(bkecamatan);
				}

				small_loader_close('profile_kecamatan');
			}
		});
	}

	function kelurahan(id){
		$.ajax({
			url: "{{ route('masterdata.wilayah.kelurahan', ':id') }}".replace(':id', id),
			type: "GET",
			datatype: "JSON",
			beforeSend: function(){
				small_loader_open('profile_kelurahan');
			},
			success: function(res){
				$('#profile_kelurahan').html(res);
			},
			error: function (jqXHR, textStatus, errorThrown){
				alert('Error get data from ajax');
			},
			complete: function(){
				if(bkelurahan != ''){
					$('#profile_kelurahan').val(bkelurahan);
				}

				small_loader_close('profile_kelurahan');
			}
		});
	}

	function simpan_profile(){
		var id = $('#user_id').val();
		$.ajax({
			type: "PUT",
			url: "{{ route('main.profile.update', ':id') }}".replace(':id', id),
			data: $('#form_data').serialize(),
			beforeSend: function(){
				small_loader_open('form_data');
			},
			success: function (s) {
				sw_success(s);
			},
			error: function(e){
				sw_multi_error(e);
			},
			complete: function(){
				status_kelengkapan_profile();
				small_loader_close('form_data');
			}
		});
	}

	function status_kelengkapan_profile(){
		$.ajax({
			url: "{{ route('main.profile.show', ':id') }}".replace(':id', "{{ Auth::user()->id }}"),
			type: "GET",
			datatype: "JSON",
			success: function(res){
				$('#alert_profile').html(res.alert);

				$.each(res.completed_data, function(key, val){
					// console.log(val.nama_kolom+' is valid ');
					$('#'+val.nama_kolom).removeClass('is-invalid');
					$('#'+val.nama_kolom).addClass('is-valid');
				});

				$.each(res.mandatory, function(k, v){
					$('#star_'+v.nama_kolom).html('*');
				});

				if(res.status == 'N'){
					$.each(res.uncomplete_data, function(key, val){
						$('#'+val.nama_kolom).addClass('is-invalid');
					});
				}
            },
			error: function (jqXHR, textStatus, errorThrown){
				alert('Error get data from ajax');
			}
		});
	}
</script>
@endsection