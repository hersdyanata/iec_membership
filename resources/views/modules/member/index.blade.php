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
                        <a href="{{ route('main.member.create') }}" class="btn btn-purple btn-labeled btn-labeled-left">
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
                            <th width="20%">Col1</th>
                            <th width="10%">Col2</th>
                            <th width="10%">Col3</th>
                            <th width="10%">Col4</th>
                            <th width="10%">Col5</th>
                            <th width="10%">Col6</th>
                            <th width="10%">Col7</th>
                            <th width="10%">Col8</th>
                            <th width="10%">Col9</th>
                            <th width="10%">Col10</th>
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

</script>
@endsection