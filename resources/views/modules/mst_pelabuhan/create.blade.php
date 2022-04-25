@extends('layouts.app')
@section('content')
<form id="form_data">
    <div class="row">
        <div class="col-xl-12">
            <div class="card" id="section_divider">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">{{ $title }}</h5>
                </div>

                <div class="card-body">
                    @csrf
                    <fieldset class="mb-3">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Negara</label>
                            <div class="col-lg-9">
                                <select class="form-control select-search" name="port_negara_kode" id="port_negara_kode">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($negara as $n)
                                        <option value="{{ $n->negara_kode }}">{{ $n->negara_nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Kode Pelabuhan</label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" name="port_kode_tujuan" id="port_kode_tujuan">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Nama Pelabuhan</label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" name="port_nama" id="port_nama">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-2"></label>
                            <div class="col-lg-9">
                                <button class="btn btn-success btn-sm btn-labeled btn-labeled-left" type="button" onclick="simpan_data()">
                                    <b><i class="icon-floppy-disk"></i></b>Simpan
                                </button>
                                <a href="{{ route('masterdata.mst_pelabuhan.index') }}" class="btn btn-danger btn-sm btn-labeled btn-labeled-left"><b>
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

@section('page_js')
<script>

    $('document').ready(function(){
        
    });

    function simpan_data(){
        $.ajax({
            type: "POST",
            url: "{{ route('masterdata.mst_pelabuhan.store') }}",
            data: $('#form_data').serialize(),
            beforeSend: function(){
                 small_loader_open('form_data');
            },
            success: function (s) {
                console.log(s);
                 sw_success_redirect(s, "{{ route('masterdata.mst_pelabuhan.index') }}");
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