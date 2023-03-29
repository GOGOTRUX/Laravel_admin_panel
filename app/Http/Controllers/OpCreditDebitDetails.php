<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OperatorCreditDebitNotes;
use App\Models\Operator;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CustomAwsController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\OperatorPaymentsController;
use Config;
use Session;
use Auth;
use Carbon\Carbon;

class OpCreditDebitDetails extends Controller
{
    public function __construct()
    {
        $this->aws = new CustomAwsController;
        $this->notifiy = new NotificationController;
        $this->commonFunction = new CommonController;
        $this->paymentController = new OperatorPaymentsController;
        $this->today = Carbon::today()->toDateString();
        $this->bucketname = Config::get('custom_config_file.bucket-name');
        $this->amazon_s3_url = Config::get('custom_config_file.amazon_s3_url');
    }

    public function index()
    {
        $notesDetails = OperatorCreditDebitNotes::select('id', 'created_at', 'amount', 'transaction_id', 'party_id', 'created_by', 'approved_by', 'status')->orderby('created_at', 'desc')->get()->toArray();
        if(!empty($notesDetails)){
            foreach ($notesDetails as $key => $value) {
                $notesDetails[$key]['created_at'] = Carbon::parse($value['created_at'])->format('Y-m-d H:i:s');
            }
        }
        $op_details = $this->paymentController->operator_details();
        // dd($notesDetails);
        return view('admin.payments.operatorPayments.credit_debit_note', compact('notesDetails', 'op_details'));
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
    public function store(Request $request)
    {
        // dd($request->all());
        // dd(Auth::user());
        $postdata = $request->all();
        if($request->has('send_for_approval')){
            $postdata['status'] = 'send_for_approval';
        }
        else{
            $postdata['status'] = 'submitted';
        }
        $postdata['created_by'] = Auth::user()->name;
        $postdata['created_admin_id'] = Auth::user()->id;
        $result = OperatorCreditDebitNotes::create($postdata);
        if($result){
            $status = 'success';
            $response = 'Payment note has been saved!!!.';
        }
        else{
            $status = 'failed';
            $response = 'Something went wrong!!!.';
        }
        return redirect()->back()->with($status, $response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Session::put('credit_debit_note_id', $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // dd($id);
        $notesDetails = OperatorCreditDebitNotes::where('id', decrypt($id))->first();
        // if(!empty($notesDetails)){
        //     foreach ($notesDetails as $key => $value) {
        //         $notesDetails[$key]['created_at'] = Carbon::parse($value['created_at'])->format('Y-m-d H:i:s');
        //     }
        // }
        $op_details = $this->paymentController->operator_details();
        // dd($notesDetails);
        $serching_param = $notesDetails['op_uid'];
        $op_user = Operator::select('op_user_id','op_mobile_no','op_first_name','op_last_name','op_email','op_username', 'op_deposit', 'op_uid')
            ->where(function($query) use ($serching_param) {
                $query->where('op_uid', 'LIKE', '%'.$serching_param.'%');
            })
            ->where('op_uid', '!=', null)
            ->first();
        if(!empty($serching_param)){
            $op_balance = $this->paymentController->getOperatorAccountBalance($op_user['op_uid']);
            $op_user['credit_balance'] = $op_balance['credit_balance'];
            $op_user['debit_balance'] = $op_balance['debit_balance'];
        }
        else{
            $op_user['credit_balance'] = null;
            $op_user['debit_balance'] = null;
            Log::warning("UID not Set");
        }
        // dd($notesDetails);
        return view('admin.payments.operatorPayments.edit_credit_debit_note', compact('notesDetails', 'op_details', 'op_user'));
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
        // dd($request->all());
        $postdata = $request->except(['_method', '_token', 'operator_id', 'op_name', 'op_credit_bal', 'op_debit_bal', 'op_id', 'send_for_approval']);
        // $postdata;
        if($request->has('send_for_approval')){
            $postdata['status'] = 'send_for_approval';
        }
        else{
            $postdata['status'] = 'submitted';
        }
        $postdata['created_by'] = Auth::user()->name;
        $postdata['created_admin_id'] = Auth::user()->id;
        // dd($postdata);
        $result = OperatorCreditDebitNotes::where('id', decrypt($id))->update($postdata);
        if($result){
            $status = 'success';
            $response = 'Payment note has been updated!!!.';
        }
        else{
            $status = 'failed';
            $response = 'Something went wrong!!!.';
        }
        return redirect('/payment-credit-debit-note')->with('success', $response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $note = OperatorCreditDebitNotes::findOrFail($id);
        $note->delete();
        return json_encode(['status' => 'success', 'message' => 'Payment note has been deleted successfully!']);
    }

    public function approvePaymentNote($id)
    {
        $data = ['status' => 'approved', 'approved_by' => Auth::user()->name];
        $note = OperatorCreditDebitNotes::where('id', $id)->update($data);
        return json_encode(['status' => 'success', 'message' => 'Payment note has been approved successfully!']);
    }
}
