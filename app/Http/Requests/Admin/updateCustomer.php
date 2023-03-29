<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class updateCustomer extends FormRequest
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
            'user_first_name'=>'required',
            'user_last_name'=>'required',
            'user_dob' => 'required',
            'user_gender' => 'required',
            //'email' => 'required',
            'user_mobile_no' => 'required',
            'address_pin_code' => 'required',
            'address_state' => 'required',
            'address_city' => 'required',
            //'user_address_line' => 'required',
            'user_address_line_1' => 'required',
            //'user_address_line_2' => 'required',
            //'user_address_line_3' => 'required',
            // 'user_profile_pic' => 'required',
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
            'user_first_name.required' => 'Please enter first name',
            'user_last_name.required' => 'Please enter last name',
            'user_dob.required' => 'Please select date of birth',
            'user_gender.required' => 'Please select gender',
            //'email.required' => 'Please enter email',
            'user_mobile_no.required' => 'Please enter mobile number',
            'address_pin_code.required' => 'Please enter pincode',
            'address_state.required' => 'Please enter state',
            'address_city.required' => 'Please enter city',
            //'user_address_line.required' => 'Please enter address',
            'user_address_line_1.required' => 'Please enter address',
            //'user_address_line_2.required' => 'Please enter address',
            //'user_address_line_3.required' => 'Please enter address',
            // 'user_profile_pic.required' => 'Please upload profile picture',
        ];
    }
}
