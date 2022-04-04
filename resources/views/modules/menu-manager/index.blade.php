@extends('layouts.app')
@section('header')
    {{ $title }}
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">{{ $title }}</h5>
                </div>

                <div class="card-body">
                    <a href="{{ route('menu-manager.create') }}" class="btn btn-purple btn-labeled btn-labeled-left"><b>
                        <i class="icon-plus3"></i></b>Buat Menu Baru
                    </a>

                    <button type="button" onclick="reload_table(table)" class="btn btn-warning btn-labeled btn-labeled-left">
                        <b><i class="icon-reload-alt"></i></b>Reload
                    </button>
                </div>

                <table class="table datatable-show-all table-hover" id="table_menu">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Divider</th>
                            <th>Nama Menu (INA)</th>
                            {{-- <th>Nama Menu (ENG)</th> --}}
                            <th class="text-center">Order</th>
                            <th>Parent</th>
                            <th>Controller</th>
                            <th>Route Name</th>
                            <th class="text-center">Permissions</th>
                            <th class="text-center">Icon</th>
                            <th class="text-center">Publish</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </div>
    </div>
@endsection

@section('page_js')
<script>
    sidebar_collapsed();
    var table = build_datatables( 
        "table_menu", 
        "{{ route('menu-manager.list_menu') }}", 
        "{{ csrf_token() }}",
        [0,-1]
    );

    function preaction(i){
        sw_delete(
            "{{ route('menu-manager.predelete') }}",
            "{{ csrf_token() }}",
            i,
            "{{ route('menu-manager.delete') }}",
            "{{ csrf_token() }}",
            "table_menu",
            table
        );
    }

    function order_up(id){
        $.ajax({
            url: "{{ route('menu-manager.set_order_menu') }}",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                "menu_id": id,
                "move": "up"
            },
            beforeSend: function(){
                small_loader_open('table_menu');
            },
            success: function(s){
                reload_table(table);
            },
            error: function(e){
                sw_multi_error(e);
            },
            complete: function(){
                small_loader_close('table_menu');
            }
        });
    }

    function order_down(id){
        $.ajax({
            url: "{{ route('menu-manager.set_order_menu') }}",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                "menu_id": id,
                "move": "down"
            },
            beforeSend: function(){
                small_loader_open('table_menu');
            },
            success: function(s){
                reload_table(table);
            },
            error: function(e){
                sw_multi_error(e);
            },
            complete: function(){
                small_loader_close('table_menu');
            }
        });
    }
</script>
@endsection