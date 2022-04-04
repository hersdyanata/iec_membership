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
                        <a href="{{ route('masterdata.mst_supplier.create') }}" class="btn btn-purple btn-labeled btn-labeled-left">
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
                            <th width="10%">Nama</th>
                            <th width="20%">Alamat</th>
                            <th width="30%">Komoditas</th>
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
        "{{ route('masterdata.mst_supplier.list') }}", 
        "{{ csrf_token() }}",
        [0,-1]
    );

    function preaction(i){
        sw_delete_validated(
            "{{ route('masterdata.mst_supplier.show', ':id') }}".replace(':id', i),
            "{{ csrf_token() }}",
            i,
            "{{ route('masterdata.mst_supplier.destroy', ':id') }}".replace(':id', i),
            "{{ csrf_token() }}",
            "tableData",
            table
        );
    }
</script>
@endsection