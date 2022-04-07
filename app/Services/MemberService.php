<?php

namespace App\Services;

use DB;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

use App\Models\User;
use App\Models\MemberProfileModel as Profile;
use App\Models\SettingProfileModel as Setting;

class MemberService{

    public function status_profile($user_id){
        $profile = Profile::find($user_id);
        $setting = Setting::where('tbl_prefix', 'profile')->get();

        $kolom = '';
        $array_required = array();
        foreach($setting as $i => $c){
            $kolom = $c->tbl_nama_kolom;
            $array_required[$i] = array(
                'nama_kolom' => $c->tbl_nama_kolom,
                'kolom_alias' => $c->tbl_kolom_alias,
                'isi' => $profile->$kolom,
                'required_flag' => $c->tbl_required_flag
            );
        }

        $alert_required = '<ul>';
        $kolom_belum_lengkap = collect($array_required)->where('required_flag', 'Y')->where('isi', null)->all();
        $kolom_sudah_lengkap = collect($array_required)->where('required_flag', 'Y')->where('isi', '!=', null)->all();
        foreach($kolom_belum_lengkap as $r){
            $alert_required .= '<li>'.$r['kolom_alias'].'</li>';
        }
        $alert_required .= '</ul>';

        if(count($kolom_belum_lengkap) > 0){
            $data['alert'] = '<div class="alert alert-danger border-0"><span class="font-weight-semibold">Tidak dapat menggunakan fitur Pricing Calculator karena profile Anda belum lengkap.</span></div>';
            $data['pesan'] = '<h5 class="text-danger">Anda belum melengkapi profile.<br>Silahkan untuk melengkapinya di menu <strong>Profile</strong></h5>';
            $data['last_update'] = ($profile->profile_updatedat != null) ? date("d F, Y : H:i", strtotime($profile->profile_updatedat)) : '';
            $data['collections'] = $profile;
            $data['uncomplete_data'] = $kolom_belum_lengkap;
            $data['completed_data'] = $kolom_sudah_lengkap;
            $data['status'] = 'N';
            $data['mandatory'] = collect($array_required)->where('required_flag', 'Y')->all();
        }else{
            $data['alert'] = '<div class="alert alert-success border-0"><span class="font-weight-semibold">Profile sudah lengkap.</div>';
            $data['pesan'] = '<h5 class="text-success">Profile Anda sudah lengkap.</h5>';
            $data['last_update'] = ($profile->profile_updatedat != null) ? date("d F, Y : H:i", strtotime($profile->profile_updatedat)) : '';
            $data['collections'] = $profile;
            $data['completed_data'] = $kolom_sudah_lengkap;
            $data['status'] = 'Y';
            $data['mandatory'] = collect($array_required)->where('required_flag', 'Y')->all();
        }

        return $data;
    }

}