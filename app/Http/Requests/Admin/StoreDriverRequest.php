<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreDriverRequest extends FormRequest
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
            'driver_first_name' => 'required',
            'driver_mobile_number' => 'required',
            'driver_is_online' => 'required',
            'working_shift_days' => 'required',
            'working_shift_time' => 'required',
        ];
    }
}
