@extends('layouts.app')

<!-- Content Header (Page header) -->
@section('content-header')
    <h1>
        Orders
        <!--<small>(Vendors)</small>-->
    </h1>
    <ol class="breadcrumb">
    <li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
    <li>Roles</li>
    <li class="active">Edit</li>
    </ol>
@endsection

<!-- Main Content -->
@section('content')
    @if(session('success'))
        <!-- If password successfully show message -->
        <div class="row">
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        </div>
    @else
    <form method="POST" action="{{ route('deposite/update') }}">        
    @csrf
    <div class="panel panel-default">
        <div class="panel-heading">
            Create
        </div>

        <div class="panel-body">
            <div>
                <?php //print_r($depositlist); ?>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    
                    
                    <label for="op_pay_mobile_no" class="control-label">{{ __('Mobile Number*') }}</label>
                    
                    <input id="op_pay_id" type="hidden" class="form-control" name="op_pay_id" value="{{ $depositlist->first()->op_pay_id }}" required autofocus>


                    <input id="op_pay_mobile_no" type="text" class="form-control" name="op_pay_mobile_no" value="{{ $depositlist->first()->op_pay_mobile_no }}" required autofocus>

                    <p class="help-block"></p>
                    @if($errors->has('op_pay_mobile_no'))
                        <p class="help-block text-red">
                            {{ $errors->first('op_pay_mobile_no') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xs-6 form-group">
                    

                    <label for="op_first_name" class="control-label">{{ __('First Name*') }}</label>

                     <input id="op_first_name" type="text" class="form-control" name="op_first_name" value="{{ $depositlist->first()->op_first_name }}" required autofocus disabled="disabled">
                    
                    

                    <p class="help-block"></p>
                    @if($errors->has('op_first_name'))
                        <p class="help-block text-red">
                            {{ $errors->first('op_first_name') }}
                        </p>
                    @endif
                </div>

                 <div class="col-xs-6 form-group">
                    

                    <label for="op_last_name" class="control-label">{{ __('Last Name*') }}</label>

                     <input id="op_last_name" type="text" class="form-control" name="op_last_name" value="{{ $depositlist->first()->op_last_name }}" required autofocus disabled="disabled">
                    
                    

                    <p class="help-block"></p>
                    @if($errors->has('op_last_name'))
                        <p class="help-block text-red">
                            {{ $errors->first('op_last_name') }}
                        </p>
                    @endif
                </div>
            </div>

            
            <div class="row">
                <div class="col-xs-12 form-group">
                    
                    <label for="op_pay_email" class="control-label">{{ __('Email*') }}</label>
                   <input id="op_pay_email" type="text" class="form-control" name="op_pay_email" value="{{ $depositlist->first()->op_pay_email }}" required autofocus disabled="disabled">
                    <p class="help-block"></p>
                    @if($errors->has('op_pay_email'))
                        <p class="help-block text-red">
                            {{ $errors->first('op_pay_email') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 form-group">
                    {{ $depositlist->first()->op_pay_mode }}
                   <label for="op_pay_mode" class="control-label">{{ __('Payment Mode*') }}</label>
            <select id="op_pay_mode"  class="form-control" name="op_pay_mode"  required autofocus disabled="disabled">
                   @if($depositlist->first()->op_pay_mode=="cash")
                   <option value="{{ $depositlist->first()->op_pay_mode }}" selected="selected">Cash</option>
                   <option value="cheque" >Cheque</option>
                   @else
                   <option value="cash" >Cash</option>
                   <option value="{{ $depositlist->first()->op_pay_mode }}" selected="selected">Cheque</option>
                   @endif

                    
                    </select>
                   
                </div>
            </div>


            
            <div class="row" id="cash" style="display:none;">
                 @if($depositlist->first()->op_pay_mode=="cash")
                <div class="col-xs-12 form-group">
                    

                    <label for="op_pay_receipt_no" class="control-label">{{ __('Receipt number*') }}</label>

                     <input id="op_pay_receipt_no" type="text" class="form-control" name="op_pay_receipt_no" value="{{ $depositlist->first()->op_pay_receipt_no }}" required autofocus>
                    
                    

                    <p class="help-block"></p>
                    @if($errors->has('op_pay_receipt_no'))
                        <p class="help-block text-red">
                            {{ $errors->first('op_pay_receipt_no') }}
                        </p>
                    @endif
                </div>

                 <div class="col-xs-12 form-group">
                    

                       

                    <label for="op_pay_amount" class="control-label">{{ __('Amount*') }}</label>

                    <input id="op_pay_amount" type="text" class="form-control" name="op_pay_amount" value="{{ $depositlist->first()->op_pay_amount }}" required autofocus>
                    
                    

                    <p class="help-block"></p>
                    @if($errors->has('op_pay_amount'))
                        <p class="help-block text-red">
                            {{ $errors->first('op_pay_amount') }}
                        </p>
                    @endif
                </div>

                 <div class="col-xs-12 form-group">
                    

                    <label for="op_pay_receipt_date" class="control-label">{{ __('Date *') }}</label>

                     <input id="op_pay_receipt_date" type="text" class="form-control" name="op_pay_receipt_date" value="{{ $depositlist->first()->op_pay_receipt_date }}" required autofocus>
                    
                    

                    <p class="help-block"></p>
                    @if($errors->has('op_pay_receipt_date'))
                        <p class="help-block text-red">
                            {{ $errors->first('op_pay_receipt_date') }}
                        </p>
                    @endif
                </div>
                @endif
            </div>
            
            <div class="row" id="cheque" style="display:none;">
             @if($depositlist->first()->op_pay_mode=="cheque")
                <div class="col-xs-6 form-group">
                    

                    <label for="op_order_cheque_no" class="control-label">{{ __('Cheque number*') }}</label>

                     <input id="op_order_cheque_no" type="text" class="form-control" name="op_order_cheque_no" value="{{ $depositlist->first()->op_order_cheque_no }}" required autofocus>
                    
                    

                    <p class="help-block"></p>
                    @if($errors->has('op_order_cheque_no'))
                        <p class="help-block text-red">
                            {{ $errors->first('op_order_cheque_no') }}
                        </p>
                    @endif
                </div>

                 <div class="col-xs-6 form-group">
                    

                    <label for="op_pay_amount" class="control-label">{{ __('Amount*') }}</label>

                    <input id="op_pay_amount" type="text" class="form-control" name="op_pay_amount"  value="{{ $depositlist->first()->op_pay_amount }}"required autofocus>
                    
                    

                    <p class="help-block"></p>
                    @if($errors->has('op_pay_amount'))
                        <p class="help-block text-red">
                            {{ $errors->first('op_pay_amount') }}
                        </p>
                    @endif
                </div>

                 <div class="col-xs-6 form-group">
                    

                    <label for="op_order_cheque_bank" class="control-label">{{ __('Bank Name *') }}</label>

                     <input id="op_order_cheque_bank" type="text" class="form-control" name="op_order_cheque_bank" value="{{ $depositlist->first()->op_order_cheque_bank }}" required autofocus>
                    
                    

                    <p class="help-block"></p>
                    @if($errors->has('op_order_cheque_bank'))
                        <p class="help-block text-red">
                            {{ $errors->first('op_order_cheque_bank') }}
                        </p>
                    @endif
                </div>

                <div class="col-xs-6 form-group">
                    

                    <label for="op_order_cheque_ifsc" class="control-label">{{ __('IFSC Code *') }}</label>

                     <input id="op_order_cheque_ifsc" type="text" class="form-control" name="op_order_cheque_ifsc" value="{{ $depositlist->first()->op_order_cheque_ifsc }}" required autofocus>
                    
                    

                    <p class="help-block"></p>
                    @if($errors->has('op_order_cheque_ifsc'))
                        <p class="help-block text-red">
                            {{ $errors->first('op_order_cheque_ifsc') }}
                        </p>
                    @endif
                </div>
                <div class="col-xs-12 form-group">
                    

                    <label for="op_order_cheque_date" class="control-label">{{ __('Date *') }}</label>

                     <input id="op_order_cheque_date" type="text" class="form-control" name="op_order_cheque_date" value="{{ $depositlist->first()->op_order_cheque_date }}" required autofocus>
                    
                    

                    <p class="help-block"></p>
                    @if($errors->has('op_order_cheque_date'))
                        <p class="help-block text-red">
                            {{ $errors->first('op_order_cheque_date') }}
                        </p>
                    @endif
                </div>
                @endif
            </div>
</div>
</div>
    <button type="submit" class="btn btn-danger">
        {{ __('Save') }}
    </button>
    </form>

        @if($depositlist->first()->op_pay_mode=="cash")
        
        <script type="text/javascript">
            
            $('#cash').show();
            $('#cheque').hide();
        </script>

        @else

        <script type="text/javascript">
            $('#cash').hide();
            $('#cheque').show();
        </script>       
        
        @endif
    @endif
@endsection

<!-- JS scripts for this page only -->
@section('javascript')
@endsection

