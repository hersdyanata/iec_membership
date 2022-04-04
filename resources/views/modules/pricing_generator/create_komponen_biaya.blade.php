<div class="card">
    <div class="card-header bg-light-1 border-bottom border-light header-elements-inline">
        <h5 class="card-title">Komponen Biaya</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
            </div>
        </div>
    </div>

    <div class="collapse show">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <fieldset>
                        <legend class="font-weight-semibold"><h2><i class="icon-coin-dollar icon-2x mr-2"></i> FIXED VALUE</h2></legend>
                        
                        <div class="row">
                            @foreach ($komponen_fix as $r)
                                <div class="col-lg-6">
                                    <input type="hidden" name="prkomp_komponen_nama[{{ $r->komp_id }}]" id="prkomp_komponen_nama_{{ $r->komp_id }}" value="{{ $r->komp_nama }}" readonly>
                                    <input type="hidden" name="prkomp_komponen_id[{{ $r->komp_id }}]" id="prkomp_komponen_id_{{ $r->komp_id }}" value="{{ $r->komp_id }}" readonly>
                                    <input type="hidden" name="prkomp_incoterms[{{ $r->komp_id }}]" id="prkomp_incoterms{{ $r->komp_id }}" value="{{ $r->komp_incoterms }}" readonly>
                                    <input type="hidden" name="prkomp_komponen_type[{{ $r->komp_id }}]" id="prkomp_komponen_type_{{ $r->komp_id }}" value="fixed" readonly>
                                    <div class="form-group">
                                        <label>{{ $r->komp_nama.' - '.Str::upper($r->komp_incoterms) }}</label>
                                        <div id="div_komponen_{{ $r->komp_id }}"></div>
                                    </div>
                                </div>

                                <script>
                                    $('#div_komponen_{{ $r->komp_id }}').alpaca({
                                        schema: {
                                            default: 0,
                                        },
                                        options: {
                                            id: 'prkomp_nilai_fixed_{{ $r->komp_id }}',
                                            name: 'prkomp_nilai_fixed[{{ $r->komp_id }}]',
                                            type: 'currency',
                                            focus: false,
                                            prefix: '',
                                            centsLimit: 0,
                                        }
                                    });
                                </script>
                            @endforeach
                        </div>
                    </fieldset>
                </div>

                <div class="col-lg-6">
                    <fieldset>
                        <legend class="font-weight-semibold"><h2><i class="icon-percent icon-2x mr-2"></i> VARIABLE VALUE</h2></legend>
                        @foreach ($komponen_persentase as $r)
                            <div class="row">
                                <div class="col-lg-12">
                                    <input type="hidden" name="prkomp_komponen_nama[{{ $r->komp_id }}]" id="prkomp_komponen_nama_{{ $r->komp_id }}" value="{{ $r->komp_nama }}" readonly>
                                    <input type="hidden" name="prkomp_komponen_id[{{ $r->komp_id }}]" id="prkomp_komponen_id_{{ $r->komp_id }}" value="{{ $r->komp_id }}" readonly>
                                    <input type="hidden" name="prkomp_incoterms[{{ $r->komp_id }}]" id="prkomp_incoterms{{ $r->komp_id }}" value="{{ $r->komp_incoterms }}" readonly>
                                    <input type="hidden" name="prkomp_komponen_type[{{ $r->komp_id }}]" id="prkomp_komponen_type_{{ $r->komp_id }}" value="persentase" readonly>
                                    <div class="form-group">
                                        <label>{{ $r->komp_nama.' - '.Str::upper($r->komp_incoterms) }}</label>
                                        <input type="text" value="0" class="form-control" name="prkomp_persentase[{{ $r->komp_id }}]" id="prkomp_persentase_{{ $r->komp_id }}">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </fieldset>
                </div>
            </div><br>

            <hr>
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <button class="btn btn-purple btn-lg btn-labeled btn-labeled-left" type="button" onclick="generate()">
                            <b><i class="icon-magic-wand"></i></b>Abra Kadabra!
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>