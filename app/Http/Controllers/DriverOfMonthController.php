<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Operator;
use App\Models\Driver;
use App\Models\DriverOfMonth;
use Illuminate\Support\Facades\Auth;
use DB;
class DriverOfMonthController extends Controller
{
    public function __construct()
    {           
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //get all driver mobile numbers
        $driver_numbers = Driver::select('ggt_drivers.driver_mobile_number')->join('ggt_operator_users', 'ggt_operator_users.op_user_id', '=', 'ggt_drivers.op_user_id')->where('ggt_operator_users.op_is_verified',1)->whereNull('ggt_drivers.deleted_at')->whereNull('ggt_operator_users.deleted_at')->get();
        //->where('ggt_drivers.is_active',1)

        return view('admin.driverofmonth.create', compact('driver_numbers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $postData = $request->all();
        if(!empty($postData)){
            
            //get admin details
            $user = Auth::getUser();
            $admin_id = $user->id;
            $admin_email = $user->email;
            
            $input = array(
                'admin_id' => $admin_id,
                'admin_email' => $admin_email,
                'op_mobile_no' => $request->selected_number,
                'comment' => $request->driver_comment,
            );
            
            $user = DriverOfMonth::create($input);
            return redirect()->route('driverhome.index')->with('success', 'Driver of the month updated successfully.');
        }else{
            Log::warning("empty request:DriverOfMonthController");
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
        if(!empty($id)){
            $driver_data = DriverOfMonth::select('id','op_mobile_no','comment')->where('id',$id)->first();
            $driver_numbers = Driver::select('driver_mobile_number')->where('is_active',1)->where('driver_is_verified',1)->whereNull('deleted_at')->get();
            return view('admin.driverofmonth.edit', compact('driver_data','driver_numbers'));
        }else{
            Log::warning("empty request:DriverOfMonthController");
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
        $postData = $request->all();
        if(!empty($postData)){
            
            //get admin details
            $user = Auth::getUser();
            $admin_id = $user->id;
            $admin_email = $user->email;
            
            $input = array(
                'admin_id' => $admin_id,
                'admin_email' => $admin_email,
                'op_mobile_no' => $request->selected_number,
                'comment' => $request->driver_comment,
            );
            
            $image = DriverOfMonth::where('id',$id)->update($input);
            return redirect()->route('driverhome.index')->with('success', 'Driver of the month updated successfully.');
        }else{
            Log::warning("empty request:DriverOfMonthController");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req)
    {
        
    }
    public function deleteDriverOfMonth(Request $request)
    {
        $postData = $request->all();
        if(!empty($postData)){
            $driverofmonth=DB::table('ggt_driver_of_month')
                        ->where('id','=',$postData['selectid'])            
                        ->update(['deleted_at'=>date('Y-m-d H:i:s')]);  
            // $driverofmonth = DriverOfMonth::findOrFail($postData['selectid']);
            // $driverofmonth->delete();
            return json_encode(['status' => 'success', 'message' => 'Deleted successfully!']);   
        }
    }

}
