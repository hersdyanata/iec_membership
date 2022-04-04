<form id="form_final">
    @csrf
    <div class="card">
        <div class="card-header bg-light-1 border-bottom border-light header-elements-inline">
            <h5 class="card-title">Preview Final Pricing</h5>
            <div class="header-elements">
            </div>
        </div>

        <div class="card-body">
            <div id="data_header">
                <input type="hidden" name="header[prc_kode]" id="prc_kode" value="{{ $pricing_kode }}" readonly>
                <input type="hidden" name="header[prc_label]" id="prc_label" value="{{ $req['prc_label'] }}" readonly>
                <input type="hidden" name="header[prc_supplier_id]" id="prc_supplier_id" value="{{ $req['prc_supplier_id'] }}" readonly>
                <input type="hidden" name="header[prc_komoditas_id]" id="prc_komoditas_id" value="{{ $req['prc_komoditas_id'] }}" readonly>
                
                <input type="hidden" name="header[prc_harga_supplier]" id="prc_harga_supplier" value="{{ str_replace(',', '', $req['prc_harga_supplier']) }}" readonly>
                <input type="hidden" name="header[prc_kurs]" id="prc_kurs" value="{{ str_replace(',', '', $req['prc_kurs']) }}" readonly>
                <input type="hidden" name="header[prc_container_size]" id="prc_container_size" value="{{ $req['prc_container_size'] }}" readonly>
                <input type="hidden" name="header[prc_container_max_qty]" id="prc_container_max_qty" value="{{ str_replace(',', '', $req['prc_container_max_qty']) }}" readonly>
                <input type="hidden" name="header[prc_container_qty]" id="prc_container_qty" value="{{ $req['prc_container_qty'] }}" readonly>
                <input type="hidden" name="header[prc_total_qty]" id="prc_total_qty" value="{{ str_replace(',', '', $req['prc_total_qty']) }}" readonly>
                <input type="hidden" name="header[prc_margin_persentase]" id="prc_margin_persentase" value="{{ str_replace(',', '', $req['prc_margin_persentase']) }}" readonly>
                <input type="hidden" name="header[prc_margin_idr]" id="prc_margin_idr" value="{{ str_replace(',', '', $req['prc_margin_idr']) }}" readonly>
                <input type="hidden" name="header[prc_cost_produk]" id="prc_cost_produk" value="{{ $biaya_produk }}" readonly>
                <input type="hidden" name="header[prc_profit]" id="prc_profit" value="{{ $profit }}" readonly>
                <input type="hidden" name="header[prc_createdby]" id="prc_createdby" value="{{ session('user_id') }}" readonly>
                <input type="hidden" name="header[prc_createdat]" id="prc_createdat" value="{{ date('Y-m-d H:i:s') }}" readonly>
            </div>

            <div id="data_packing">
                <input type="hidden" name="packing_detail" id="packing_detail" value="{{ $json_packing }}" readonly>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <table class="table table-bordered datatable-show-all table-hover">
                        <thead>
                            <tr>
                                <th colspan="6" class="text-center font-weight-bold bg-primary-100">
                                    <h1 class="font-weight-bold">FOB</h1>
                                </th>
                            </tr>

                            @php
                                $total_cost_fob = 0;
                            @endphp
                            @foreach ($komponen_biaya['fob']->where('prkomp_nilai_fixed', '>', 0) as $r)
                                @php
                                    $komponen_id = explode(' - ', $r['prkomp_komponen_id']);
                                    $total_cost_fob += $r['prkomp_nilai_fixed'];
                                @endphp

                                <tr>
                                    <th colspan="2" width="10%">
                                        <input type="hidden" name="fob[prkomp_komponen_id][{{ $komponen_id[0] }}]" id="prkomp_komponen_id" value="{{ $komponen_id[0] }}" readonly>
                                        <input type="hidden" name="fob[prkomp_komponen_type][{{ $komponen_id[0] }}]" id="prkomp_komponen_type" value="{{ $r['prkomp_komponen_type'] }}" readonly>
                                        <input type="hidden" name="fob[prkomp_incoterms][{{ $komponen_id[0] }}]" id="prkomp_incoterms" value="fob" readonly>
                                        <strong>{{ $komponen_id[1] }}</strong>
                                    </th>
                                    <th colspan="2" width="10%" class="text-right">
                                        <input type="hidden" name="fob[prkomp_persentase][{{ $komponen_id[0] }}]" id="prkomp_persentase" value="{{ $r['prkomp_persentase'] }}" readonly>
                                        <input type="hidden" name="fob[prkomp_nilai_fixed_idr][{{ $komponen_id[0] }}]" id="prkomp_nilai_fixed_idr" value="{{ $r['prkomp_nilai_fixed'] }}" readonly>
                                        IDR {{ number_format($r['prkomp_nilai_fixed']) }} (x{{ $qty_container }})
                                    </th>
                                    <th colspan="2" width="15%" class="text-right">
                                        <input type="hidden" name="fob[prkomp_nilai_fixed_usd][{{ $komponen_id[0] }}]" id="prkomp_nilai_fixed_usd" value="{{ round($r['prkomp_nilai_fixed'] / $kurs) }}" readonly>
                                        $ {{ number_format($r['prkomp_nilai_fixed'] / $kurs) }}
                                    </th>
                                </tr>
                            @endforeach
                            <tr>
                                <th colspan="2" width="10%">
                                    <strong>Cost Produk</strong>
                                </th>
                                <th colspan="2" width="10%" class="text-right">
                                    IDR {{ number_format($biaya_produk) }}
                                </th>
                                <th colspan="2" width="15%" class="text-right">
                                    ${{ number_format($biaya_produk / $kurs) }}
                                </th>
                            </tr>
                            @if ($packing > 0)
                                <tr>
                                    <th colspan="2" width="10%">
                                        <strong>Packaging</strong>
                                    </th>
                                    <th colspan="2" width="10%" class="text-right">
                                        IDR {{ number_format($packing) }}
                                    </th>
                                    <th colspan="2" width="15%" class="text-right">
                                        ${{ number_format($packing / $kurs) }}
                                    </th>
                                </tr>
                            @endif
                            <tr>
                                <th colspan="2" width="10%">
                                    <strong>Profit</strong>
                                </th>
                                <th colspan="2" width="10%" class="text-right">
                                    IDR {{ number_format($profit) }}
                                </th>
                                <th colspan="2" width="15%" class="text-right">
                                    ${{ number_format($profit / $kurs) }}
                                </th>
                            </tr>
                            <tr>
                                <th colspan="2" width="10%">
                                    <strong>Total Tagihan Buyer</strong>
                                </th>
                                <th colspan="2" width="10%" class="text-right">
                                    IDR {{ number_format($total_cost_fob + $biaya_produk + $profit) }}
                                </th>
                                <th colspan="2" width="15%" class="text-right">
                                    ${{ number_format(($total_cost_fob + $biaya_produk + $profit) / $kurs) }}
                                </th>
                            </tr>

                            <tr>
                                <th colspan="2" width="10%" class="text-center">
                                    <strong>Keseluruhan</strong>
                                </th>
                                <th colspan="2" width="10%" class="text-center">
                                    <strong>/Ton</strong>
                                </th>
                                <th colspan="2" width="15%" class="text-center">
                                    <strong>/Kilogram</strong>
                                </th>
                            </tr>
                            <tr>
                                <th class="text-center">
                                    IDR
                                </th>
                                <th class="text-center">
                                    USD
                                </th>
                                <th class="text-center">
                                    IDR
                                </th>
                                <th class="text-center">
                                    USD
                                </th>
                                <th class="text-center">
                                    IDR
                                </th>
                                <th class="text-center">
                                    USD
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="16%" class="text-right">
                                    <input type="hidden" name="final_fob[prdetail_prc_id]" id="prdetail_prc_id" readonly>
                                    <input type="hidden" name="final_fob[prdetail_prc_incoterms]" id="prdetail_prc_incoterms" value="fob" readonly>
                                    <input type="hidden" name="final_fob[prdetail_keseluruhan_idr]" id="prdetail_keseluruhan_idr" value="{{ $tagihan_buyer['fob'] }}" readonly>
                                    {{ number_format($tagihan_buyer['fob']) }}
                                </td>
                                <td width="16%" class="text-right">
                                    <input type="hidden" name="final_fob[prdetail_keseluruhan_usd]" id="prdetail_keseluruhan_usd" value="{{ round($tagihan_buyer['fob'] / $kurs, 2) }}" readonly>
                                    {{ number_format(round($tagihan_buyer['fob'] / $kurs, 2)) }}
                                </td>

                                <td width="16%" class="text-right">
                                    <input type="hidden" name="final_fob[prdetail_ton_idr]" id="prdetail_ton_idr" value="{{ round($harga_ton['fob']) }}" readonly>
                                    {{ number_format(round($harga_ton['fob'])) }}
                                </td>
                                <td width="16%" class="text-right">
                                    <input type="hidden" name="final_fob[prdetail_ton_usd]" id="prdetail_ton_usd" value="{{ round($harga_ton['fob'] / $kurs) }}" readonly>
                                    {{ number_format(round($harga_ton['fob'] / $kurs, 2)) }}
                                </td>

                                <td width="16%" class="text-right">
                                    <input type="hidden" name="final_fob[prdetail_kilo_idr]" id="prdetail_kilo_idr" value="{{ round($harga_kilo['fob']) }}" readonly>
                                    {{ number_format($harga_kilo['fob']) }}
                                </td>
                                <td width="16%" class="text-right">
                                    <input type="hidden" name="final_fob[prdetail_kilo_usd]" id="prdetail_kilo_usd" value="{{ round($harga_kilo['fob'] / $kurs, 2) }}" readonly>
                                    {{ number_format(round($harga_kilo['fob'] / $kurs, 2), 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-lg-4">
                    <table class="table table-bordered datatable-show-all table-hover">
                        <thead>
                            <tr>
                                <th colspan="6" class="text-center font-weight-bold bg-success-100">
                                    <h1 class="font-weight-bold">CnF</h1>
                                </th>
                            </tr>

                            @php
                                $total_cost_cnf = 0;
                            @endphp
                            @foreach ($komponen_biaya['cnf']->where('prkomp_nilai_fixed', '>', 0) as $r)
                                @php
                                    $komponen_id = explode(' - ', $r['prkomp_komponen_id']);
                                    $total_cost_cnf += $r['prkomp_nilai_fixed'];
                                @endphp
                                <tr>
                                    <th colspan="2" width="10%">
                                        <input type="hidden" name="cnf[prkomp_komponen_id][{{ $komponen_id[0] }}]" id="prkomp_komponen_id" value="{{ $komponen_id[0] }}" readonly>
                                        <input type="hidden" name="cnf[prkomp_komponen_type][{{ $komponen_id[0] }}]" id="prkomp_komponen_type" value="{{ $r['prkomp_komponen_type'] }}" readonly>
                                        <input type="hidden" name="cnf[prkomp_incoterms][{{ $komponen_id[0] }}]" id="prkomp_incoterms" value="cnf" readonly>
                                        <strong>{{ $komponen_id[1] }}</strong>
                                    </th>
                                    <th colspan="2" width="10%" class="text-right">
                                        <input type="hidden" name="cnf[prkomp_persentase][{{ $komponen_id[0] }}]" id="prkomp_persentase" value="{{ $r['prkomp_persentase'] }}" readonly>
                                        <input type="hidden" name="cnf[prkomp_nilai_fixed_idr][{{ $komponen_id[0] }}]" id="prkomp_nilai_fixed_idr" value="{{ $r['prkomp_nilai_fixed'] }}" readonly>
                                        IDR {{ number_format($r['prkomp_nilai_fixed']) }} (x{{ $qty_container }})
                                    </th>
                                    <th colspan="2" width="15%" class="text-right">
                                        <input type="hidden" name="cnf[prkomp_nilai_fixed_usd][{{ $komponen_id[0] }}]" id="prkomp_nilai_fixed_usd" value="{{ round($r['prkomp_nilai_fixed'] / $kurs) }}" readonly>
                                        ${{ number_format($r['prkomp_nilai_fixed'] / $kurs) }}
                                    </th>
                                </tr>
                            @endforeach
                            <tr>
                                <th colspan="2" width="10%">
                                    <strong>Cost Produk</strong>
                                </th>
                                <th colspan="2" width="10%" class="text-right">
                                    IDR {{ number_format($biaya_produk) }}
                                </th>
                                <th colspan="2" width="15%" class="text-right">
                                    ${{ number_format($biaya_produk / $kurs) }}
                                </th>
                            </tr>
                            @if ($packing > 0)
                                <tr>
                                    <th colspan="2" width="10%">
                                        <strong>Packaging</strong>
                                    </th>
                                    <th colspan="2" width="10%" class="text-right">
                                        IDR {{ number_format($packing) }}
                                    </th>
                                    <th colspan="2" width="15%" class="text-right">
                                        ${{ number_format($packing / $kurs) }}
                                    </th>
                                </tr>
                            @endif
                            <tr>
                                <th colspan="2" width="10%">
                                    <strong>Profit</strong>
                                </th>
                                <th colspan="2" width="10%" class="text-right">
                                    IDR {{ number_format($profit) }}
                                </th>
                                <th colspan="2" width="15%" class="text-right">
                                    ${{ number_format($profit / $kurs) }}
                                </th>
                            </tr>
                            <tr>
                                <th colspan="2" width="10%">
                                    <strong>Total Tagihan Buyer</strong>
                                </th>
                                <th colspan="2" width="10%" class="text-right">
                                    IDR {{ number_format($total_cost_cnf + $biaya_produk + $profit) }}
                                </th>
                                <th colspan="2" width="15%" class="text-right">
                                    ${{ number_format(($total_cost_cnf + $biaya_produk + $profit) / $kurs) }}
                                </th>
                            </tr>

                            <tr>
                                <th colspan="2" width="10%" class="text-center">
                                    <strong>Keseluruhan</strong>
                                </th>
                                <th colspan="2" width="10%" class="text-center">
                                    <strong>/Ton</strong>
                                </th>
                                <th colspan="2" width="15%" class="text-center">
                                    <strong>/Kilogram</strong>
                                </th>
                            </tr>
                            <tr>
                                <th class="text-center">
                                    IDR
                                </th>
                                <th class="text-center">
                                    USD
                                </th>
                                <th class="text-center">
                                    IDR
                                </th>
                                <th class="text-center">
                                    USD
                                </th>
                                <th class="text-center">
                                    IDR
                                </th>
                                <th class="text-center">
                                    USD
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="16%" class="text-right">
                                    <input type="hidden" name="final_cnf[prdetail_prc_id]" id="prdetail_prc_id" readonly>
                                    <input type="hidden" name="final_cnf[prdetail_prc_incoterms]" id="prdetail_prc_incoterms" value="cnf" readonly>
                                    <input type="hidden" name="final_cnf[prdetail_keseluruhan_idr]" id="prdetail_keseluruhan_idr" value="{{ $tagihan_buyer['cnf'] }}" readonly>
                                    {{ number_format($tagihan_buyer['cnf']) }}
                                </td>
                                <td width="16%" class="text-right">
                                    <input type="hidden" name="final_cnf[prdetail_keseluruhan_usd]" id="prdetail_keseluruhan_usd" value="{{ round($tagihan_buyer['cnf'] / $kurs, 2) }}" readonly>
                                    {{ number_format(round($tagihan_buyer['cnf'] / $kurs, 2)) }}
                                </td>

                                <td width="16%" class="text-right">
                                    <input type="hidden" name="final_cnf[prdetail_ton_idr]" id="prdetail_ton_idr" value="{{ round($harga_ton['cnf']) }}" readonly>
                                    {{ number_format($harga_ton['cnf']) }}
                                </td>
                                <td width="16%" class="text-right">
                                    <input type="hidden" name="final_cnf[prdetail_ton_usd]" id="prdetail_ton_usd" value="{{ round($harga_ton['cnf'] / $kurs) }}" readonly>
                                    {{ number_format(round($harga_ton['cnf'] / $kurs, 2)) }}
                                </td>

                                <td width="16%" class="text-right">
                                    <input type="hidden" name="final_cnf[prdetail_kilo_idr]" id="prdetail_kilo_idr" value="{{ round($harga_kilo['cnf']) }}" readonly>
                                    {{ number_format($harga_kilo['cnf']) }}
                                </td>
                                <td width="16%" class="text-right">
                                    <input type="hidden" name="final_cnf[prdetail_kilo_usd]" id="prdetail_kilo_usd" value="{{ round($harga_kilo['cnf'] / $kurs, 2) }}" readonly>
                                    {{ number_format(round($harga_kilo['cnf'] / $kurs, 2), 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-lg-4">
                    <table class="table table-bordered datatable-show-all table-hover">
                        <thead>
                            <tr>
                                <th colspan="6" class="text-center font-weight-bold bg-danger-100">
                                    <h1 class="font-weight-bold">CIF</h1>
                                </th>
                            </tr>

                            @php
                                $total_cost_cif = 0;
                            @endphp
                            @foreach ($komponen_biaya['cif']->where('prkomp_nilai_fixed', '>', 0) as $r)
                                @php
                                    $komponen_id = explode(' - ', $r['prkomp_komponen_id']);
                                    $total_cost_cif += $r['prkomp_nilai_fixed'];
                                @endphp
                                <tr>
                                    <th colspan="2" width="10%">
                                        <input type="hidden" name="cif[prkomp_komponen_id][{{ $komponen_id[0] }}]" id="prkomp_komponen_id" value="{{ $komponen_id[0] }}" readonly>
                                        <input type="hidden" name="cif[prkomp_komponen_type][{{ $komponen_id[0] }}]" id="prkomp_komponen_type" value="{{ $r['prkomp_komponen_type'] }}" readonly>
                                        <input type="hidden" name="cif[prkomp_incoterms][{{ $komponen_id[0] }}]" id="prkomp_incoterms" value="cif" readonly>
                                        <strong>{{ $komponen_id[1] }}</strong>
                                    </th>
                                    <th colspan="2" width="10%" class="text-right">
                                        <input type="hidden" name="cif[prkomp_persentase][{{ $komponen_id[0] }}]" id="prkomp_persentase" value="{{ $r['prkomp_persentase'] }}" readonly>
                                        <input type="hidden" name="cif[prkomp_nilai_fixed_idr][{{ $komponen_id[0] }}]" id="prkomp_nilai_fixed_idr" value="{{ $r['prkomp_nilai_fixed'] }}" readonly>
                                        IDR {{ number_format($r['prkomp_nilai_fixed']) }} (x{{ $qty_container }})
                                    </th>
                                    <th colspan="2" width="15%" class="text-right">
                                        <input type="hidden" name="cif[prkomp_nilai_fixed_usd][{{ $komponen_id[0] }}]" id="prkomp_nilai_fixed_usd" value="{{ round($r['prkomp_nilai_fixed'] / $kurs) }}" readonly>
                                        ${{ number_format($r['prkomp_nilai_fixed'] / $kurs) }}
                                    </th>
                                </tr>
                            @endforeach
                            <tr>
                                <th colspan="2" width="10%">
                                    <strong>Cost Produk</strong>
                                </th>
                                <th colspan="2" width="10%" class="text-right">
                                    IDR {{ number_format($biaya_produk) }}
                                </th>
                                <th colspan="2" width="15%" class="text-right">
                                    ${{ number_format($biaya_produk / $kurs) }}
                                </th>
                            </tr>
                            @if ($packing > 0)
                                <tr>
                                    <th colspan="2" width="10%">
                                        <strong>Packaging</strong>
                                    </th>
                                    <th colspan="2" width="10%" class="text-right">
                                        IDR {{ number_format($packing) }}
                                    </th>
                                    <th colspan="2" width="15%" class="text-right">
                                        ${{ number_format($packing / $kurs) }}
                                    </th>
                                </tr>
                            @endif
                            <tr>
                                <th colspan="2" width="10%">
                                    <strong>Profit</strong>
                                </th>
                                <th colspan="2" width="10%" class="text-right">
                                    IDR {{ number_format($profit) }}
                                </th>
                                <th colspan="2" width="15%" class="text-right">
                                    ${{ number_format($profit / $kurs) }}
                                </th>
                            </tr>
                            <tr>
                                <th colspan="2" width="10%">
                                    <strong>Total Tagihan Buyer</strong>
                                </th>
                                <th colspan="2" width="10%" class="text-right">
                                    IDR {{ number_format($total_cost_cif + $biaya_produk + $profit) }}
                                </th>
                                <th colspan="2" width="15%" class="text-right">
                                    ${{ number_format(($total_cost_cif + $biaya_produk + $profit) / $kurs) }}
                                </th>
                            </tr>
                            
                            <tr>
                                <th colspan="2" width="10%" class="text-center">
                                    <strong>Keseluruhan</strong>
                                </th>
                                <th colspan="2" width="10%" class="text-center">
                                    <strong>/Ton</strong>
                                </th>
                                <th colspan="2" width="15%" class="text-center">
                                    <strong>/Kilogram</strong>
                                </th>
                            </tr>
                            <tr>
                                <th class="text-center">
                                    IDR
                                </th>
                                <th class="text-center">
                                    USD
                                </th>
                                <th class="text-center">
                                    IDR
                                </th>
                                <th class="text-center">
                                    USD
                                </th>
                                <th class="text-center">
                                    IDR
                                </th>
                                <th class="text-center">
                                    USD
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="16%" class="text-right">
                                    <input type="hidden" name="final_cif[prdetail_prc_id]" id="prdetail_prc_id" readonly>
                                    <input type="hidden" name="final_cif[prdetail_prc_incoterms]" id="prdetail_prc_incoterms" value="cif" readonly>
                                    <input type="hidden" name="final_cif[prdetail_keseluruhan_idr]" id="prdetail_keseluruhan_idr" value="{{ $tagihan_buyer['cif'] }}" readonly>
                                    {{ number_format($tagihan_buyer['cif']) }}
                                </td>
                                <td width="16%" class="text-right">
                                    <input type="hidden" name="final_cif[prdetail_keseluruhan_usd]" id="prdetail_keseluruhan_usd" value="{{ round($tagihan_buyer['cif'] / $kurs, 2) }}" readonly>
                                    {{ number_format(round($tagihan_buyer['cif'] / $kurs, 2)) }}
                                </td>

                                <td width="16%" class="text-right">
                                    <input type="hidden" name="final_cif[prdetail_ton_idr]" id="prdetail_ton_idr" value="{{ round($harga_ton['cif']) }}" readonly>
                                    {{ number_format($harga_ton['cif']) }}
                                </td>
                                <td width="16%" class="text-right">
                                    <input type="hidden" name="final_cif[prdetail_ton_usd]" id="prdetail_ton_usd" value="{{ round($harga_ton['cif'] / $kurs) }}" readonly>
                                    {{ number_format(round($harga_ton['cif'] / $kurs, 2)) }}
                                </td>

                                <td width="16%" class="text-right">
                                    <input type="hidden" name="final_cif[prdetail_kilo_idr]" id="prdetail_kilo_idr" value="{{ round($harga_kilo['cif']) }}" readonly>
                                    {{ number_format($harga_kilo['cif']) }}
                                </td>
                                <td width="16%" class="text-right">
                                    <input type="hidden" name="final_cif[prdetail_kilo_usd]" id="prdetail_kilo_usd" value="{{ round($harga_kilo['cif'] / $kurs, 2) }}" readonly>
                                    {{ number_format(round($harga_kilo['cif'] / $kurs, 2), 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr>

            <div class="text-center">
                <button class="btn btn-success btn-labeled btn-labeled-left" type="button" onclick="simpan_data()">
                    <b><i class="icon-floppy-disk"></i></b>Simpan
                </button>
                <a href="{{ route('tools.pricing_generator.index') }}" class="btn btn-danger btn-labeled btn-labeled-left"><b>
                    <i class="icon-cross"></i></b>Cancel
                </a>
            </div>
        </div>
    </div>
</form>