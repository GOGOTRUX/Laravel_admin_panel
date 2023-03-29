<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\DepositePayment;
use Validator;
use DB;

class DepositePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('deposite')) {
            return abort(401);
        }
        $depositlist=DepositePayment::all();
        
        return view('admin.deposit.index', compact('depositlist'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('deposite')) {
            return abort(401);
        }
        $bankname=DB::table('ggt_op_bank_list')->select('id','op_bank_name')->get();
        
         return view('admin.deposit.create',compact('bankname'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! Gate::allows('deposite')) {
            return abort(401);
        }

        $operatordetails=DB::table('ggt_operator_users')->select('op_user_id','op_username')
        ->where('op_user_id','=',$request->input('op_user_id'))->get();
        
        //echo dd($request->all());
        if($request->op_pay_mode=="Cash")
        {
           
             /*
            $rules =[            

                    'op_order_receipt_date' => 'date|before:tomorrow',
            ];
            
            $messages =[                           
                'op_order_receipt_date.before'=>'The Future date can not be accepted.',

            ];
            
            $validator = Validator::make($request->all(), $rules,$messages);
            
            if ($validator->fails()) 
            {
                        return redirect('deposite/create')
                        ->withErrors($validator)
                        ->withInput();
            }
        */
        $deposite=new DepositePayment();
        $deposite->op_user_id=$request->input('op_user_id');
        $deposite->op_order_username=$operatordetails->first()->op_username;
        $deposite->op_order_email=$request->input('op_email');
        $deposite->op_order_mobile_no=$request->input('op_pay_mobile_no');
        $deposite->op_order_mode=$request->input('op_pay_mode');        
        $deposite->op_order_receipt_no=$request->input('op_pay_receipt_no');
        $deposite->op_order_amount=$request->input('op_pay_amount1');
        $deposite->op_order_receipt_date=$request->input('op_pay_receipt_date');

        $deposite->save();

        return redirect()->route('deposite.index')->with('success', 'New deposite has been created successfully!');
        
        }
        else if($request->op_pay_mode=="Cheque")
        {
        
        /*
         $rules =[            
                            
                'op_order_cheque_date' => 'before:tomorrow',                
            ];
            
            $messages =[   
               
                'op_order_cheque_date.before'=>'The Future date can not be accepted.',

            ];

            $validator = Validator::make($request->all(), $rules,$messages);
            
            
        
            if ($validator->fails()) 
            {
            return redirect('deposite/create')
                        ->withErrors($validator)
                        ->withInput();
            }
        */

        $deposite=new DepositePayment();
        $deposite->op_user_id=$request->input('op_user_id');        
        $deposite->op_order_email=$request->input('op_email');
        $deposite->op_order_mobile_no=$request->input('op_pay_mobile_no');
        $deposite->op_order_mode=$request->input('op_pay_mode');                
        $deposite->op_order_username=$operatordetails->first()->op_username;
        $deposite->op_order_cheque_no=$request->input('op_order_cheque_no');
        $deposite->op_order_cheque_amount=$request->input('op_pay_amount');
        $deposite->op_order_cheque_bank=$request->input('op_order_cheque_bank');
        $deposite->op_order_cheque_ifsc=$request->input('op_order_cheque_ifsc');
        $deposite->op_order_cheque_date=$request->input('op_order_cheque_date');
        $deposite->save();
        
         return redirect()->route('deposite.index')->with('success', 'New deposite has been created successfully!');

        }
    }
    public function getifsccode(Request $request)
    {
        
        $ifsccode=DB::table('ggt_op_bank_list')->select('op_bank_ifsc','id')->where('op_bank_name','=',$request->op_order_cheque_bank)->get();
        return $ifsccode->first()->op_bank_ifsc;
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\DepositePayment  $depositePayment
     * @return \Illuminate\Http\Response
     */
    public function show(DepositePayment $depositePayment)
    {
        //
    }
     /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DepositePayment  $depositePayment
     * @return \Illuminate\Http\Response
     */
     public function softdelete(Request $request,$id)
    {

        if (! Gate::allows('deposite')) {
            return abort(401);
        }

        $deposit=DB::table("ggt_op_deposite_payment")
            ->where('op_order_id','=',$request->id)            
            ->update([
                        'deleted_at'=>date('Y-m-d H:i:s'),
                        
                    ]); 
          return redirect()->route('deposite.index')->with('success', 'Deposite has been deleted successfully!');  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DepositePayment  $depositePayment
     * @return \Illuminate\Http\Response
     */

    public function edit(Request $request,$id)
    {
        if (! Gate::allows('deposite')) {
            return abort(401);
        }

        $bankname=DB::table('ggt_op_bank_list')->select('id','op_bank_name')->get();
        $depositlist=DB::table('ggt_op_deposite_payment')
                    ->join('ggt_operator_users','ggt_op_deposite_payment.op_user_id','=','ggt_operator_users.op_user_id')                    
                    ->select('ggt_op_deposite_payment.*','op_first_name','op_last_name')
                    ->where('ggt_op_deposite_payment.op_order_id','=',$id)
                    ->get();
                
        return view('admin.deposit.edit', compact('depositlist','bankname'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DepositePayment  $depositePayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        
        if (! Gate::allows('deposite')) 
        {
            return abort(401);
        }
        

        if($request->op_order_mode=="Cash")
        {
            
            $rules =[            
             
                    'op_order_amount' =>'numeric',                     
                    'op_order_receipt_no' => 'required',
                    'op_order_receipt_date' => 'before:tomorrow',
                    ];
            
            $messages =[            
             'op_order_amount.numeric'=>'The amount may only contain numbers.',                
                'op_order_receipt_no'=>'The receipt fields required.',
                'op_order_receipt_date.before'=>'The Future date can not be accepted.',

                    ];

            $validator = Validator::make($request->all(), $rules,$messages);
            
            if ($validator->fails()) 
            {
                    return redirect('deposite/edit/'.$request->op_order_id)
                    ->withErrors($validator)
                    ->withInput();
            }
        

            $deposit=DB::table("ggt_op_deposite_payment")
                        ->where('op_order_id','=',$request->op_order_id)
                        ->update([
                        'op_order_receipt_no'=> $request->op_order_receipt_no,
                        'op_order_amount'=> $request->op_order_amount,
                        'op_order_receipt_date'=> $request->op_order_receipt_date,
                                ]); 

            return redirect()->route('deposite.index')->with('success', 'Deposite has been updated successfully!');  
                      
            }
            else if($request->op_order_mode=="Cheque")
            {

            $rules =[                         
                    'op_order_cheque_date' => 'before:tomorrow',
                        ];
            
            $messages =[            
                'op_order_cheque_date.before'=>'The Future date can not be accepted.',

                        ];

                    $validator = Validator::make($request->all(), $rules,$messages);
            
                    if ($validator->fails()) 
                    {
                        return redirect('deposite/edit/'.$request->op_order_id)
                        ->withErrors($validator)
                        ->withInput();
                    }

            
            

            $deposit=DB::table("ggt_op_deposite_payment")
            ->where('op_order_id','=',$request->op_order_id)
            ->update([
                        'op_order_cheque_no'=> $request->op_order_cheque_no,
                        'op_order_amount'=> $request->op_order_amount,
                        'op_order_cheque_bank'=> $request->op_order_cheque_bank,
                        'op_order_cheque_ifsc'=> $request->op_order_cheque_ifsc,
                        'op_order_cheque_date'=> $request->op_order_cheque_date,
                    ]);  

            return redirect()->route('deposite.index')->with('success', 'Deposite has been updated successfully!');  

            }        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DepositePayment  $depositePayment
     * @return \Illuminate\Http\Response
     */

    public function destroy(DepositePayment $depositePayment)
    {
        //
    }
   
}
