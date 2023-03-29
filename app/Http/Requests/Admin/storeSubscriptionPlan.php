<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class storeSubscriptionPlan extends FormRequest
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
            'subscription_type_name'=>'required',
            'subscription_amount'=>'required',
            'subscription_validity_type'=>'required',
            'subscription_business_rs' => 'required_if:subscription_validity_type,no',
            // 'subscription_business_rs'=>'required|numeric',
            // 'subscription_expected_enquiries'=>'required|numeric',
            'subscription_expected_enquiries' => 'required_if:subscription_validity_type,yes',
            'subscription_veh_wheel_type'=>'required',
            // 'subscription_validity_days'=>'required',
            // 'subscription_validity_from'=>'required',
            // 'subscription_validity_to'=>'required',
            // 'is_active'=>'required',
            'subscription_type_name'=>'required',
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
            'subscription_type_name.required' => 'Please enter scheme name',
            'subscription_amount.required' => 'Please enter amount',
            'subscription_validity_type.required' => 'Please enter expected value',
            'subscription_business_rs.required_if' => 'Please enter business Rs.',
            'subscription_expected_enquiries.required_if' => 'Please enter business enquiries',
            // 'subscription_expected_enquiries.numeric' => 'Please enter number',
            'subscription_veh_wheel_type.required' => 'Please select wheel type',
            'subscription_validity_days.required' => 'Please enter number of days',
            'subscription_validity_from.required' => 'Please select from date',
            'subscription_validity_to.required' => 'Please select to date',
            'is_active.required' => 'Please select to plan status',
            'subscription_type_name.required' => 'Please enter scheme name',
        ];
    }
}
