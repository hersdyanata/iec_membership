@extends('layouts.app')
@section('header')
    {{ $title }}
@endsection
@section('content')
<form id="form_data">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header bg-light-1 border-bottom border-light header-elements-inline">
                    <h5 class="card-title">{{ $title }}</h5>
                </div>

                <div class="card-body border-bottom border-light">
                    @method('put')
                    @csrf
                    <fieldset class="mb-12">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Nama Komoditas</label>
                            <div class="col-lg-10">
                                <input type="hidden" name="komoditas_id" id="komoditas_id" value="{{ $data->komoditas_id }}" readonly>
                                <input type="text" class="form-control" name="komoditas_nama" id="komoditas_nama" value="{{ $data->komoditas_nama }}">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Prefix</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="komoditas_prefix" id="komoditas_prefix" value="{{ $data->komoditas_prefix }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Spesifikasi</label>
                            <div class="col-lg-10">
                                <textarea name="komoditas_spesifikasi" id="komoditas_spesifikasi" rows="4" cols="4">
                                    {{ $data->komoditas_spesifikasi }}
                                </textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-2"></label>
                            <div class="col-lg-10">
                                <button class="btn btn-success btn-labeled btn-labeled-left" type="button" onclick="simpan_data()">
                                    <b><i class="icon-floppy-disk"></i></b>Simpan
                                </button>
                                <a href="{{ route('masterdata.mst_komoditas.index') }}" class="btn btn-danger btn-labeled btn-labeled-left"><b>
                                    <i class="icon-cross"></i></b>Cancel
                                </a>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('page_resources')
    <script src="{{ asset('assets/global/js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('page_js')
<script>
    CKEDITOR.config.customConfig = 'config_{{ session('theme') }}.js';
    CKEDITOR.replace('komoditas_spesifikasi', {
        height: 400
    });
    function simpan_data(){
        for ( instance in CKEDITOR.instances ) {
            CKEDITOR.instances[instance].updateElement();
        }

        $.ajax({
            type: "PUT",
            url: "{{ route('masterdata.mst_komoditas.update', ':id') }}".replace(':id', {{ $data->komoditas_id }}),
            data: $('#form_data').serialize(),
            beforeSend: function(){
                small_loader_open('form_data');
            },
            success: function (s) {
                sw_success_redirect(s, "{{ route('masterdata.mst_komoditas.index') }}");
            },
            error: function(e){
                sw_multi_error(e);
            },
            complete: function(){
                small_loader_close('form_data');
            }
        });
    }
</script>
@endsection