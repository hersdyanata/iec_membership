<div class="collapse show">
    <div class="card-body">
        <h3 class="font-weight-bold">BASIC DATA</h3>
        <div class="row">
            <div class="col-lg-6">
                <fieldset>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Label</label>
                                <input type="text" class="form-control" name="prc_label" id="prc_label" value="{{ $pricing->prc_label }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Supplier</label>
                        <select id="supplier_select" name="prc_supplier_id" class="form-control select-search" data-fouc onchange="load_komoditas()">
                            <option value="">-- Pilih Supplier --</option>
                            @foreach ($supplier as $s)
                                <option value="{{ $s->supplier_id }}_{{ $s->supplier_komoditas }}" {{ ($pricing->prc_supplier_id === $s->supplier_id) ? 'selected' : '' }}>{{ $s->supplier_nama.' - '.$s->supplier_alamat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Komoditas</label>
                        <input type="hidden" class="form-control" name="prc_supplier_id" id="prc_supplier_id" readonly>
                        <select name="prc_komoditas_id" id="prc_komoditas_id" class="form-control select" data-fouc>
                            <option value="">-- Pilih Komoditas --</option>
                        </select>
                    </div>

                    {{-- <div class="form-group">
                        <label>Buyer</label>
                        <select name="prc_buyer_id" id="prc_buyer_id" class="form-control select" data-fouc>
                            <option value="">-- Pilih Buyer --</option>
                            @foreach ($buyer as $b)
                                <option value="{{ $b->buyer_id }}" {{ ($pricing->prc_buyer_id === $b->buyer_id) ? 'selected' : '' }}>{{ $b->buyer_pic.' - '.$b->buyer_perusahaan }}</option>
                            @endforeach
                        </select>
                    </div> --}}

                    <div class="form-group">
                        <label>Harga Supplier</label>
                        <div id="div_prc_harga_supplier"></div>
                    </div>
                </fieldset>
            </div>

            <div class="col-lg-6">
                <fieldset>
                    <div class="form-group">
                        <label>Currency</label>
                        <div id="div_prc_kurs"></div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Container Size</label>
                                <select name="prc_container_size" id="prc_container_size" class="form-control select" data-fouc>
                                    <option value="">-- Pilih Container --</option>
                                    @foreach ($containers as $c)
                                        <option value="{{ $c['container_size'] }}" {{ ($c['container_size'] === $pricing->prc_container_size ? 'selected' : '') }}>{{ $c['container_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Maximum Load Container (kg)</label>
                                <div id="div_prc_container_max_qty"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Quantity Container</label>
                                <input type="text" class="form-control" name="prc_container_qty" id="prc_container_qty" value="{{ $pricing->prc_container_qty }}">
                            </div>
                        </div>

                        <script>
                            if ($('#prc_container_qty') != ''){
                                $('#prc_container_qty').on('keyup', function(){
                                    var total_qty = parseInt($('#prc_container_max_qty').val().replace(",","") * $('#prc_container_qty').val());
                                    $('#prc_total_qty').val(total_qty.toLocaleString());
                                });

                                $('#prc_container_qty').on('change', function(){
                                    var packing = $('.rowpack').length;
                                    if(packing > 0){
                                        // $.each( packing, function( key, value ) {
                                        //     console.log( key + ": " + value );
                                        // });
                                        $('.psize').focus();
                                        $('.pprice').focus();
                                        console.log(packing);
                                    }
                                });
                            }
                        </script>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Total Qty (kg)</label>
                                <input type="text" class="form-control" name="prc_total_qty" id="prc_total_qty" value="{{ number_format($pricing->prc_total_qty) }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Margin /kg (Dalam Persentase)</label>
                                <input type="text" class="form-control" name="prc_margin_persentase" id="prc_margin_persentase" value="{{ $pricing->prc_margin_persentase }}">
                                <span class="form-text text-muted">Isi Tanpa Simbol Persen</span>
                            </div>
                        </div>

                        <script>
                            if ($('#prc_margin_persentase') != ''){
                                $('#prc_margin_persentase').on('keyup', function(){                                
                                    var margin_persentase = $(this).val() / 100;
                                    var margin_idr = parseInt($('#prc_harga_supplier').val().replace(",","") * margin_persentase);
                                    $('#prc_margin_idr').val(margin_idr);
                                });
                            }
                        </script>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Margin /kg (Konversi IDR)</label>
                                <input type="text" class="form-control" name="prc_margin_idr" id="prc_margin_idr">
                                <span class="form-text text-muted">Terisi Otomatis (Harga Supplier * Persentase Margin)</span>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</div>