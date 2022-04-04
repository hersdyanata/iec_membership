<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class MstKomoditasRequest extends FormRequest
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
            'komoditas_nama' => [
                'required',
                Rule::unique('mst_komoditas')->ignore($this->komoditas_id, 'komoditas_id')
            ],
            'komoditas_prefix' => [
                'required'
            ],
            // 'komoditas_spesifikasi' => [
            //     'required',
            // ],
        ];
    }

    public function messages(){
        return [
            'komoditas_nama.required' => 'Nama komoditas tidak boleh kosong.',
            'komoditas_nama.unique' => 'Nama komoditas sudah ada. Silahkan cek kembali.',
            'komoditas_prefix' => 'Prefix tidak boleh kosong',
            // 'komoditas_spesifikasi.required' => 'Spesifikasi tidak boleh kosong.',
        ];
    }

    protected function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'msg_title' => 'Gagal',
            'msg_body' => json_encode($validator->errors())
        ], 422));
    }
}
