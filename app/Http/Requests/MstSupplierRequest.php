<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class MstSupplierRequest extends FormRequest
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
            'supplier_nama' => [
                'required',
                Rule::unique('mst_supplier')->ignore($this->supplier_id, 'supplier_id')
            ],
            'supplier_alamat' => [
                'required',
            ],
            'supplier_komoditas' => [
                'required',
            ],
        ];
    }

    public function messages(){
        return [
            'supplier_nama.required' => 'Nama supplier tidak boleh kosong.',
            'supplier_nama.unique' => 'Nama supplier sudah ada. Silahkan cek kembali.',
            'supplier_alamat.required' => 'Alamat tidak boleh kosong.',
            'supplier_komoditas.required' => 'Komoditas tidak boleh kosong.',
        ];
    }

    protected function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'msg_title' => 'Gagal',
            'msg_body' => json_encode($validator->errors())
        ], 422));
    }
}
