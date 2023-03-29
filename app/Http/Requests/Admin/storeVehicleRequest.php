<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class storeVehicleRequest extends FormRequest
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
            'veh_make_model_type'=>'required',
            'veh_model_name'=>'required',
            'veh_wheel_type'=>'required',
            'veh_capacity'=>'required',
            'veh_dimension'=>'required',
            'veh_type'=>'required',
            'veh_city'=>'required',
            'veh_registration_no'=>'required',
            'veh_color'=>'required',
            'veh_fuel_type'=>'required',
            'veh_base_charge'=>'required',
            // 'veh_per_km'=>'required',
            'veh_is_online'=>'required',
            'is_active'=>'required',
            'veh_loader_available'=>'required',
            // 'veh_images' => 'required',
	    'lat' => 'required',
            //'lng' => 'required',
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
            'veh_make_model_type.required' => 'Please select make',
            'veh_model_name.required' => 'Please select model name',
            'veh_wheel_type.required' => 'Please select wheel type',
            'veh_capacity.required' => 'Please select capacity',
            'veh_dimension.required' => 'Please select dimension',
            'veh_type.required' => 'Please select body type',
            'veh_city.required' => 'Please enter base station / stand',
            'veh_registration_no.required' => 'Please enter vehicles registration no',
            'veh_color.required' => 'Please select vehicle color',
            'veh_fuel_type.required' => 'Please select fuel type',
            'veh_base_charge.required' => 'Please enter minimum charge',
            // 'veh_per_km.required' => 'Please enter change per km',
            'veh_is_online.required' => 'Please select vehicle offline / online status',
            'is_active.required' => 'Please select vehicle active / inactive status',
            'veh_loader_available.required' => 'Please select loader is available',
            // 'veh_images.required' => 'Please upload vehicle images',
	    'lat.required' => 'Lat long not set please select base station again',
	    //'lng.required' => 'Lat long not set please select base station again',
        ];
    }

}
