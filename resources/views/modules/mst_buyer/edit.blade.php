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
                    @method('put')
                    <fieldset class="mb-12">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Nama Perusahaan</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="buyer_perusahaan" id="buyer_perusahaan" value="{{ $data->buyer_perusahaan }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">PIC</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="buyer_pic" id="buyer_pic" value="{{ $data->buyer_pic }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">No. HP</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="buyer_no_hp" id="buyer_no_hp" value="{{ $data->buyer_no_hp }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Alamat</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="buyer_alamat" id="buyer_alamat" value="{{ $data->buyer_alamat }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Negara</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="buyer_negara" id="buyer_negara" value="{{ $data->buyer_negara }}">
                                <input type="hidden" readonly name="buyer_id" id="buyer_id" value="{{ $data->buyer_id }}" readonly>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-form-label col-lg-2"></label>
                            <div class="col-lg-10">
                                <button class="btn btn-success btn-labeled btn-labeled-left" type="button" onclick="simpan_data()">
                                    <b><i class="icon-floppy-disk"></i></b>Simpan
                                </button>
                                <a href="{{ route('masterdata.mst_buyer.index') }}" class="btn btn-danger btn-labeled btn-labeled-left"><b>
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
            type: "PUT",
            url: "{{ route('masterdata.mst_buyer.update', ':id') }}".replace(':id', {{ $data->buyer_id }}),
            data: $('#form_data').serialize(),
            beforeSend: function(){
                small_loader_open('form_data');
            },
            success: function (s) {
                sw_success_redirect(s, "{{ route('masterdata.mst_buyer.index') }}");
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