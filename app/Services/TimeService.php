<?php

namespace App\Services;
use Illuminate\Support\Arr;

class TimeService{
    
    public function get_all_days(){
        return [
            '1' => 'Senin',
            '2' => 'Selasa',
            '3' => 'Rabu',
            '4' => 'Kamis',
            '5' => 'Jumat',
            '6' => 'Sabtu',
            '7' => 'Minggu'
        ];
    }

    public function get_day($key){
        $days = $this->get_all_days();
        return Arr::get($days, $key);
    }

    public function get_all_months(){
        return [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];
    }

    public function get_month($key){
        $months = $this->get_all_months();
        return Arr::get($months, $key);
    }

    public function translate_to_ina($tgl){
        $dt = explode('-', $tgl);
        $tahun = $dt[0];
        $bulan = $this->get_month($dt[1]);
        $tanggal = $dt[2];
        $hari = $this->get_day(date('N', strtotime($tgl)));
        
        return $hari.', '.$tanggal.' '.$bulan.' '.$tahun;
    }

}