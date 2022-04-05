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
                                    <a href="{{ route('tools.pricing_generator.create') }}" class="btn btn-purple btn-labeled btn-labeled-left">
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
                    <table class="table datatable-show-all table-xs table-hover" id="tableData">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">#</th>
                                <th width="15%">Label</th>
                                {{-- <th width="10%">Buyer</th> --}}
                                <th width="10%">Supplier & Komoditas</th>
                                <th width="10%">Container Size</th>
                                <th width="10%">Price</th>
                                <th width="10%">Profit</th>
                                <th width="10%">Tgl Dibuat</th>
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
    sidebar_collapsed();
    var table = build_datatables_select( 
        "tableData", 
        "{{ route('tools.pricing_generator.list') }}", 
        "{{ csrf_token() }}",
        [0,-1]
    );

    function preaction(i){
        sw_delete_validated(
            "{{ route('tools.pricing_generator.show', ':id') }}".replace(':id', i),
            "{{ csrf_token() }}",
            i,
            "{{ route('tools.pricing_generator.destroy', ':id') }}".replace(':id', i),
            "{{ csrf_token() }}",
            "tableData",
            table
        );
    }

    function cetak(){
        var a = [];
        $('#tableData tbody tr.selected').each(function(){
            var pos = table.row(this).index();
            var row = table.row(pos).data();
            a.push(row[8]);
        });

        if(a.length > 0){
            swalInit.fire({
                title: 'Bismillahirahmanirahim 🤲',
                input: 'select',
                inputOptions: {
                    '': '',
                    'fob': 'FOB',
                    'cnf': 'CNF',
                    'cif': 'CIF',
                },
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-light',
                    denyButton: 'btn btn-light',
                    input: 'form-control select-single'
                },
                showCancelButton: true,
                inputValidator: function(value) {
                    return new Promise(function(resolve) {
                        if(value === ''){
                            resolve('Silahkan pilih incoterms :)');
                        }else{
                            resolve();
                        }
                    });
                },
                inputAttributes: {
                    'data-placeholder': 'Pilih Incoterms'
                },
                didOpen: function() {

                    // Initialize Select2
                    $('.swal2-select.select-single').select2({
                        minimumResultsForSearch: Infinity
                    });
                }
            }).then(function(result) {
                if(result.value) {
                    var url = "{{ route('tools.pricing_generator.cetak', [':id', ':terms']) }}".replace(':id', a).replace(':terms', result.value);
                    window.open(url, "_blank");
                }
            });
        }else{
            swalInit.fire({
                title: 'Tetot!',
                text: 'Pilih dulu pricing yang akan dicetak.',
                icon: 'error',
                allowOutsideClick: false,
                customClass: {
                    confirmButton: 'btn btn-primary',
                },
            });
        }

    }

    function copy(i){
        $.ajax({
            url: "{{ route('tools.pricing_generator.pra_copy', ':id') }}".replace(':id', i),
            type: "GET",
            data: {
                _token : "{{ csrf_token() }}",
                id : i
            },
            beforeSend: function(){
                small_loader_open('tableData');
            },
            success: function(s){
                swalInit.fire({
                    title: s.msg_title,
                    html: s.msg_body,
                    type: 'info',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Iya, tolong buat duplikat!',
                    cancelButtonText: 'Tidak, tolong batalkan!',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    allowOutsideClick: false
                }).then(function(result) {
                    if(result.value) {

                        $.ajax({
                            url: "{{ route('tools.pricing_generator.copy') }}",
                            type: "POST",
                            data: {
                                _token : "{{ csrf_token() }}",
                                id : i
                            },
                            beforeSend: function(){
                                small_loader_open('tableData');
                            },
                            success: function(d){
                                swalInit.fire({
                                    title: d.msg_title,
                                    html: d.msg_body,
                                    type: 'success',
                                    icon: 'success',
                                    confirmButtonClass: 'btn btn-success',
                                });
                                reload_table(table);
                            },
                            complete: function(){
                                small_loader_close('tableData');
                            }
                        });
                    }
                    else if(result.dismiss === swal.DismissReason.cancel) {
                        swalInit.fire({
                            title: 'Dibatalkan',
                            html: 'Oke! Duplikasi digagalkan 😉',
                            type: 'success',
                            icon: 'success',
                            confirmButtonClass: 'btn btn-success',
                            allowOutsideClick: false
                        });
                        small_loader_close('tableData');
                    }
                });
            },
            complete: function(){
                small_loader_close('section_divider');
            }
        });
    }
</script>
@endsection