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
                </div>
                
                <div class="card-body">
                    <div class="form-group">
                        <div class="col-lg-12">
                            <label>Filter by HS Code Induk</label>
                            <select name="parent_code" id="parent_code" class="form-control select-search" data-fouc>
                                <option value="">Cari HS Code Induk</option>
                                @foreach ($hs_parent as $i)
                                    <option value="{{ $i->hsp_code }}">{{ $i->hsp_code.' - '.$i->hsp_desc_ina }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <table class="table datatable-show-all table-hover" id="tableData">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">#</th>
                                <th width="8%">HS Code</th>
                                {{-- <th width="8%">Induk</th> --}}
                                <th width="30%">Desc (INA)</th>
                                <th width="30%">Desc (ENG)</th>
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

	$('#parent_code').on('change', function(){
        $('#tableData').DataTable().clear();
        $('#tableData').DataTable().destroy();

		build_datatables_with_params(
            "tableData",
            "{{ route('masterdata.mst_komoditas.list') }}", 
            "{{ csrf_token() }}",
            [0,-1],
            this.value
        )
	});

    build_datatables(
        "tableData",
        "{{ route('masterdata.mst_komoditas.list') }}", 
        "{{ csrf_token() }}",
        [0,-1],
        $('#parent_code').val()
    )

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