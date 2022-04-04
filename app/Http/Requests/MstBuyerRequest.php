<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class MstBuyerRequest extends FormRequest
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
            'buyer_perusahaan' => [
                'required',
                Rule::unique('mst_buyer')->ignore($this->buyer_id, 'buyer_id')
            ],
            'buyer_pic' => [
                'required',
            ],
            'buyer_no_hp' => [
                'required',
            ],
            'buyer_alamat' => [
                'required',
            ],
            'buyer_negara' => [
                'required',
            ],
        ];
    }

    public function messages(){
        return [
            'buyer_perusahaan.required' => 'Nama perusahaan tidak boleh kosong.',
            'buyer_perusahaan.unique' => 'Nama perusahaan sudah ada.',
            'buyer_pic.required' => 'PIC tidak boleh kosong.',
            'buyer_no_hp.required' => 'No. HP tidak boleh kosong.',
            'buyer_alamat.required' => 'Alamat tidak boleh kosong.',
            'buyer_negara.required' => 'Negara tidak boleh kosong.',
        ];
    }

    protected function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'msg_title' => 'Gagal',
            'msg_body' => json_encode($validator->errors())
        ], 422));
    }
}
