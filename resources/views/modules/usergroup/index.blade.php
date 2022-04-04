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
                    @if ( (session('group_id') != 0 && in_array('create', $page_permission)) || session('group_id') == 0 )
                        <a href="{{ route('usergroup.create') }}" class="btn btn-purple btn-labeled btn-labeled-left">
                            <b><i class="icon-plus3"></i></b>Tambah
                        </a>
                    @endif
                    <button type="button" onclick="reload_table(table)" class="btn btn-warning btn-labeled btn-labeled-left">
                        <b><i class="icon-reload-alt"></i></b>Reload
                    </button>
                </div>

                <table class="table datatable-show-all table-hover" id="tableData">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">#</th>
                            <th width="10%">Group</th>
                            <th width="10%">Default Menu</th>
                            <th width="15%">Deskripsi</th>
                            <th width="35%">Akses Module</th>
                            <th width="10%" class="text-center">Actions</th>
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
    var table = build_datatables( 
        "tableData", 
        "{{ route('usergorup.list') }}", 
        "{{ csrf_token() }}",
        [0,4,-1]
    );

    function preaction(i){
        sw_delete(
            "{{ route('usergroup.predelete') }}",
            "{{ csrf_token() }}",
            i,
            "{{ route('usergroup.delete') }}",
            "{{ csrf_token() }}",
            "tableData",
            table
        );
    }
</script>
@endsection