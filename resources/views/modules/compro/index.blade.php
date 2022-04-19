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
						<h6 class="card-title">Company Profile</h6>
					</div>
					<div class="card-body">
						<div id="alert_profile"></div>
						<form id="form_data">
							@csrf
							@method('put')
							<input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}" class="form-control" readonly>
							<div class="form-group">
								<div class="row">
									<div class="col-lg-12">
										<label>Nama Perusahaan</label>
										<input type="text" name="comp_nama_perusahaan" value="" class="form-control">
									</div>
								</div>
							</div>
								
							<div class="form-group">
								<div class="row">
									<div class="col-lg-6">
										<label>Tempat Berdiri <span class="text-danger" id="star_comp_tempat_berdiri"></span></label>
										<input type="text" name="comp_tempat_berdiri" id="comp_tempat_berdiri" class="form-control" value="">
									</div>
									<div class="col-lg-6">
										<label>Tanggal Berdiri <span class="text-danger" id="star_comp_tanggal_berdiri"></span></label>
										<input type="text" name="comp_tanggal_berdiri" id="comp_tanggal_berdiri" class="form-control pickadate-selectors" value="">
									</div>
								</div>
							</div>
				
							<div class="form-group">
								<div class="row">
									<div class="col-lg-3">
										<label>Provinsi <span class="text-danger" id="star_comp_provinsi"></span></label>
										<select class="select-search" name="comp_provinsi" id="comp_provinsi">
											<option value="">-- PILIH PROVINSI --</option>
										</select>
									</div>
									<div class="col-lg-3">
										<label>Kota <span class="text-danger" id="star_comp_kota"></span></label>
										<select class="select-search" name="comp_kota" id="comp_kota">
											<option value="">-- PILIH KOTA --</option>
										</select>
									</div>
									<div class="col-lg-3">
										<label>Kecamatan <span class="text-danger" id="star_comp_kecamatan"></span></label>
										<select class="select-search" name="comp_kecamatan" id="comp_kecamatan">
											<option value="">-- PILIH KECAMATAN --</option>
										</select>
									</div>
									<div class="col-lg-3">
										<label>Kelurahan <span class="text-danger" id="star_comp_kelurahan"></span></label>
										<select class="select-search" name="comp_kelurahan" id="comp_kelurahan">
											<option value="">-- PILIH KELURAHAN --</option>
										</select>
									</div>
								</div>
							</div>
				
							<div class="form-group">
								<div class="row">
									<div class="col-lg-9">
										<label>Alamat <span class="text-danger" id="star_comp_alamat"></span></label>
										<input type="text" name="comp_alamat" id="comp_alamat" class="form-control" value="">
									</div>
									<div class="col-lg-3">
										<label>Kode POS <span class="text-danger" id="star_comp_kodepos"></span></label>
										<input type="text" name="comp_kodepos" id="comp_kodepos" class="form-control" value="">
									</div>
								</div>
							</div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Komoditas</label>
                                        <select class="select" name="comp_komoditas" id="comp_komoditas">
											<option value="">-- Pilih Komoditas --</option> 
										</select>
                                    </div>
                                </div>
                            </div>
				
							<div class="form-group">
								<div class="row">
									<div class="col-lg-4">
										<label>PIC</label>
										<input type="text" name="comp_pic" id="comp_pic" class="form-control" value="">
									</div>
									<div class="col-lg-4">
										<label>No. Whatsapp</label>
										<input type="text" name="comp_wa" id="comp_wa" class="form-control" value="">
									</div>
									<div class="col-lg-4">
										<label>Website <span class="text-danger" id="star_comp_website"></span></label>
										<input type="text" name="comp_website" id="comp_website" class="form-control" value="">
									</div>
								</div>
							</div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-lg-2">Company Profile</label>
                                            <div class="col-lg-10">
                                                <input type="file" name="comp_file_profile" id="comp_file_profile" class="form-control-plaintext">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-lg-2">Katalog</label>
                                            <div class="col-lg-10">
                                                <input type="file" name="comp_file_katalog" id="comp_file_katalog" class="form-control-plaintext">
                                            </div>
                                        </div>
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

	var bprovinsi = "";
	var bkota = "";
	var bkecamatan = "";
	var bkelurahan = "";

	$('#comp_provinsi').on('change', function(){
		kota(this.value);
	});

	$('#comp_kota').on('change', function(){
		kecamatan(this.value);
	});

	$('#comp_kecamatan').on('change', function(){
		kelurahan(this.value);
	});

	// status_kelengkapan_profile();
	provinsi();

	function provinsi(){
		$.ajax({
			url: "{{ route('masterdata.wilayah.provinsi') }}",
			type: "GET",
			datatype: "JSON",
			beforeSend: function(){
				small_loader_open('comp_provinsi');
			},
			success: function(res){
				$('#comp_provinsi').html(res);
			},
			error: function (jqXHR, textStatus, errorThrown){
				alert('Error get data from ajax');
			},
			complete: function(){
				if(bprovinsi != ''){
					$('#comp_provinsi').val(bprovinsi);
					kota(bprovinsi);
				}

				small_loader_close('comp_provinsi');
			}
		});
	}

	function kota(id){
		$.ajax({
			url: "{{ route('masterdata.wilayah.kota', ':id') }}".replace(':id', id),
			type: "GET",
			datatype: "JSON",
			beforeSend: function(){
				small_loader_open('comp_kota');
			},
			success: function(res){
				$('#comp_kota').html(res);
			},
			error: function (jqXHR, textStatus, errorThrown){
				alert('Error get data from ajax');
			},
			complete: function(){
				if(bkota != ''){
					$('#comp_kota').val(bkota);
					kecamatan(bkota);
				}
				
				small_loader_close('comp_kota');
			}
		});
	}

	function kecamatan(id){
		$.ajax({
			url: "{{ route('masterdata.wilayah.kecamatan', ':id') }}".replace(':id', id),
			type: "GET",
			datatype: "JSON",
			beforeSend: function(){
				small_loader_open('comp_kecamatan');
			},
			success: function(res){
				$('#comp_kecamatan').html(res);
			},
			error: function (jqXHR, textStatus, errorThrown){
				alert('Error get data from ajax');
			},
			complete: function(){
				if(bkecamatan != ''){
					$('#comp_kecamatan').val(bkecamatan);
					kelurahan(bkecamatan);
				}

				small_loader_close('comp_kecamatan');
			}
		});
	}

	function kelurahan(id){
		$.ajax({
			url: "{{ route('masterdata.wilayah.kelurahan', ':id') }}".replace(':id', id),
			type: "GET",
			datatype: "JSON",
			beforeSend: function(){
				small_loader_open('comp_kelurahan');
			},
			success: function(res){
				$('#comp_kelurahan').html(res);
			},
			error: function (jqXHR, textStatus, errorThrown){
				alert('Error get data from ajax');
			},
			complete: function(){
				if(bkelurahan != ''){
					$('#comp_kelurahan').val(bkelurahan);
				}

				small_loader_close('comp_kelurahan');
			}
		});
	}

	function simpan_profile(){
		var id = $('#user_id').val();
		$.ajax({
			type: "PUT",
			url: "{{ route('main.compro.update', ':id') }}".replace(':id', id),
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