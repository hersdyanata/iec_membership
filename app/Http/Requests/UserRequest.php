<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            'name' => 'required',
            'email' => [
                'required',
                Rule::unique('users')->ignore($this->id, 'id')
            ],
            'username' => [
                'required',
                'max:30',
                Rule::unique('users')->ignore($this->id, 'id')
            ],
            'group_id' => 'required',
            'password' => 'required',
        ];
    }

    public function messages(){
        return [
            'name.required' => 'Nama tidak boleh kosong',
            'email.required' => 'Email tidak boleh kosong',
            'email.unique' => 'Email sudah terdaftar',
            'username.required' => 'Username tidak boleh kosong',
            'username.max' => 'Username maximal 30 karakter',
            'username.unique' => 'Username sudah terdaftar',
            'group_id.required' => 'Group tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong'
        ];
    }

    protected function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'msg_title' => 'Gagal',
            'msg_body' => json_encode($validator->errors())
        ], 422));
    }
}
