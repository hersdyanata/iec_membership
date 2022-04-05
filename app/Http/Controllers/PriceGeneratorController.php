<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;
use DB;
use App;

use App\Services\CustDataTables;
use App\Services\GrantedService;
use App\Services\Global_Vars;
use App\Services\TimeService;
use App\Services\PricingService;

use App\Models\PricingModel as Pricing;
use App\Models\PricingBiayaModel as PricingBiaya;
use App\Models\PricingPackagingModel as PricingPack;
use App\Models\PricingFinalModel as PricingFinal;

use App\Models\MstKomponenBiayaModel as Biaya;
use App\Models\MstSupplierModel as Supplier;
use App\Models\MstBuyerModel as Buyer;
use App\Models\MstKomoditasModel as Komoditas;
use App\Models\MstPackagingModel as Packing;
use PDF;

class PriceGeneratorController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', 'granted', 'verified']);
    }

    public function index(){
        return view('modules.pricing_generator.index')
                ->with([
                    'title' => 'Pricing Generator',
                ]);
    }

    public function create(Global_Vars $globvars){
        $biaya = Biaya::all();
        return view('modules.pricing_generator.create')
                ->with([
                    'title' => 'Buat Pricing Baru',
                    'komponen_fix' => $biaya->where('komp_type', 'fixed'),
                    'komponen_persentase' => $biaya->where('komp_type', 'persentase'),
                    'supplier' => Supplier::where('supplier_createdby', session('user_id'))->get(),
                    'buyer' => Buyer::all(),
                    'containers' => $globvars->container_size(),
                    'packs' => Packing::all()
                ]);
    }

    public function supplier_komoditas(Request $request){
        $komoditas = Komoditas::whereIn('komoditas_id', json_decode($request->komoditas))->get();
        $option = '';
        $option .= '<option value="">-- Pilih Komoditas --</option>';
        foreach ($komoditas as $r){
            $option .= '<option value="'.$r->komoditas_id.'">'.$r->komoditas_prefix.' - '.$r->komoditas_nama.'</option>';
        }

        return response()->json($option, 200);
    }

    public function generate(PricingService $pricing, Request $request){
        $harga_produk = str_replace(',', '', $request->input('prc_harga_supplier'));
        $max_load_container = str_replace(',', '', $request->input('prc_container_max_qty'));
        $qty_container = $request->input('prc_container_qty');
        $total_qty_order = str_replace(',', '', $request->input('prc_container_max_qty')) * $request->input('prc_container_qty');
        $kurs = str_replace(',', '', $request->prc_kurs);
        
        // 1. Menghitung biaya produk
        $biaya_produk = $harga_produk * $total_qty_order;

        // 2. Menghitung profit
        $profit = $total_qty_order * str_replace(',', '', $request->prc_margin_idr);
        // Hitung total biaya dasar (produk+profit+komponen biaya fixed)
        $biaya_dasar = $biaya_produk + $profit;

        // 3. Meng-collect komponen biaya dengan nilai fixed
        foreach(str_replace(',', '', $request->prkomp_nilai_fixed) as $i => $v){
            if(!empty($request['prkomp_nilai_fixed'])){
                $array_biaya_fixed[] = [
                    'prkomp_komponen_id' => $i.' - '.$request->prkomp_komponen_nama[$i],
                    'prkomp_komponen_type' => $request->prkomp_komponen_type[$i],
                    'prkomp_persentase' => null,
                    'prkomp_incoterms' => $request->prkomp_incoterms[$i],
                    'prkomp_nilai_fixed' => $v * $qty_container
                ];
            }
        }
        $biaya_fixed = collect($array_biaya_fixed)->sum('prkomp_nilai_fixed');

        // 4. Meng-collect komponen biaya dengan nilai variable/persentase
        foreach(str_replace(',', '', $request->prkomp_persentase) as $i => $v){
            if(!empty($request['prkomp_persentase'])){
                $array_biaya_persentase[] = [
                    'prkomp_komponen_id' => $i.' - '.$request->prkomp_komponen_nama[$i],
                    'prkomp_komponen_type' => $request->prkomp_komponen_type[$i],
                    'prkomp_persentase' => $v,
                    'prkomp_incoterms' => $request->prkomp_incoterms[$i],
                    'prkomp_nilai_fixed' => ($v / 100) * $biaya_dasar
                ];
            }
        }
        $all_komponen_biaya = collect($array_biaya_fixed)->merge($array_biaya_persentase);
        $komponen_fob = $all_komponen_biaya->where('prkomp_incoterms', 'fob');
        $komponen_cnf = $all_komponen_biaya->where('prkomp_incoterms', 'cnf');
        $komponen_cif = $all_komponen_biaya->where('prkomp_incoterms', 'cif');

        // 5. Menghitung seluruh tagihan untuk buyer
        $fob = $biaya_dasar + $komponen_fob->sum('prkomp_nilai_fixed');
        $cnf = $fob + $komponen_cnf->sum('prkomp_nilai_fixed');
        $cif = $cnf + $komponen_cif->sum('prkomp_nilai_fixed');
        
        // 6. Membagi seluruh tagihan buyer dan meng-konversi menjadi harga per ton
        $fob_ton = $fob / ($total_qty_order / 1000);
        $cnf_ton = $cnf / ($total_qty_order / 1000);
        $cif_ton = $cif / ($total_qty_order / 1000);

        // 7. Membagi harga ton menjadi harga kilo
        $fob_kilo = ($fob_ton / 1000);
        $cnf_kilo = ($cnf_ton / 1000);
        $cif_kilo = ($cif_ton / 1000);

        // 8. Menghitung biaya packaging
        if(isset($request->pack_id)){
            $cost_packing = 0;
            foreach($request->pack_id as $i => $v){
                $packing_idr = str_replace(',', '',$request->pack_cost_idr[$i]);
                $data_packing[] = [
                    'pack_prc_id' => 'xxx',
                    'pack_id' => $request->pack_id[$i],
                    'pack_size' => $request->pack_size[$i],
                    'pack_harga' => $request->pack_harga[$i],
                    'pack_unit' => 'Kg',
                    'pack_qty' => str_replace(',', '', $request->pack_qty[$i]),
                    'pack_cost_idr' => $packing_idr,
                    'pack_cost_usd' => round($packing_idr / $kurs, 2),
                ];
            }
            
            $cost_packing = collect($data_packing)->sum('pack_cost_idr');
            $json_packing = json_encode($data_packing);
        }else{
            $cost_packing = 0;
            $json_packing = null;
        }

        // 9. Menampilkan di view
        $view = view('modules.pricing_generator.preview')
                ->with('pricing_kode', $pricing->generate_kode_pricing($request->prc_komoditas_id))
                ->with('komponen_biaya', ['fob' => $komponen_fob, 'cnf' => collect($komponen_fob)->merge($komponen_cnf), 'cif' => collect($komponen_fob)->merge($komponen_cnf)->merge($komponen_cif)])
                ->with('tagihan_buyer', ['fob' => $fob, 'cnf' => $cnf, 'cif' => $cif])
                ->with('harga_ton', ['fob' => $fob_ton, 'cnf' => $cnf_ton, 'cif' => $cif_ton])
                ->with('harga_kilo', ['fob' => $fob_kilo, 'cnf' => $cnf_kilo, 'cif' => $cif_kilo])
                ->with('kurs', $kurs)
                ->with('biaya_produk', $biaya_produk)
                ->with('profit', $profit)
                ->with('req', $request->all())
                ->with('packing', $cost_packing)
                ->with('json_packing', $json_packing)
                ->with('qty_container', $qty_container)
                ->render();

        return response()->json($view, 200);
    }

    public function store(Request $request){
        $header = $request['header'];
        $pricing_id = Pricing::insertGetId($header);
        $detail = $this->proses_detail($pricing_id, $request->all());

        $res = [
            'msg_title' => 'Berhasil',
            'msg_body' => 'Data berhasil disimpan.',
        ];
        return response()->json($res, 200);
    }

    public function proses_detail($pricing_id, $request){
        if(isset($request['fob']['prkomp_nilai_fixed_idr'])){
            foreach($request['fob']['prkomp_nilai_fixed_idr'] as $i => $v){
                $fob[] = [
                    'prkomp_prc_id' => $pricing_id,
                    'prkomp_komponen_id' => $i,
                    'prkomp_komponen_type' => $request['fob']['prkomp_komponen_type'][$i],
                    'prkomp_persentase' => $request['fob']['prkomp_persentase'][$i],
                    'prkomp_incoterms' => $request['fob']['prkomp_incoterms'][$i],
                    'prkomp_nilai_fixed_idr' => $v,
                    'prkomp_nilai_fixed_usd' => $request['fob']['prkomp_nilai_fixed_usd'][$i],
                ];
            }
        }

        if(isset($request['cnf']['prkomp_nilai_fixed_idr'])){
            foreach($request['cnf']['prkomp_nilai_fixed_idr'] as $i => $v){
                $cnf[] = [
                    'prkomp_prc_id' => $pricing_id,
                    'prkomp_komponen_id' => $i,
                    'prkomp_komponen_type' => $request['cnf']['prkomp_komponen_type'][$i],
                    'prkomp_persentase' => $request['cnf']['prkomp_persentase'][$i],
                    'prkomp_incoterms' => $request['cnf']['prkomp_incoterms'][$i],
                    'prkomp_nilai_fixed_idr' => $v,
                    'prkomp_nilai_fixed_usd' => $request['cnf']['prkomp_nilai_fixed_usd'][$i],
                ];
            }
        }

        if(isset($request['cif']['prkomp_nilai_fixed_idr'])){
            foreach($request['cif']['prkomp_nilai_fixed_idr'] as $i => $v){
                $cif[] = [
                    'prkomp_prc_id' => $pricing_id,
                    'prkomp_komponen_id' => $i,
                    'prkomp_komponen_type' => $request['cif']['prkomp_komponen_type'][$i],
                    'prkomp_persentase' => $request['cif']['prkomp_persentase'][$i],
                    'prkomp_incoterms' => $request['cif']['prkomp_incoterms'][$i],
                    'prkomp_nilai_fixed_idr' => $v,
                    'prkomp_nilai_fixed_usd' => $request['cif']['prkomp_nilai_fixed_usd'][$i],
                ];
            }
        }
        if(isset($fob)){
            $komponen_biaya = collect($fob)->merge($cnf)->merge($cif)->toArray();
        }

        $final_fob[] = $request['final_fob'];
        $final_cnf[] = $request['final_cnf'];
        $final_cif[] = $request['final_cif'];
        $price = collect($final_fob)->merge($final_cnf)->merge($final_cif)->toArray();

        $final_price = collect($price)->map(function($price, $key) use ($pricing_id){
            $collect = (object)$price;
            return [
                'prdetail_prc_id' => $pricing_id,
                'prdetail_prc_incoterms' => $collect->prdetail_prc_incoterms,
                'prdetail_keseluruhan_idr' => $collect->prdetail_keseluruhan_idr,
                'prdetail_keseluruhan_usd' => $collect->prdetail_keseluruhan_usd,
                'prdetail_ton_idr' => $collect->prdetail_ton_idr,
                'prdetail_ton_usd' => $collect->prdetail_ton_usd,
                'prdetail_kilo_idr' => $collect->prdetail_kilo_idr,
                'prdetail_kilo_usd' => $collect->prdetail_kilo_usd,
            ];
        })->toArray();

        
        if(isset($komponen_biaya)){
            PricingBiaya::insert($komponen_biaya);
        }
        PricingFinal::insert($final_price);
        
        if($request['packing_detail'] != null){
            $packaging = json_decode(str_replace('xxx', $pricing_id, $request['packing_detail']));
            foreach($packaging as $p){
                $packing[] = collect($p)->toArray();
            }
            PricingPack::insert($packing);
        }

        $res = [
            'message' => 'proses data komponen dan pricing final selesai',
            'status' => '1'
        ];

        return $res;
    }

    public function show($id){
        $data = Pricing::leftJoin('mst_buyer', 'buyer_id', 'prc_buyer_id')->find($id);
        $res = $this->delete_validation($id, $data->prc_kode, $data->buyer_perusahaan);
        return response()->json($res, 200);
    }

    public function edit(Global_Vars $globvars, $id){
        $biaya = Biaya::all();
        return view('modules.pricing_generator.edit')
                ->with([
                    'title' => 'Edit Data Pricing',
                    'komponen_fix' => $biaya->where('komp_type', 'fixed'),
                    'komponen_persentase' => $biaya->where('komp_type', 'persentase'),
                    'supplier' => Supplier::all(),
                    'buyer' => Buyer::all(),
                    'containers' => $globvars->container_size(),
                    'pricing' => Pricing::find($id),
                    'pricing_komponen' => PricingBiaya::where('prkomp_prc_id', $id)->get(),
                    'pricing_final' => PricingFinal::where('prdetail_prc_id', $id)->get(),
                    'pricing_packs' => PricingPack::where('pack_prc_id', $id)->get(),
                    'packs' => Packing::all()
                ]);
    }

    public function update(PricingService $pricing, Request $request, $id){
        $put = Pricing::find($id);
        $put->prc_label = $request['header']['prc_label'];
        $put->prc_kode = $pricing->generate_kode_pricing($request['header']['prc_komoditas_id']);
        $put->prc_supplier_id = $request['header']['prc_supplier_id'];
        $put->prc_komoditas_id = $request['header']['prc_komoditas_id'];
        $put->prc_harga_supplier = $request['header']['prc_harga_supplier'];
        $put->prc_kurs = $request['header']['prc_kurs'];
        $put->prc_container_size = $request['header']['prc_container_size'];
        $put->prc_container_max_qty = $request['header']['prc_container_max_qty'];
        $put->prc_container_qty = $request['header']['prc_container_qty'];
        $put->prc_total_qty = $request['header']['prc_total_qty'];
        $put->prc_margin_persentase = $request['header']['prc_margin_persentase'];
        $put->prc_margin_idr = str_replace(',', '', $request['header']['prc_margin_idr']);
        $put->prc_cost_produk = $request['header']['prc_cost_produk'];
        $put->prc_profit = $request['header']['prc_profit'];
        $put->prc_updatedat = $request['header']['prc_createdat'];
        $put->prc_updatedby = $request['header']['prc_createdby'];
        $put->save();

        PricingBiaya::where('prkomp_prc_id', $id)->delete();
        PricingFinal::where('prdetail_prc_id', $id)->delete();
        PricingPack::where('pack_prc_id', $id)->delete();
        $detail = $this->proses_detail($id, $request->all());

        $res = [
            'msg_title' => 'Berhasil',
            'msg_body' => 'Data berhasil disimpan.',
        ];
        return response()->json($res, 200);
    }

    public function destroy($id){
        $data = Pricing::leftJoin('mst_buyer', 'buyer_id', 'prc_buyer_id')->find($id);
        $res = [
            'msg_title' => 'Berhasil',
            'msg_body' => 'Pricing '.$data->prc_kode.' untuk buyer '.$data->buyer_perusahaan.' telah dihapus.',
        ];
        PricingBiaya::where('prkomp_prc_id', $id)->delete();
        PricingFinal::where('prdetail_prc_id', $id)->delete();
        PricingPack::where('pack_prc_id', $id)->delete();
        $data->delete();
        return response()->json($res,200);
    }

    public function cetak(PricingService $pricing, $param, $terms){
        // $pdf = App::make('dompdf.wrapper');
        // $pdf->loadHTML('<h1>Test</h1>');
        // return $pdf->stream();

        $item = explode(',',$param);
        foreach($item as $i => $v){
            $ids[] = [
                Crypt::decryptString($v)
            ];
        }
        $collection = collect($ids)->flatten()->toArray();
        $data = $pricing->cetak_buyer($collection, $terms);
        
        // $with = [
        //     'title' => 'Cetak Price List',
        //     'incoterms' => strtoupper($terms),
        //     'items' => $data['pricing'],
        //     'packing' => $data['packing']
        // ];
        // $view = PDF::loadView('modules.pricing_generator.print', $with);

        // $pdf = App::make('dompdf.wrapper');
        // $pdf->loadHtml($view);
        // return $view->download('pricing.pdf');

        return view('modules.pricing_generator.print')
                ->with([
                    'title' => 'Cetak Price List',
                    'incoterms' => strtoupper($terms),
                    'items' => $data['pricing'],
                    'packing' => $data['packing']
                ]);

    }
    
    public function list(CustDataTables $dtt, GrantedService $granted, Global_Vars $globvars, Request $request){
        $colSearch = array(
            'prc_kode', 'supplier_nama', 'komoditas_nama', 'prc_kurs', 'prc_container_size',
        );
        
        $colOrder = array(
            null, 'prc_kode', 'supplier_nama', 'komoditas_nama', 'prc_kurs', 'prc_container_size', null
        );

        $q = "select *
                from pricing
                left join mst_supplier on supplier_id = prc_supplier_id
                left join mst_komoditas on komoditas_id = prc_komoditas_id
                left join mst_buyer on buyer_id = prc_buyer_id
                left join pricing_final on prdetail_prc_id = prc_id
               where prdetail_prc_incoterms = 'fob'
                 and prc_createdby = ".session('user_id');
        $order = 'order by prc_buyer_id asc';
        
        $dtt->set_table($q);
        $dtt->set_col_order($colOrder);
        $dtt->set_col_search($colSearch);
        $dtt->set_order($order);

        $posts = $dtt->get_datatables();

        $no = $request->input('start');
        $data = array();
        foreach($posts as $r){
            $container = collect($globvars->container_size())->where('container_size', $r->prc_container_size)->first();
            
            $no++;
            if(session('group_id') != 0){
                if( in_array('update', $request->page_permission) ){
                    $buttons = '<button class="btn btn-warning btn-icon rounded-round tooltiped" data-toggle="tooltip" data-placement="top" title="" data-original-title="Copy" onclick="copy('.$r->prc_id.')"><i class="icon-copy"></i></button> ';
                }
            }else{
                $buttons = '<button class="btn btn-warning btn-icon rounded-round tooltiped" data-toggle="tooltip" data-placement="top" title="" data-original-title="Copy" onclick="copy('.$r->prc_id.')"><i class="icon-copy"></i></button> ';
            }

            $buttons .= $granted->action_buttons('tools.pricing_generator.edit', $r->prc_id, $request->page_permission);

            $dtRow = array();
            $dtRow[] = '<div class="text-center">'.$no.'</div>';
            $dtRow[] = '<div>'.$r->prc_label.'</div>';
            // $dtRow[] = '<div>PIC: '.$r->buyer_pic.'<br>Comp: '.$r->buyer_perusahaan.'</div>';
            $dtRow[] = '<div>Supplier: '.$r->supplier_nama.'<br>'.$r->komoditas_prefix.'<br>'.$r->komoditas_nama.'</div>';
            $dtRow[] = '<div>'.$r->prc_container_qty.' x '.$container['container_name'].'<br>Total Qty: '.number_format($r->prc_total_qty/1000).' MT<br>Qty /Container: '.number_format($r->prc_container_max_qty).' MT</div>';
            $dtRow[] = '<div>'.
                            'Spl: '.number_format($r->prc_harga_supplier).' ($ '.(number_format($r->prc_harga_supplier / $r->prc_kurs,2)).')<br>'
                            .'Exw: '.number_format($r->prc_harga_supplier + $r->prc_margin_idr).' ($ '.(number_format(($r->prc_harga_supplier + $r->prc_margin_idr) / $r->prc_kurs,2)).')'.'<br>'
                            .'FOB: '.number_format($r->prdetail_kilo_idr).' ($ '.(number_format($r->prdetail_kilo_usd,2)).')'
                        .'</div>';
            $dtRow[] = 'Mgn: IDR '.number_format($r->prc_margin_idr).' ('.$r->prc_margin_persentase.'%)<br>IDR '.number_format($r->prc_profit);
            $dtRow[] = date('Y-m-d', strtotime($r->prc_createdat));
            $dtRow[] = '<div class="text-center">'.$buttons.'</div>';
            $dtRow[] = Crypt::encryptString($r->prc_id);
            $data[] = $dtRow;
        }

        $json_data = array(
            "draw"              => $request->input('draw'),
            "recordsTotal"      => $dtt->count_all(),
            "recordsFiltered"   => $dtt->count_filtered(),
            "data"              => $data,
        );

        return response()->json($json_data, 200);
    }

    public function delete_validation($id, $nama, $buyer){
        // $count = Supplier::where('supplier_id', $id)->count();
        // if($count > 0){
        //     $res = [
        //         'msg_title' => 'Gagal',
        //         'msg_body' => 'Supplier '.$nama.' tidak bisa dihapus karena memiliki '.$count.' pricing.',
        //         'permission' => 'F'
        //     ];
        // }else{
            $res = [
                'msg_title' => 'Konfirmasi',
                'msg_body' => 'Apakah Anda yakin akan menghapus pricing '.$nama.' untuk buyer '.$buyer.'?',
                'permission' => 'T'
            ];
        // }

        return $res;
    }

    public function pra_copy($id){
        $data = Pricing::find($id);
        $res = [
            'msg_title' => 'Konfirmasi',
            'msg_body' => 'Anda akan menduplikat pricing <strong>'.$data->prc_label.'</strong> Apakah Anda yakin?',
        ];
        return response()->json($res, 200);
    }
    
    public function copy(PricingService $pricing, Request $req){
        $src_header = Pricing::find($req->id);
        $label_lama = $src_header->prc_label;
        $label_baru = $src_header->prc_label.'_Copy_'.date('Y_m_d_H_i_s');
        $header = [
            'prc_kode' => $pricing->generate_kode_pricing($src_header->prc_komoditas_id),
            'prc_label' => $label_baru,
            'prc_supplier_id' => $src_header->prc_supplier_id,
            'prc_buyer_id' => $src_header->prc_buyer_id,
            'prc_komoditas_id' => $src_header->prc_komoditas_id,
            'prc_harga_supplier' => $src_header->prc_harga_supplier,
            'prc_kurs' => $src_header->prc_kurs,
            'prc_container_size' => $src_header->prc_container_size,
            'prc_container_max_qty' => $src_header->prc_container_max_qty,
            'prc_container_qty' => $src_header->prc_container_qty,
            'prc_total_qty' => $src_header->prc_total_qty,
            'prc_margin_persentase' => $src_header->prc_margin_persentase,
            'prc_margin_idr' => $src_header->prc_margin_idr,
            'prc_cost_produk' => $src_header->prc_cost_produk,
            'prc_profit' => $src_header->prc_profit,
            'prc_createdat' => date('Y-m-d H:i:s'),
            'prc_createdby' => $src_header->prc_createdby,
        ];
        $prc_id = Pricing::insertGetId($header);
        
        $src_biaya = PricingBiaya::where('prkomp_prc_id', $req->id)->get();
        foreach($src_biaya as $r){
            $biaya[] = [
                'prkomp_prc_id' => $prc_id,
                'prkomp_komponen_id' => $r->prkomp_komponen_id,
                'prkomp_komponen_type' => $r->prkomp_komponen_type,
                'prkomp_incoterms' => $r->prkomp_incoterms,
                'prkomp_persentase' => $r->prkomp_persentase,
                'prkomp_nilai_fixed_idr' => $r->prkomp_nilai_fixed_idr,
                'prkomp_nilai_fixed_usd' => $r->prkomp_nilai_fixed_usd,
            ];      
        }

        $src_final = PricingFinal::where('prdetail_prc_id', $req->id)->get();
        foreach($src_final as $f){
            $final[] = [
                'prdetail_prc_id' => $prc_id,
                'prdetail_prc_incoterms' => $f->prdetail_prc_incoterms,
                'prdetail_keseluruhan_idr' => $f->prdetail_keseluruhan_idr,
                'prdetail_keseluruhan_usd' => $f->prdetail_keseluruhan_usd,
                'prdetail_ton_idr' => $f->prdetail_ton_idr,
                'prdetail_ton_usd' => $f->prdetail_ton_usd,
                'prdetail_kilo_idr' => $f->prdetail_kilo_idr,
                'prdetail_kilo_usd' => $f->prdetail_kilo_usd,
            ];
        }

        $src_packing = PricingPack::where('pack_prc_id', $req->id)->get();
        if(isset($src_packing)){
            foreach($src_packing as $p){
                $packing[] = [
                    'pack_prc_id' => $prc_id,
                    'pack_id' => $p->pack_id,
                    'pack_size' => $p->pack_size,
                    'pack_harga' => $p->pack_harga,
                    'pack_unit' => $p->pack_unit,
                    'pack_qty' => $p->pack_qty,
                    'pack_cost_idr' => $p->pack_cost_idr,
                    'pack_cost_usd' => $p->pack_cost_usd,
                ];
            }
            PricingPack::insert($packing);
        }

        if(isset($biaya)){
            PricingBiaya::insert($biaya);
        }
        PricingFinal::insert($final);

        $res = [
            'msg_title' => 'Berhasil',
            'msg_body' => 'Proses duplikat pricing <strong>'.$label_lama.'</strong> berhasil. Label pricing baru Anda <strong>'.$label_baru.'</strong>',
        ];
        return response()->json($res, 200);
    }
}