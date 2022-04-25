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
                                    <a href="#" class="btn btn-success btn-labeled btn-labeled-left">
                                        <b><i class="icon-import"></i></b>Import
                                    </a> 
                                @endif
                            </li>
                            <li class="page-item">
                                @if ( (session('group_id') != 0 && in_array('create', $page_permission)) || session('group_id') == 0 )
                                    <a href="{{ route('masterdata.mst_pelabuhan.create') }}" class="btn btn-purple btn-labeled btn-labeled-left ml-2">
                                        <b><i class="icon-plus3"></i></b>Tambah
                                    </a> 
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
                
                
                <div class="card-body">
                    <div class="form-group">
                        <div class="col-lg-12">
                            <label>Filter by Negara</label>
                            <select name="kode_negara" id="kode_negara" class="form-control select-search" data-fouc>
                                <option value="">Cari Negara</option>
                                @foreach ($negara as $i)
                                    <option value="{{ $i->negara_kode }}">{{ $i->negara_kode.' - '.$i->negara_nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <table class="table datatable-show-all table-hover" id="tableData">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">#</th>
                                <th width="8%">Negara</th> 
                                <th width="30%">Kode Pelabuhan</th>
                                <th width="30%">Nama Pelabuhan</th>
                                <th width="10%">Actions</th>
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
    sidebar_collapsed();

	$('#kode_negara').on('change', function(){
        $('#tableData').DataTable().clear();
        $('#tableData').DataTable().destroy();

		build_datatables_with_params(
            "tableData",
            "{{ route('masterdata.mst_pelabuhan.list') }}", 
            "{{ csrf_token() }}",
            [0,-1],
            this.value
        )
	});

    build_datatables(
        "tableData",
        "{{ route('masterdata.mst_pelabuhan.list') }}", 
        "{{ csrf_token() }}",
        [0,-1],
        $('#kode_negara').val()
    )
</script>
@endsection