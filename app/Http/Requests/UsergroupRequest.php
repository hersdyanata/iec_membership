<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UsergroupRequest extends FormRequest
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
            'group_nama' => [
                'required',
                Rule::unique('usergroup')->ignore($this->group_id, 'group_id')
            ],
            'group_deskripsi' => 'required',
            'group_default_menu' => 'required'
        ];
    }

    public function messages(){
        return [
            'group_nama.required' => 'Nama group tidak boleh kosong.',
            'group_nama.unique' => 'Nama group ini sudah ada.',
            'group_deskripsi.required' => 'Deskripsi tidak boleh kosong.',
            'group_default_menu.required' => 'Default menu tidak boleh kosong.'
        ];
    }

    protected function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'msg_title' => 'Gagal',
            'msg_body' => json_encode($validator->errors())
        ], 422));
    }
}
