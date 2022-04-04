@extends('layouts.app')
@section('header')
    {{ $title }}
@endsection
@section('content')
<form id="form_data">
    @csrf
    <div class="card">
        <div class="card-header bg-light-1 border-bottom border-light header-elements-inline">
            <h5 class="card-title">{{ $title }}</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>

        @include('modules.pricing_generator.edit_basic_data')
    </div>

    <div class="card">
        <div class="card-header bg-light-1 border-bottom border-light header-elements-inline">
            <h5 class="card-title">Packaging</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>

        <div class="collapse show">
            <div class="card-body" id="div_pack">
                <div class="row">
                    <div class="col-lg-12">

                        <fieldset class="mb-3">
                            <legend class="font-weight-semibold"><h2><i class="icon-package icon-2x mr-2"></i> DEFINISIKAN DENGAN OPTIMAL 🤙</h2></legend>

                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <button class="btn btn-teal btn-labeled btn-labeled-left" id="btn_packing" type="button" onclick="add_packing()">
                                        <b><i class="icon-plus3"></i></b>Tambah Packing
                                    </button>
                                </div>
                            </div>
                            
                            <div id="packing">
                                @foreach ($pricing_packs as $i => $v)
                                    <div class="form-group row rowpack" id="idpack{{ $i }}"><div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <select name="pack_id[]" id="pack_id_{{ $i }}" class="form-control select">
                                                    <option value="">-- Pilih Packaging --</option>
                                                    @foreach($packs as $p)
                                                        <option value="{{ $p->pack_id }}" {{ ($p->pack_id === $v->pack_id) ? 'selected' : '' }}>{{ $p->pack_label }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="form-text text-muted">Pilih jenisnya apa</span>
                                            </div>
                                            <div class="col-lg-2">
                                                <input type="text" name="pack_size[]" id="pack_size_{{ $i }}" class="form-control" value="{{ $v->pack_size }}">
                                                <span class="form-text text-muted">Ukuran berapa? (diisi angka saja dalam satuan "Kg")</span>
                                            </div>
                                            <div class="col-lg-2">
                                                <input type="text" name="pack_harga[]" id="pack_harga_{{ $i }}" class="form-control" onkeyup="calculate({{ $i }})" value="{{ $v->pack_harga }}">
                                                <span class="form-text text-muted">Harganya berapa?</span>
                                            </div>
                                            <div class="col-lg-2">
                                                <input type="text" name="pack_qty[]" id="pack_qty_{{ $i }}" class="form-control" readonly  value="{{ $v->pack_qty }}">
                                                <span class="form-text text-muted">Quantity packaging (Diambil dari Max. Load Container : Pack Size)</span>
                                            </div>
                                            <div class="col-lg-2">
                                                <input type="text" name="pack_cost_idr[]" id="pack_cost_idr_{{ $i }}" class="form-control" readonly  value="{{ number_format($v->pack_cost_idr) }}">
                                                <span class="form-text text-muted">Cost dalam IDR (Quantity x Harga)</span>
                                            </div>
                                            <div class="col-lg-1 text-center">
                                                <button type="button" class="btn btn-outline-danger btn-icon rounded-pill" onclick="remove_packing({{ $i }})"><i class="icon-trash"></i></button>
                                            </div>
                                        </div>
                                    </div></div>
                                @endforeach
                            </div>                            
                            
                        </fieldset>

                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('modules.pricing_generator.edit_komponen_biaya')
</form>

<div id="preview"></div>
@endsection

@section('page_resources')
	<script src="{{ asset('assets/global/js/plugins/forms/inputs/typeahead/handlebars.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/plugins/forms/inputs/alpaca/alpaca.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/plugins/forms/inputs/alpaca/price_format.min.js') }}"></script>
@endsection

@section('page_js')
<script>
    sidebar_collapsed();
    load_komoditas();
    $('#div_prc_harga_supplier').alpaca({
        schema: {
            default: {{ $pricing->prc_harga_supplier }}
        },
        options: {
            id: 'prc_harga_supplier',
            name: 'prc_harga_supplier',
            type: 'currency',
            focus: false,
            prefix: '',
            centsLimit: 0,
        },
    });

    $('#div_prc_kurs').alpaca({
        schema: {
            default: {{ $pricing->prc_kurs }}
        },
        options: {
            id: 'prc_kurs',
            name: 'prc_kurs',
            type: 'currency',
            focus: false,
            prefix: '',
            centsLimit: 0,
        }
    });

    $('#div_prc_container_max_qty').alpaca({
        schema: {
            default: {{ $pricing->prc_container_max_qty }}
        },
        options: {
            id: 'prc_container_max_qty',
            name: 'prc_container_max_qty',
            type: 'currency',
            focus: false,
            prefix: '',
            centsLimit: 0,
        }
    });

    function load_komoditas(){
        if ($('#supplier_select').val() != ''){
            var sellected_supplier = $('#supplier_select').val().split('_');
            var supplier_id = sellected_supplier[0];
            var supplier_komoditas = sellected_supplier[1];
            $.ajax({
                type: "POST",
                url: "{{ route('tools.pricing_generator.supplier_komoditas') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "komoditas": supplier_komoditas
                },
                beforeSend: function(){
                    $('#prc_komoditas_id').html('<option value="">-- Pilih Komoditas --</option>');
                    // small_loader_open('prc_komoditas_id');
                },
                success: function (s) {
                    $('#prc_komoditas_id').html(s)
                    $('#prc_supplier_id').val(supplier_id);
                    $('#prc_komoditas_id').val({{ $pricing->prc_komoditas_id }});
                    $('#prc_margin_persentase').keyup();
                },
                error: function(e){
                    sw_multi_error(e);
                },
                complete: function(){
                    small_loader_close('prc_komoditas_id');
                    // generate();
                }
            });
        }else{
            $('#prc_supplier_id').val('');
            $('#prc_komoditas_id').html('<option value="">-- Pilih Komoditas --</option>');
        }
    }

    function add_packing(){
        var counter = $('.rowpack').length;
        big_loader_open('div_pack');
        $('#packing').append('<div class="form-group row rowpack" id="idpack'+counter+'"><div class="col-lg-12">\
                                    <div class="row">\
                                        <div class="col-lg-3">\
                                            <select name="pack_id[]" id="pack_id_'+counter+'" class="form-control select">\
                                                <option value="">-- Pilih Packaging --</option>\
                                                @foreach($packs as $p)\
                                                    <option value="{{ $p->pack_id }}">{{ $p->pack_label }}</option>\
                                                @endforeach\
                                            </select>\
                                            <span class="form-text text-muted">Pilih jenisnya apa</span>\
                                        </div>\
                                        <div class="col-lg-2">\
                                            <input type="text" name="pack_size[]" id="pack_size_'+counter+'" class="form-control">\
                                            <span class="form-text text-muted">Ukuran berapa? (diisi angka saja dalam satuan "Kg")</span>\
                                        </div>\
                                        <div class="col-lg-2">\
                                            <input type="text" name="pack_harga[]" id="pack_harga_'+counter+'" class="form-control" onkeyup="calculate('+counter+')">\
                                            <span class="form-text text-muted">Harganya berapa?</span>\
                                        </div>\
                                        <div class="col-lg-2">\
                                            <input type="text" name="pack_qty[]" id="pack_qty_'+counter+'" class="form-control" readonly>\
                                            <span class="form-text text-muted">Quantity packaging (Diambil dari Max. Load Container : Pack Size)</span>\
                                        </div>\
                                        <div class="col-lg-2">\
                                            <input type="text" name="pack_cost_idr[]" id="pack_cost_idr_'+counter+'" class="form-control" readonly>\
                                            <span class="form-text text-muted">Cost dalam IDR (Quantity x Harga)</span>\
                                        </div>\
                                        <div class="col-lg-1 text-center">\
                                            <button type="button" class="btn btn-outline-danger btn-icon rounded-pill" onclick="remove_packing('+counter+')"><i class="icon-trash"></i></button>\
                                        </div>\
                                    </div>\
                                </div></div>');
        big_loader_close('div_pack');
    }

    function calculate(i){
        let max_load = parseInt($('#prc_container_max_qty').val().replace(',', ''));
        let ukuran_pack = $('#pack_size_'+i).val();
        let harga = $('#pack_harga_'+i).val();
        let qty_packing = max_load / ukuran_pack;
        let cost_idr = harga * qty_packing;

        $('#pack_qty_'+i).val(qty_packing.toLocaleString());
        $('#pack_cost_idr_'+i).val(cost_idr.toLocaleString());
    }

    function remove_packing(i){
        big_loader_open('div_pack');
        $('#idpack'+i).remove();
        big_loader_close('div_pack');
    }

    function generate(){
        $.ajax({
            type: "POST",
            url: "{{ route('tools.pricing_generator.generate') }}",
            data: $('#form_data').serialize(),
            beforeSend: function(){
                big_loader_open('form_data');
            },
            success: function (s) {
                $('#preview').html(s);
            },
            error: function(e){
                sw_multi_error(e);
            },
            complete: function(){
                big_loader_close('form_data');
                $('html, body').animate({
                    scrollTop: $("#form_final").offset().top
                }, 1000);
            }
        });
    }

    function simpan_data(){
        $.ajax({
            type: "PUT",
            url: "{{ route('tools.pricing_generator.update', ':id') }}".replace(':id', {{ $pricing->prc_id }}),
            data: $('#form_final').serialize(),
            beforeSend: function(){
                small_loader_open('form_final');
            },
            success: function (s) {
                sw_success_redirect(s, "{{ route('tools.pricing_generator.index') }}");
            },
            error: function(e){
                sw_multi_error(e);
            },
            complete: function(){
                small_loader_close('form_final');
            }
        });
    }


</script>
@endsection