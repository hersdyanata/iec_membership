<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class MstPelabuhanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'port_kode_tujuan' => [
                'required',
                Rule::unique('mst_negara_pelabuhan')->ignore($this->port_kode_tujuan, 'port_kode_tujuan')
            ],
            'port_nama' => [
                'required',
            ],
            'port_negara_kode' => [
                'required',
            ],
        ];
    }

    public function messages(){
        return [
            'port_negara_kode.required' => 'Negara tujuan tidak boleh kosong.',
            'port_kode_tujuan.required' => 'Kode pelabuhan tidak boleh kosong.',
            'port_kode_tujuan.unique' => 'Kode pelabuhan sudah ada. Silahkan cek kembali.',
            'port_nama.required' => 'Nama pelabuhan tidak boleh kosong.',
        ];
    }

    protected function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'msg_title' => 'Gagal',
            'msg_body' => json_encode($validator->errors())
        ], 422));
    }
}
