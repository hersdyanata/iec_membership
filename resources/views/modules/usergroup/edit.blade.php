@extends('layouts.app')
@section('content')
<form id="form_data">
    <div class="row">
        <div class="col-xl-4">
            <div class="card" id="section_divider">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">Usergroup</h5>
                </div>

                <div class="card-body">
                    @csrf
                    <fieldset class="mb-3">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Nama Group</label>
                            <div class="col-lg-9">
                                <input type="hidden" class="form-control" name="group_id" id="group_id" value="{{ $owned->group_id }}" readonly>
                                <input type="text" class="form-control" name="group_nama" id="group_nama" value="{{ $owned->group_nama }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Deskripsi</label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" name="group_deskripsi" id="group_deskripsi" value="{{ $owned->group_deskripsi }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Default Menu</label>
                            <div class="col-lg-9">
                                <select class="form-control select-search" name="group_default_menu" id="group_default_menu" data-fouc>
                                    <option value="">-- Pilih --</option>
                                    @foreach ($list_menu as $i => $d)
                                        <optgroup label="{{ $d['div_nama'] }}">
                                            @foreach ($d['menus'] as $m)
                                                <option value="{{ $m['menu_id'] }}" {{ ($owned->group_default_menu === $m['menu_id']) ? 'selected' : '' }}>{{ $m['menu_nama_ina'] }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-3"></label>
                            <div class="col-lg-9">
                                <button class="btn btn-success btn-labeled btn-labeled-left" type="button" onclick="simpan_data()">
                                    <b><i class="icon-floppy-disk"></i></b>Simpan
                                </button>
                                <a href="{{ route('usergroup.index') }}" class="btn btn-danger btn-labeled btn-labeled-left"><b>
                                    <i class="icon-cross"></i></b>Cancel
                                </a>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card" id="section_menu">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">Permissions</h5>
                </div>

                <div class="card-body">

                    @php
                        $roles = json_decode($owned->group_menu_permission);
                        $saved_menus = [];
                        $saved_permissions = [];
                        foreach($roles as $idx => $rl){
                            $saved_menus = Arr::prepend($saved_menus, $rl->menu_id);
                            $saved_permissions[$rl->menu_id] = $rl->permissions;
                        }
                    @endphp

                    @csrf
                    <label class="form-check-label d-flex align-items-center">
                        <input type="checkbox" name="check_all" id="check_all" data-on-text="Grant All" data-off-text="Revoke All" class="form-check-input-switch" data-size="small" data-on-color="success" data-off-color="danger">
                    </label><hr>
                    @foreach ($list_menu as $i => $d)
                        <fieldset class="mb-3">
                            <legend class="text-uppercase font-size-sm font-weight-bold">{{ $d['div_nama'] }}</legend>
                            @foreach ($d['menus'] as $m)
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">{{ $m['menu_nama_ina'] }}</label>
                                    <div class="col-lg-3">
                                        <input type="checkbox"
                                                class="form-check-input form-check-input-switch menu_toggle_check"
                                                name="group_menu_permission[{{ $m['menu_id'] }}]" 
                                                id="group_menu_permission{{ $m['menu_id'] }}"
                                                data-on-text="GRANT" 
                                                data-off-text="REVOKE" 
                                                data-on-color="success" 
                                                data-off-color="default"
                                                @if (in_array($m['menu_id'], $saved_menus))
                                                    checked
                                                @endif>
                                    </div>

                                    <div class="col-lg-7">
                                        <label class="form-check-label d-flex align-items-center">
                                            @forelse ($m['permission'] as $idx => $p)
                                                {{ strtoupper($p['map_perm_nama']) }} &nbsp;
                                                <input type="checkbox" 
                                                        name="permissions[{{ $m['menu_id'] }}][{{ $p['map_perm_nama'] }}]" 
                                                        id="permissions" 
                                                        data-on-text="Bisa" 
                                                        data-off-text="Tidak" 
                                                        class="form-check-input-switch permission_toggle_check grp_perm{{ $m['menu_id'] }}" 
                                                        {{-- data-size="small" --}}
                                                        @if ( Arr::exists($saved_permissions, $m['menu_id']) && in_array($p['map_perm_nama'], $saved_permissions[$m['menu_id']]) )
                                                            checked
                                                        @endif> &nbsp;&nbsp;&nbsp;
                                                        <script>
                                                            $(".grp_perm{{ $m['menu_id'] }}").on('switchChange.bootstrapSwitch', function(event, state) {
                                                                if(state == true){
                                                                    $("#group_menu_permission{{ $m['menu_id'] }}").bootstrapSwitch('state', true);
                                                                }

                                                                var permission_checked = $(".grp_perm{{ $m['menu_id'] }}:checked").length;
                                                                if(permission_checked > 0){
                                                                    $("#group_menu_permission{{ $m['menu_id'] }}").bootstrapSwitch('state', true);
                                                                }
                                                            });
                                                        </script>
                                            @empty
                                                <code>Diasumsikan tidak memerlukan custom permission ketika di-grant</code>
                                            @endforelse
                                        </label>
                                    </div>
                                </div>
                                <script>
                                    $("#group_menu_permission{{ $m['menu_id'] }}").on('switchChange.bootstrapSwitch', function(e, s) {
                                        var permission_checked = $(".grp_perm{{ $m['menu_id'] }}:checked").length;
                                        if(s == false){
                                            if(permission_checked > 0){
                                                $(".grp_perm{{ $m['menu_id'] }}").bootstrapSwitch('state', false);
                                                $(this).bootstrapSwitch('state', false);
                                            }
                                        }
                                    });
                                </script>
                            @endforeach
                        </fieldset>
                        <hr>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('page_js')
<script>

    $('document').ready(function(){
        $('#check_all').on('switchChange.bootstrapSwitch', function (event, state) {
            if(state == true){
                $('.menu_toggle_check').bootstrapSwitch('state', true);
                $('.permission_toggle_check').bootstrapSwitch('state', true);
            }else{
                $('.menu_toggle_check').bootstrapSwitch('state', false);
                $('.permission_toggle_check').bootstrapSwitch('state', false);
            }
        });

        var menu_checked = $(".menu_toggle_check:checked").length;
        var menu_toggle = $('.menu_toggle_check').length;
        // if(menu_checked == menu_toggle){
        //     $('#check_all').bootstrapSwitch('state', true);
        // }
    });

    function simpan_data(){
        $.ajax({
            type: "PUT",
            url: "{{ route('usergroup.update', $owned->group_id) }}",
            data: $('#form_data').serialize(),
            beforeSend: function(){
                small_loader_open('form_data');
            },
            success: function (s) {
                sw_success_redirect(s, "{{ route('usergroup.index') }}");
            },
            error: function(e){
                sw_multi_error(e);
            },
            complete: function(){
                small_loader_close('form_data');
            }
        });
    }

    function reset_form_divider(){
        $('#form_divider')[0].reset();
        $('#form_divider #action_method').val('save');
        $('#form_divider #div_nama_ina').focus();
    }
</script>
@endsection