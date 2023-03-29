<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class storeSettingRequest extends FormRequest
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
            'setting_label'=>'required',
            'setting_charge_type'=>'required',
            'setting_charge_amount'=>'required',
        ];
    }

    /**
    * Get the error messages for the defined validation rules.
    *
    * @return array
    */
    public function messages()
    {
        return [
            'setting_label.required' => 'Please enter title',
            'setting_charge_type.required' => 'Please select type',
            'setting_charge_amount.required' => 'Please enter amount type',
        ];
    }
}
