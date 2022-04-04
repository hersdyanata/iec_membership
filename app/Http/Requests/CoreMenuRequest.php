<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CoreMenuRequest extends FormRequest
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
            'menu_div_id' => 'required',
            'menu_nama_ina' => [
                'required',
                // Rule::unique('dev_menu')->ignore($this->menu_id, 'menu_id')
            ],
            'menu_nama_eng' => [
                'required',
                // Rule::unique('dev_menu')->ignore($this->menu_id, 'menu_id')
            ],
            'menu_controller' => [
                'required',
                // Rule::unique('dev_menu')->ignore($this->menu_id, 'menu_id')
            ],
            'menu_route_name' => [
                'required',
                // Rule::unique('dev_menu')->ignore($this->menu_id, 'menu_id')
            ],
            'menu_publish_ke_user' => 'required',
            'menu_order' => 'required'
        ];
    }

    public function messages(){
        return [
            'menu_div_id.required' => 'Divider tidak boleh kosong.',
            'menu_nama_ina.required' => 'Nama menu dalam bahasa indonesia tidak boleh kosong.',
            // 'menu_nama_ina.unique' => 'Nama menu dalam bahasa indonesia sudah ada.',
            'menu_nama_eng.required' => 'Nama menu dalam bahasa inggris tidak boleh kosong.',
            // 'menu_nama_eng.unique' => 'Nama menu dalam bahasa inggris sudah ada.',
            'menu_controller.required' => 'Controller tidak boleh kosong. Jika ini adalah menu induk, isikan tanda (#).',
            // 'menu_controller.unique' => 'Controller dengan nama ini sudah ada.',
            'menu_route_name.required' => 'Nama route tidak boleh kosong.',
            // 'menu_route_name.unique' => 'Nama route sudah ada.',
            'menu_publish_ke_user.required' => 'Silahkan putuskan menu ini dipublish ke user atau tidak.',
            'menu_order.requried' => 'Urutan menu tidak boleh kosong.'
        ];
    }

    protected function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'msg_title' => 'Gagal',
            'msg_body' => json_encode($validator->errors())
        ], 422));
    }
}
