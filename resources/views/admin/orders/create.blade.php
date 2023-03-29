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

<form method="POST" action="{{ route('deposite/store') }}" name="form1">    
    @csrf
    <div class="panel panel-default">
        <div class="panel-heading">
            Create
        </div>

        <div class="panel-body">
         
            <div class="row">
                <div class="col-xs-12 form-group">
                    
                    
                    <label for="op_pay_mobile_no" class="control-label">{{ __('Mobile Number*') }}</label>
                    
                    <input id="op_pay_mobile_no" type="text" class="form-control" name="op_pay_mobile_no"   value="{{ old('op_pay_mobile_no') }}" required onchange="checkMobileNo()" autofocus >
                   
                </div>
            </div>

            <div class="row">
                <div class="col-xs-6 form-group">
                    

                    <label for="op_first_name" class="control-label">{{ __('First Name*') }}</label>

                     <input id="op_first_name" type="text" class="form-control" name="op_first_name" value="{{ old('op_first_name') }}" required autofocus>
                    
                    

                    <p class="help-block"></p>
                    @if($errors->has('op_first_name'))
                        <p class="help-block text-red">
                            {{ $errors->first('op_first_name') }}
                        </p>
                    @endif
                </div>

                 <div class="col-xs-6 form-group">
                    

                    <label for="op_last_name" class="control-label">{{ __('Last Name*') }}</label>

                     <input id="op_last_name" type="text" class="form-control" name="op_last_name" value="{{ old('op_last_name') }}" required autofocus>
                    
                    

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
                    
                    <label for="op_email" class="control-label">{{ __('Email*') }}</label>
                   <input id="op_email" type="Email" class="form-control" name="op_email" value="{{ old('op_email') }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('op_email'))
                        <p class="help-block text-red">
                            {{ $errors->first('op_email') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 form-group">
                    
                   <label for="op_pay_mode" class="control-label">{{ __('Payment Mode*') }}</label>
            <select id="op_pay_mode"  class="form-control" name="op_pay_mode"  autofocus value="{{ old('op_pay_mode') }}">
                   <option value="">--- please select ---</option>
                   <option value="cash">Cash</option>
                   <option value="cheque">Cheque</option>

                    
                    </select>
                   
                </div>
            </div>

            
            
            <div class="row" id="cash" style="display:none;">
                Cash details
                <div class="col-xs-12 form-group">
                    

                    <label for="op_pay_receipt_no" class="control-label">{{ __('Receipt number*') }}</label>

                     <input id="op_pay_receipt_no" type="text" class="form-control" name="op_pay_receipt_no" value="{{ old('op_pay_receipt_no') }}" autofocus>
                    
                    

                    <p class="help-block"></p>
                    @if($errors->has('op_pay_receipt_no'))
                        <p class="help-block text-red">
                            {{ $errors->first('op_pay_receipt_no') }}
                        </p>
                    @endif
                </div>

                 <div class="col-xs-12 form-group">
                   
                   <label for="op_pay_amount1" class="control-label">{{ __('Amount*') }}</label>

                    <input id="op_pay_amount1" type="text" class="form-control" name="op_pay_amount1" value="{{ old('op_pay_amount1') }}"  autofocus>
                    
                    

                    <p class="help-block"></p>
                    @if($errors->has('op_pay_amount1'))
                        <p class="help-block text-red">
                            {{ $errors->first('op_pay_amount1') }}
                        </p>
                    @endif
                </div>

                 <div class="col-xs-12 form-group">
                    

                    <label for="op_pay_receipt_date" class="control-label">{{ __('Date *') }}</label>

                     <input id="op_pay_receipt_date" type="text" class="form-control" name="op_pay_receipt_date" value="{{ old('op_pay_receipt_date') }}"  autofocus>
                    
                    

                    <p class="help-block"></p>
                    @if($errors->has('op_pay_receipt_date'))
                        <p class="help-block text-red">
                            {{ $errors->first('op_pay_receipt_date') }}
                        </p>
                    @endif
                </div>
            </div>
            
            <div class="row" id="cheque" style="display:none;">
            cheque details    
                <div class="col-xs-6 form-group">
                    

                    <label for="op_order_cheque_no" class="control-label">{{ __('Cheque number*') }}</label>

                     <input id="op_order_cheque_no" type="text" class="form-control" name="op_order_cheque_no" value="{{ old('op_order_cheque_no') }}" autofocus>
                    
                    

                    <p class="help-block"></p>
                    @if($errors->has('op_order_cheque_no'))
                        <p class="help-block text-red">
                            {{ $errors->first('op_order_cheque_no') }}
                        </p>
                    @endif
                </div>

                 <div class="col-xs-6 form-group">
                    

                    <label for="op_pay_amount" class="control-label">{{ __('Amount*') }}</label>

                    <input id="op_pay_amount" type="text" class="form-control" name="op_pay_amount" value="{{ old('op_pay_amount') }}" autofocus>
                    
                    

                    <p class="help-block"></p>
                    @if($errors->has('op_pay_amount'))
                        <p class="help-block text-red">
                            {{ $errors->first('op_pay_amount') }}
                        </p>
                    @endif
                </div>

                 <div class="col-xs-6 form-group">
                    

                    <label for="op_order_cheque_bank" class="control-label">{{ __('Bank Name *') }}</label>

                     <input id="op_order_cheque_bank" type="text" class="form-control" name="op_order_cheque_bank" value="{{ old('op_order_cheque_bank') }}"  autofocus>
                    
                    

                    <p class="help-block"></p>
                    @if($errors->has('op_order_cheque_bank'))
                        <p class="help-block text-red">
                            {{ $errors->first('op_order_cheque_bank') }}
                        </p>
                    @endif
                </div>

                <div class="col-xs-6 form-group">
                    

                    <label for="op_order_cheque_ifsc" class="control-label">{{ __('IFSC Code *') }}</label>

                     <input id="op_order_cheque_ifsc" type="text" class="form-control" name="op_order_cheque_ifsc" value="{{ old('op_order_cheque_ifsc') }}" autofocus>
                    
                    

                    <p class="help-block"></p>
                    @if($errors->has('op_order_cheque_ifsc'))
                        <p class="help-block text-red">
                            {{ $errors->first('op_order_cheque_ifsc') }}
                        </p>
                    @endif
                </div>
                <div class="col-xs-12 form-group">
                    

                    <label for="op_order_cheque_date" class="control-label">{{ __('Date *') }}</label>

                     <input id="op_order_cheque_date" type="text" class="form-control" name="op_order_cheque_date"  value="{{ old('op_order_cheque_date') }}" autofocus>
                    
                    <p class="help-block"></p>
                    @if($errors->has('op_order_cheque_date'))
                        <p class="help-block text-red">
                            {{ $errors->first('op_order_cheque_date') }}
                        </p>
                    @endif
                </div>
            </div>           
    </div>
</div>
    <button type="submit" class="btn btn-danger" onClick='submitDetailsForm()'>
        {{ __('Save') }}
    </button>
</form>

<script language="javascript" type="text/javascript">
    function submitDetailsForm() {
       alert($("#op_pay_amount").val());
    }
</script>

  

    
    <script type="text/javascript">
      $('#op_pay_mode').on('change', function() {
    //alert( this.value ); 
  // or $(this).val()
  if(this.value == "cash") {
    $('#cash').show();
    $('#cheque').hide();
  } else if(this.value == "cheque"){
    $('#cash').hide();
    $('#cheque').show();
  }
  else
  {
     $('#cash').hide();
    $('#cheque').hide();
  }
});
</script>

<!--        Ajax control-label -->

<script>
function checkMobileNo() 
{
  
  var op_pay_mobile_no=$("#op_pay_mobile_no").val();

  {
    $.ajax({
        url :"{{ route('getmobiledetails') }}",
        method:"POST",
        data: {
        "_token": "{{ csrf_token() }}",
        "op_pay_mobile_no": op_pay_mobile_no
        },
        success : function(data){
            alert(data);
        }
    });

  }
}
</script>

    @endif
@endsection

<!-- JS scripts for this page only -->
@section('javascript')
@endsection

