<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Validator;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\storeSettingRequest;
use App\Models\Setting;
use App\Models\AdminBanks;
use Log;
use Session;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('setting_manage')) 
        {
            return abort(401);
        }
        else{
            $setting_charges = array();
            $setting_charges = Setting::where('active_sms_gateway','=','')->get()->toArray();
            $bank_details = AdminBanks::get()->toArray();
	    $active_sms_gateway = Setting::select('id','active_sms_gateway')->whereNotNull('active_sms_gateway')->where('active_sms_gateway','!=','')->first();
$overtimecharges = DB::table('ggt_overtime_charges')->get();
	    if($active_sms_gateway){
            	Session::flash('active_sms_gateway', $active_sms_gateway->active_sms_gateway);
		Session::flash('active_sms_gateway_id', $active_sms_gateway->id);
	    }
	    
            if(!empty($setting_charges) || !empty($bank_details)){
                return view('admin.setting.index', compact('overtimecharges','setting_charges','bank_details'))->with('success', 'setting row added successfully');
                
            }else{
                Log::error('error in add setting page');
                return view('admin.setting.index')->with('error', 'Opps! Something went wrong while adding.');
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(storeSettingRequest $request)
    {
        $validated = $request->validated();
        $setting_charge_type = null;
        if($request->has('setting_charge_type')){
            $setting_charge_type = $request->input('setting_charge_type') ==0 ? 'by per' : 'by value';
        }

        $setting = new Setting();
        $setting->setting_label = $request->input('setting_label');
        $setting->setting_charge_type = $setting_charge_type;
        $setting->setting_charge_amount = $request->input('setting_charge_amount');
        $setting->save();
        $setting_charges = array();
        $setting_charges = Setting::get()->toArray();
        $bank_details = AdminBanks::get()->toArray();
        if(!empty($setting) && !empty($bank_details)){
            return view('admin.setting.index', compact('setting_charges','bank_details'))->with('success', 'setting row added successfully');
            
        }else{
            Log::error('error in add setting page');
            return view('admin.setting.index')->with('error', 'Opps! Something went wrong while adding.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!empty($id)) {
            $setting = Setting::find($id);
            // Session::flash('idEditTab', 0);
            // Session::flash('idEdit', 0);
            $setting_charge_type = $setting->setting_charge_type == 'by per' ? 0 : 1;

            Session::flash('setting_id', $setting->id);
            Session::flash('setting_label', $setting->setting_label);
            Session::flash('setting_charge_type', $setting_charge_type);
            Session::flash('setting_charge_amount', $setting->setting_charge_amount);
            return redirect()->route('setting.index');
        }else{
            return redirect()->route('setting.index')->with('Failed', 'Something went wrong,request id not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        // if($request->isMethod('post')) {
            if(isset($request) && !empty($request->input('setting_id'))){
                $charge_type = $request->input('setting_charge_type');
                $charge_type = (int) $charge_type;
                $setting_charge_type = $charge_type ==0 ? 'by per' : 'by value';

                $newsetting = Setting::find($request->input('setting_id'));
                $newsetting->setting_label = $request->input('setting_label');
                $newsetting->setting_charge_type = $setting_charge_type; 
                $newsetting->setting_charge_amount = $request->input('setting_charge_amount');
                $newsetting->save();
                $setting_charges = Setting::get()->toArray();
                $bank_details = Setting::get()->toArray();

                return redirect()->route('setting.index')->with(['success', 'Setting information has been updated successfully message should be displayed.','bank_details' => $bank_details,'setting_charges' => $setting_charges]);
            }else{
                return redirect()->route('setting.index')->with('Failed', 'Something went wrong,request is not post request.');
            }
        // }else{
        //     return redirect()->route('setting.index')->with('Failed', 'Something went wrong,request is not post request.');
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function saveBankInfo(Request $request){
        Session::flash('idEditTab', 1);
        if($request->isMethod('post')) {
          if(isset($request->bank_details)){
            $requests = $request->bank_details;
            foreach ($requests as $key => $value) {
                $bank = new AdminBanks();
                $bank->name = $value['name'];
                $bank->account_num = $value['account_num'];
                $bank->ifsc_code = $value['ifsc_code'];
                $bank->bank_name = $value['bank_name'];
                $bank->branch_name = $value['branch_name'];
                $bank->city = $value['city'];
                $bank->save();
                $bank_details = AdminBanks::get()->toArray();
            }
            return redirect()->route('setting.index')->with(['success','Bank Added Successfully','bank_details' =>$bank_details]);
          }else{
            return redirect()->route('setting.index')->with('Failed', 'Something went wrong please try again.');
          } 
        }else{
            return redirect()->route('setting.index')->with('Failed', 'Something went wrong,is not a post request.');
        }
    }

    public function setDefaultBank(Request $request){
        Session::flash('idEditTab', 1);
        if ($request->isMethod('post')) {
            if(isset($request) && !empty($request->id)){
                $oldbank = AdminBanks::where('is_selected',1)->update(['is_selected' => 0]);
                $bank = AdminBanks::find($request->id);
                $bank->is_selected = 1;
                $bank->save();
                return json_encode(['status'=> 'success', 'response'=> true]);
            }else{
               return json_encode(['status'=> 'failed', 'response'=> false]);
            }
        }else{
            return json_encode(['status'=> 'failed', 'response'=> false]);
        }
    }
    public function checkisDeleteBank(Request $request){
        if ($request->isMethod('post')) {
            if(isset($request) && !empty($request->id)){
                $isExist = AdminBanks::where('id',$request->id)->where('is_selected',1)->exists();
                if($isExist){
                    return "true";
                }else{
                    return "false";
                }   
            }else{
               return "error";
            }
        }else{
            return "error";
        }
    }
    public function deleteBank(Request $request){
        Session::flash('idEditTab', 1);
        if ($request->isMethod('post')) {
            if(isset($request) && !empty($request->id)){
                $deletebank = AdminBanks::find($request->id)->delete();
                return json_encode(['status'=> 'success', 'response'=> true]);
            }else{
               return json_encode(['status'=> 'failed', 'response'=> false]);
            }
        }else{
            return json_encode(['status'=> 'failed', 'response'=> false]);
        }
    }
    public function editBankInfo($id){
        if (!empty($id)) {
            $bank = AdminBanks::find($id);
            Session::flash('idEditTab', 1);
            Session::flash('idEdit', 1);
            Session::flash('id', $bank->id);
            Session::flash('name', $bank->name);
            Session::flash('account_num', $bank->account_num);
            Session::flash('ifsc_code', $bank->ifsc_code);
            Session::flash('bank_name', $bank->bank_name);
            Session::flash('branch_name', $bank->branch_name);
            Session::flash('city', $bank->city);
            Session::flash('is_selected', $bank->is_selected);
            return redirect()->route('setting.index');
        }else{
            return redirect()->route('setting.index')->with('Failed', 'Something went wrong,request id not found.');    
        }
    }

    public function UpdateBankInfo(Request $request){
        if($request->isMethod('post')) {
            if(isset($request) && !empty($request->id)){
                $newbank = AdminBanks::find($request->id);
                $newbank->name = $request->input('name');
                $newbank->account_num = $request->input('account_num');
                $newbank->ifsc_code = $request->input('ifsc_code');
                $newbank->ifsc_code = $request->input('ifsc_code');
                $newbank->bank_name = $request->input('bank_name');
                $newbank->branch_name = $request->input('branch_name');
                $newbank->city = $request->input('city');
                $newbank->save();
                $setting_charges = Setting::get()->toArray();
                $bank_details = AdminBanks::get()->toArray();
                Session::flash('idEditTab', 1);
                return redirect()->route('setting.index')->with(['Failed', 'bank information has been updated successfully message should be displayed.','bank_details' => $bank_details,'setting_charges' => $setting_charges]);
            }else{
                return redirect()->route('setting.index')->with('Failed', 'Something went wrong,request is not post request.');
            }
        }else{
            return redirect()->route('setting.index')->with('Failed', 'Something went wrong,request is not post request.');
        }
    }

    public function switchSmsGateway(Request $request){
        $postData = $request->all();
        if(!empty($postData)){
	    if(isset($postData['edit_id'])){
                $setSmsGateway = Setting::where('id',$postData['edit_id'])->update([
                    'active_sms_gateway' => $postData['sms_gateway'],
                ]);
            }else{
            $setSmsGateway = Setting::create([
                'setting_label' => 'sms_gateway',
                'active_sms_gateway' => $postData['sms_gateway']
            ]);
	    }
            if($setSmsGateway){
                return redirect()->route('setting.index')->with(['success','Sms Gateway Switch Successfully']);
            }else{
                return redirect()->route('setting.index')->with('Failed', 'Something went wrong.');    
            }
        }else{
            return redirect()->route('setting.index')->with('Failed', 'Something went wrong, empty request.');   
        }
    }

    public function overtimeCharges(Request $request){
        $postData = $request->all();
        if(!empty($postData)){
            $ifexists = DB::table('ggt_overtime_charges')->where('overtime', $postData['overtime'])->first();//OvertimeCharges::where('overtime',$postData['overtime']);
            if($ifexists){
                $data = array(
                    'charges'=>$postData['charges_'.$postData['overtime']],
                    'updated_at'=>date('Y-m-d H:i:s')
                );
                $overtimecharges = DB::table('ggt_overtime_charges')->where('overtime', $postData['overtime'])->update($data);
                    // OvertimeCharges::where('id',$ifexists['edit_id'])->update([
                    //     'active_sms_gateway' => $postData['sms_gateway'],
                    // ]);
                }else{
                    $data = array(
                        'admin_id'=>Auth::user()->id,
                        'overtime'=>$postData['overtime'],
                        'charges'=>$postData['charges_'.$postData['overtime']],
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s')
                    );
                $overtimecharges = DB::table('ggt_overtime_charges')->insert($data);
                // OvertimeCharges::create([
                //     'setting_label' => 'sms_gateway',
                //     'active_sms_gateway' => $postData['sms_gateway']
                // ]);
            }
            if($overtimecharges){
                return redirect()->route('setting.index')->with(['success','Updated Overtime Charges Successfully']);
            }else{
                return redirect()->route('setting.index')->with('Failed', 'Something went wrong.');    
            }
        }else{
            return redirect()->route('setting.index')->with('Failed', 'Something went wrong, empty request.');   
        }
    }

}
