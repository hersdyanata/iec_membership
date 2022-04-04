<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CoreMenuDividerRequest extends FormRequest
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
            'div_nama_ina' => [
                'required',
                Rule::unique('dev_menu_divider')->ignore($this->div_id, 'div_id')
            ],
            'div_nama_eng' => [
                'required',
                Rule::unique('dev_menu_divider')->ignore($this->div_id, 'div_id')
            ],
            'div_order' => [
                'required',
                Rule::unique('dev_menu_divider')->ignore($this->div_id, 'div_id')
            ],
        ];
    }

    public function messages(){
        return [
            'div_nama_ina.required' => 'Nama divider dalam bahasa indonesia diperlukan.',
            'div_nama_ina.unique' => 'Nama divider dalam bahasa indonesia sudah ada.',
            'div_nama_eng.required' => 'Nama divider dalam bahasa inggris diperlukan.',
            'div_nama_eng.unique' => 'Nama divider dalam bahasa inggris sudah ada.',
            'div_order.required' => 'Urutan divider diperlukan untuk menyusun urutan posisi menu.',
            'div_order.unique' => 'Urutan sudah di-apply oleh divider lain'
        ];
    }

    protected function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'msg_title' => 'Gagal',
            'msg_body' => json_encode($validator->errors())
        ], 422));
    }
}
