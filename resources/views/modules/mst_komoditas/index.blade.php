@extends('layouts.app')
@section('header')
    {{ $title }}
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header bg-transparent border-bottom header-elements-sm-inline py-sm-0">
                    <h5 class="card-title py-sm-3">{{ $title }}</h5>
                    <div class="header-elements">
                        <ul class="pagination pagination-pager justify-content-between">
                            <li class="page-item">
                                @if ( (session('group_id') != 0 && in_array('create', $page_permission)) || session('group_id') == 0 )
                                    <a href="{{ route('masterdata.mst_komoditas.create') }}" class="btn btn-purple btn-labeled btn-labeled-left">
                                        <b><i class="icon-plus3"></i></b>Tambah
                                    </a> 
                                @endif
                            </li>
                            <li class="page-item">
                                <button type="button" onclick="reload_table(table)" class="btn btn-warning btn-labeled btn-labeled-left ml-2">
                                    <b><i class="icon-reload-alt"></i></b>Reload
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="card-body">
                    <table class="table datatable-show-all table-hover" id="tableData">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">#</th>
                                <th width="20%">Nama</th>
                                <th width="10%">Prefix</th>
                                <th width="20%">Spesifikasi</th>
                                <th width="10%" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>
@endsection

@section('page_js')
<script>
    var table = build_datatables( 
        "tableData", 
        "{{ route('masterdata.mst_komoditas.list') }}", 
        "{{ csrf_token() }}",
        [0,-1]
    );

    function preaction(i){
        sw_delete_validated(
            "{{ route('masterdata.mst_komoditas.show', ':id') }}".replace(':id', i),
            "{{ csrf_token() }}",
            i,
            "{{ route('masterdata.mst_komoditas.destroy', ':id') }}".replace(':id', i),
            "{{ csrf_token() }}",
            "tableData",
            table
        );
    }
</script>
@endsection