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
                            <label class="col-form-label col-lg-2">Nama</label>
                            <div class="col-lg-9">
                                <input type="hidden" class="form-control" name="id" id="id" value="{{ $owned->id }}" readonly>
                                <input type="text" class="form-control" name="name" id="name" value="{{ $owned->name }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Email</label>
                            <div class="col-lg-9">
                                <input type="email" class="form-control" name="email" id="email" value="{{ $owned->email }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Username</label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" name="username" id="username" value="{{ $owned->username }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Group Pengguna</label>
                            <div class="col-lg-9">
                                <select class="form-control select" name="group_id" id="group_id" data-fouc>
                                    <option value="">-- Pilih --</option>
                                    @foreach ($groups as $g)
                                        <option value="{{ $g->group_id }}" {{ ($owned->group_id === $g->group_id) ? 'selected' : ''}}>{{ $g->group_nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <input type="hidden" class="form-control" name="password" id="password" value="{{ $owned->password }}" readonly>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-2"></label>
                            <div class="col-lg-9">
                                <button class="btn btn-success btn-sm btn-labeled btn-labeled-left" type="button" onclick="simpan_data()">
                                    <b><i class="icon-floppy-disk"></i></b>Simpan
                                </button>
                                <a href="{{ route('user.index') }}" class="btn btn-danger btn-sm btn-labeled btn-labeled-left"><b>
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
            type: "PUT",
            url: "{{ route('user.update', $owned->id) }}",
            data: $('#form_data').serialize(),
            beforeSend: function(){
                small_loader_open('form_data');
            },
            success: function (s) {
                sw_success_redirect(s, "{{ route('user.index') }}");
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