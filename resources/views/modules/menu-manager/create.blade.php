@extends('layouts.app')
@section('header')
    {{ $title }}
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-5">
            <div class="card" id="section_divider">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">DIVIDER MENU</h5>
                </div>

                <div class="card-body">
                    <form id="form_divider">
                        @csrf
                        <fieldset class="mb-3">
                            <div class="form-group row">
                                <input type="hidden" name="div_id" id="div_id" readonly>
                                <input type="hidden" name="action_method" id="action_method" readonly>
                                <label class="col-form-label col-lg-3">Nama Divider (INA)</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="div_nama_ina" id="div_nama_ina">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">Nama Divider (ENG)</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="div_nama_eng" id="div_nama_eng">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">Urutan</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="div_order" id="div_order">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">Publish ?</label>
                                <div class="col-lg-9">
                                    <input type="checkbox" class="form-check-input form-check-input-switch" name="div_publish_ke_user" id="div_publish_ke_user" data-on-text="YA" data-off-text="TIDAK" data-on-color="primary" data-off-color="default">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-3"></label>
                                <div class="col-lg-9">
                                    <button class="btn btn-success btn-labeled btn-labeled-left" type="button" onclick="simpan_divider()">
                                        <b><i class="icon-floppy-disk"></i></b>Simpan
                                    </button>
                                    <button class="btn btn-danger btn-labeled btn-labeled-left" type="button" onclick="reset_form_divider()">
                                        <b><i class="icon-cross"></i></b>Cancel
                                    </button>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                    <hr>
                    <button type="button" onclick="reload_table(table)" class="btn btn-teal btn-labeled btn-labeled-left">
                        <b><i class="icon-reload-alt"></i></b>Reload
                    </button>
                    <table id="table_divider" class="table table-hover datatable-show-all">
                        <thead>
                            <tr>
                                <th>Divider (INA)</th>
                                <th>Divider (ENG)</th>
                                <th>Order</th>
                                <th>Publish</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-7">
            <div class="card" id="section_menu">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">MENU</h5>
                </div>

                <div class="card-body">
                    <form id="form_menu">
                        @csrf
                        <fieldset class="mb-3">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">Nama Menu (INA)</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" name="menu_nama_ina" id="menu_nama_ina">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">Nama Menu (ENG)</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" name="menu_nama_eng" id="menu_nama_eng">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">Divider</label>
                                <div class="col-lg-10">
                                    <div class="form-group">
                                        <select class="form-control select" name="menu_div_id" id="menu_div_id">
                                            <option value="">Pilih Divider</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">Parent Menu</label>
                                <div class="col-lg-10">
                                    <div class="form-group">
                                        <select class="form-control select-search" name="menu_parent_id" id="menu_parent_id"></select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">Nama Controller</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" name="menu_controller" id="menu_controller">
                                    <span class="text-danger">Jika menu akan menjadi <strong>Parent Menu</strong>, silahkan isikan tanda (<code>#</code>) saja</span>
                                </div>
                                <script>
                                    $('#menu_controller').keyup(function(){
                                        if($('#menu_controller').val() == '#'){
                                            $('#menu_route_name').val($(this).val());
                                        }
                                    });
                                </script>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">Route Group</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" name="menu_route_group" id="menu_route_group">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">Route URL</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" name="menu_route_name" id="menu_route_name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">Nama Folder View</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" name="menu_folder_view" id="menu_folder_view">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">Icon</label>
                                <div class="input-group col-lg-10">
                                    <input type="text" class="form-control" placeholder="Pilih icon" name="menu_icon" id="menu_icon">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_full" data-backdrop="false">Icons Collections</button>
                                    </div>
                                    {{-- btn btn-success btn-labeled btn-labeled-left --}}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">Order</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" name="menu_order" id="menu_order">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">Publish ?</label>
                                <div class="col-lg-10">
                                    <input type="checkbox" class="form-check-input form-check-input-switch" name="menu_publish_ke_user" id="menu_publish_ke_user" data-on-text="YA" data-off-text="TIDAK" data-on-color="primary" data-off-color="default">
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="mb-3">
                            <legend class="text-uppercase font-size-sm font-weight-bold">Permission Yang Dimiliki Oleh Menu</legend>
                            
                            @foreach ($permissions as $r)
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">{{ $r->perm_deskripsi }}</label>
                                    <div class="col-lg-10">
                                        <input type="checkbox" class="form-check-input form-check-input-switch" name="perm_nama[{{ $r->perm_nama }}]" id="perm_nama" data-on-text="YA" data-off-text="TIDAK" data-on-color="primary" data-off-color="default">
                                    </div>
                                </div>
                            @endforeach

                            <div class="form-group row">
                                <label class="col-form-label col-lg-2"></label>
                                <div class="col-lg-10">
                                    <button class="btn btn-success btn-labeled btn-labeled-left" type="button" onclick="simpan_menu()">
                                        <b><i class="icon-floppy-disk"></i></b>Simpan
                                    </button>
                                    <a href="{{ route('menu-manager.index') }}" class="btn btn-danger btn-labeled btn-labeled-left"><b>
                                        <i class="icon-cross"></i></b>Cancel
                                    </a>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="modal_full" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Icons</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <ul class="nav nav-tabs nav-tabs-highlight mb-0">
                        <li class="nav-item"><a href="#icomoon" class="nav-link active" data-toggle="tab">Icomoon</a></li>
                        <li class="nav-item"><a href="#fontawesome" class="nav-link" data-toggle="tab">Font Awesome</a></li>
                        <li class="nav-item"><a href="#material" class="nav-link" data-toggle="tab">Material</a></li>
                    </ul>

                    <div class="tab-content card card-body border-light border-top-0 rounded-top-0 shadow-0 mb-0">
                        <div class="tab-pane fade show active" id="icomoon">
                            @include('modules.menu-manager.icons.icomoon')
                        </div>

                        <div class="tab-pane fade" id="fontawesome">
                            @include('modules.menu-manager.icons.fontawesome')
                        </div>

                        <div class="tab-pane fade" id="material">
                            @include('modules.menu-manager.icons.material')
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_js')
<script>
    sidebar_collapsed();
    $('.ico').dblclick(function(){
        var icon = $(this).attr('data-put');
        $('#menu_icon').val(icon);
        $('#modal_full').modal('hide');
    });

    $('#menu_div_id').on('change', function(){
        list_menu_divider($(this).val());
    });

    reset_form_divider();
    reload_divider_options();
    var table = build_datatables( 
        "table_divider", 
        "{{ route('core.list_divider') }}", 
        "{{ csrf_token() }}",
        [-1]
    );

    function simpan_divider(){
        var act = $('#form_divider #action_method').val();
        var postUrl;
        if(act == 'save'){
            postUrl = "{{ route('save_divider') }}";
        }else{
            postUrl = "{{ route('update_divider') }}";
        }
        $.ajax({
            url: postUrl,
            type: "POST",
            data: $('#form_divider').serialize(),
            beforeSend: function(){
                small_loader_open('section_divider');
            },
            success: function(s){
                reset_form_divider();
                sw_success(s);
                reload_table(table);
                reload_divider_options();
            },
            error: function(e){
                sw_multi_error(e);
            },
            complete: function(){
                small_loader_close('section_divider');
            }
        });
    }

    function simpan_menu(){
        $.ajax({
            type: "POST",
            url: "{{ route('menu-manager.store') }}",
            data: $('#form_menu').serialize(),
            beforeSend: function(){
                small_loader_open('section_menu');
            },
            success: function (s) {
                sw_success_redirect(s, "{{ route('menu-manager.create') }}");
            },
            error: function(e){
                if(e.status == 422){
                    sw_multi_error(e);
                }else{
                    sw_single_error(e.responseJSON);
                }
            },
            complete: function(){
                small_loader_close('section_menu');
            }
        });
    }

    function reset_form_divider(){
        $('#form_divider')[0].reset();
        $('#form_divider #action_method').val('save');
        $('#form_divider #div_nama_ina').focus();
    }

    function reload_divider_options(){
        $.ajax({
            type: "GET",
            dataType: "JSON",
            url: "{{ route('core.options_divider') }}",
            beforeSend: function(){
                small_loader_open('menu_div_id');
            },
            success: function(s){
                $('#menu_div_id').html(s);
            },
            complete: function(){
                small_loader_close('menu_div_id');
            }
        });
    }

    function list_menu_divider(i){
        $.ajax({
            type: "POST",
            data: {
                _token : "{{ csrf_token() }}",
                div_id : i
            },
            url: "{{ route('core.menu_divider') }}",
            beforeSend: function(){
                small_loader_open('menu_parent_id');
            },
            success: function(s){
                $('#menu_parent_id').html(s);
            },
            complete: function(){
                small_loader_close('menu_parent_id');
            }
        });
    }

    function edit_divider(i){
        $.ajax({
            url: "{{ route('core.read_divider') }}",
            type: "POST",
            data: {
                _token : "{{ csrf_token() }}",
                id : i
            },
            beforeSend: function(){
                big_loader_open('section_divider');
            },
            success: function(s){
                reset_form_divider();
                $('#form_divider #action_method').val('edit');
                $('#form_divider #div_id').val(s.div_id);
                $('#form_divider #div_nama_ina').val(s.div_nama_ina);
                $('#form_divider #div_nama_eng').val(s.div_nama_eng);
                $('#form_divider #div_order').val(s.div_order);
                if(s.div_publish_ke_user == 'Y'){
                    $('#form_divider #div_publish_ke_user').prop('checked', true);
                }else{
                    $('#form_divider #div_publish_ke_user').prop('checked', false);
                }
            },
            complete: function(){
                big_loader_close('section_divider');
            }
        });
    }

    function preaction(i){
        sw_delete(
            "{{ route('predelete_divider') }}",
            "{{ csrf_token() }}",
            i,
            "{{ route('delete_divider') }}",
            "{{ csrf_token() }}",
            "section_divider",
            table
        );
    }

    function order_up(id){
        $.ajax({
            url: "{{ route('menu-manager.set_order_divider') }}",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                "div_id": id,
                "move": "up"
            },
            beforeSend: function(){
                small_loader_open('table_menu');
            },
            success: function(s){
                reload_table(table);
            },
            complete: function(){
                small_loader_close('table_menu');
            }
        });
    }

    function order_down(id){
        $.ajax({
            url: "{{ route('menu-manager.set_order_divider') }}",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                "div_id": id,
                "move": "down"
            },
            beforeSend: function(){
                small_loader_open('table_menu');
            },
            success: function(s){
                reload_table(table);
            },
            complete: function(){
                small_loader_close('table_menu');
            }
        });
    }
</script>
@endsection