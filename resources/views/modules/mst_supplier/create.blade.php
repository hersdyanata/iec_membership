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
                    @csrf
                    <fieldset class="mb-12">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Nama Supplier</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="supplier_nama" id="supplier_nama">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Alamat</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="supplier_alamat" id="supplier_alamat">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Komoditas</label>
                            <div class="col-lg-10">
                                <select name="supplier_komoditas[]" id="supplier_komoditas" multiple="multiple" class="form-control select" data-fouc data-placeholder="Pilih Komoditas...">
                                    @foreach ($produk as $r)
                                        <option value="{{ $r->komoditas_id }}">{{ $r->komoditas_nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-form-label col-lg-2"></label>
                            <div class="col-lg-10">
                                <button class="btn btn-success btn-labeled btn-labeled-left" type="button" onclick="simpan_data()">
                                    <b><i class="icon-floppy-disk"></i></b>Simpan
                                </button>
                                <a href="{{ route('masterdata.mst_supplier.index') }}" class="btn btn-danger btn-labeled btn-labeled-left"><b>
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
    function simpan_data(){
        $.ajax({
            type: "POST",
            url: "{{ route('masterdata.mst_supplier.store') }}",
            data: $('#form_data').serialize(),
            beforeSend: function(){
                small_loader_open('form_data');
            },
            success: function (s) {
                sw_success_redirect(s, "{{ route('masterdata.mst_supplier.index') }}");
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