<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBusinessRequest extends FormRequest
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
            'op_bu_name' => 'required',
            'op_bu_address_line_3' => 'required',
            'op_bu_landmark' => 'required',
            'op_bu_address_line_1' => 'required',
            'op_bu_address_pin_code' => 'required',
            'op_bu_address_state' => 'required',
            'op_bu_address_city' => 'required',
            //'op_bu_pan_no' => 'required',
            'op_payment_mode' => 'required',
        ];
    }
}
