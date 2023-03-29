
@extends('layouts.app')

<!-- Content Header (Page header) -->
@section('content-header')
    <h1>
        Orders Details
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

    <div class="panel panel-default">
        <div class="panel-heading">
            View
        </div>

        <div class="panel-body">            
            <div class="row">
                <div class="col-xs-12 form-group">Personal Information</div>

                <div class="col-xs-6 form-group">                    
                  <label for="op_pay_mobile_no" class="control-label">First Name :</label> 
                  @if(!empty( $orders->First()->op_first_name ))                   
                     {{ $orders->First()->op_first_name }}
                  @else
                      Empty
                  @endif
                </div>
                
                <div class="col-xs-6 form-group">                    
                   <label for="op_pay_mobile_no" class="control-label">Last Name :</label>
                  @if(!empty( $orders->First()->op_last_name ))                   
                         {{ $orders->First()->op_last_name }}
                  @else
                           Empty
                  @endif
                </div>  
            </div>

            <div class="row">
                <div class="col-xs-6 form-group">                    
                  <label for="op_pay_mobile_no" class="control-label">Mobile No :</label> 
                  @if(!empty( $orders->First()->op_mobile_no ))                   
                      {{ $orders->First()->op_mobile_no }}
                  @else
                      Empty
                  @endif
                </div>
                
                <div class="col-xs-6 form-group">
                    
                    <label for="op_pay_mobile_no" class="control-label">Alter Mobile No :</label>
                    @if(!empty( $orders->First()->op_alternative_mobile_no ))                   
                         {{ $orders->First()->op_alternative_mobile_no }}
                     @else
                          Empty
                    @endif
                </div>  
            </div>

            <div class="row">
                <div class="col-xs-6 form-group">                    
                  <label for="op_pay_mobile_no" class="control-label">Email :</label> 
                  @if(!empty( $orders->First()->op_email ))                   
                      {{ $orders->First()->op_email }}
                  @else
                      Empty
                  @endif

                </div>
                
                <div class="col-xs-6 form-group">
                    
                    <label for="op_pay_mobile_no" class="control-label">Gender :</label>
                     @if(!empty( $orders->First()->op_gender ))                   
                          {{ $orders->First()->op_gender }}
                    @else
                          Empty
                    @endif
                </div>  
            </div>

            <div class="row">

              <div class="col-xs-6 form-group">
                    
                    <label class="control-label">Registration State :</label> 
                   @if(!empty( $orders->First()->op_registration_state ))                   
                        {{ $orders->First()->op_registration_state }}
                  @else
                        Empty
                  @endif
                </div>  
            </div>      

                                  
            <div class="row">                
                <div class="col-xs-12 form-group">Source address</div>

                <div class="col-xs-6 form-group">
                    
                    <label for="op_pay_mobile_no" class="control-label">Address :</label>  @if(!empty( $orders->First()->start_address_line_1 ))                   
                        {{ $orders->First()->start_address_line_1 }}
                        {{ $orders->First()->start_address_line_2 }}
                        {{ $orders->First()->start_address_line_3 }}

                      @else
                        Empty
                      @endif
                </div>  
                <div class="col-xs-6 form-group">                    
                  <label class="control-label">City :</label>  
                  @if(!empty( $orders->First()->start_city ))                   
                     {{ $orders->First()->start_city }}
                  @else
                     Empty
                  @endif
                </div>
                
                <div class="col-xs-6 form-group">

                    <label for="op_pay_mobile_no" class="control-label">Pin Code :</label>  @if(!empty( $orders->First()->start_pincode ))                   
                        {{ $orders->First()->start_pincode }}                        
                      @else
                        Empty
                      @endif
                </div>  
            </div>

             <div class="row">                
                <div class="col-xs-12 form-group">Destination address</div>
                     

                <div class="col-xs-6 form-group">
                    
                   
                    @if(!empty($orders->First()->intermediate_address ))   
                    @php
                        $count=1
                    @endphp
    @foreach(json_decode($orders->First()->intermediate_address, true) as $address)
         <label for="op_pay_mobile_no" class="control-label">Address {{ $count }}:</label>  
                        @php
                        $count=$count+1;
                        @endphp

                        {{ $address['dest_address_line_1'] }}
                        {{ $address['dest_address_line_2'] }}
                        {{ $address['dest_address_line_3'] }}
                        {{ $address['city'] }}
                        {{ $address['state'] }}
                        {{ $address['counry'] }}-
                        {{ $address['pin_code'] }}
                        
                    <br>
                    @endforeach
                    @else
                    Address not Available
                    @endif
                </div> 

                 

               

            </div>

              <div class="row">
              <div class="col-xs-12 form-group">Driver Information</div>
                <div class="col-xs-6 form-group">    

                  <label for="op_pay_mobile_no" class="control-label">Driver Fast Name :</label> 
                  @if(!empty( $orders->First()->driver_first_name ))                   
                      {{ $orders->First()->driver_first_name }}
                  @else
                      Empty
                  @endif
                </div>
                
                <div class="col-xs-6 form-group">
                    
                    <label for="op_pay_mobile_no" class="control-label">Driver Last Name :</label>
                  @if(!empty( $orders->First()->driver_last_name ))                   
                      {{ $orders->First()->driver_last_name }}
                  @else
                      Empty
                  @endif
                </div>  
            </div>
              <div class="row">

                <div class="col-xs-6 form-group">                    
                  <label for="op_pay_mobile_no" class="control-label">Driver  Username :</label> 
                  @if(!empty( $orders->First()->driver_op_username ))                   
                      {{ $orders->First()->driver_op_username }}
                  @else
                      Empty
                  @endif
                </div>
                
                <div class="col-xs-6 form-group">
                    
                    <label for="op_pay_mobile_no" class="control-label">Driver Mobile No :</label>
                  @if(!empty( $orders->First()->driver_mobile_number ))                   
                      {{ $orders->First()->driver_mobile_number }}
                  @else
                      Empty
                  @endif
                </div>  
            </div>

             <div class="row">
              <div class="col-xs-12 form-group">Vehicle Information</div>
                <div class="col-xs-6 form-group">    

                                
                  <label for="op_pay_mobile_no" class="control-label">Vehicle Model Name :</label> 
                  @if(!empty( $orders->First()->veh_model_name ))                   
                      {{ $orders->First()->veh_model_name }}
                  @else
                      Empty
                  @endif
                </div>
                
                <div class="col-xs-6 form-group">
                    
                    <label for="op_pay_mobile_no" class="control-label">vehicle type :</label>
                  @if(!empty( $orders->First()->veh_type_name ))                   
                      {{ $orders->First()->veh_type_name }}
                  @else
                      Empty
                  @endif
                </div>  
            </div>

              <div class="row">
                <div class="col-xs-12 form-group">Helper information</div>                    
                <div class="col-xs-6 form-group">                    
                  <label for="op_pay_mobile_no" class="control-label">No of Helpers :</label> 
                  @if(!empty( $orders->First()->helper_count ))                   
                      {{ $orders->First()->helper_count }}
                  @else
                      Empty
                  @endif
                </div>
                
                <div class="col-xs-6 form-group">
                    
                    <label for="op_pay_mobile_no" class="control-label">Helper Price:</label>
                  @if(!empty( $orders->First()->helper_price ))                   
                  {{ $orders->First()->helper_price }}
                  @else
                  Empty
                  @endif
                </div>  
            </div>
            @if(isset($orders->First()->payment_mode))
            @if($orders->First()->payment_mode==0)

             <div class="row">
                <div class="col-xs-12 form-group">Payment Information</div>                                   
                <div class="col-xs-6 form-group">                    
                  <label for="op_pay_mobile_no" class="control-label">Payment Gateway Name :</label> 
                  @if(!empty( $orders->First()->payment_gateway_name ))                   
                      {{ $orders->First()->payment_gateway_name }}
                  @else
                       Empty
                  @endif
                </div>

                
                <div class="col-xs-6 form-group">
                    
                  <label for="op_pay_mobile_no" class="control-label">Payment Gateway Currency:</label>
                  @if(!empty( $orders->First()->payment_gateway_currency ))                   
                      {{ $orders->First()->payment_gateway_currency }}
                  @else
                      Empty
                  @endif
                </div>    

                <div class="col-xs-6 form-group">
                    
                  <label for="op_pay_mobile_no" class="control-label">Payment Gateway Response:</label>
                  @if(!empty( $orders->First()->payment_gateway_response))                   
                  {{ $orders->First()->payment_gateway_response }}
                  @else
                  Empty
                  @endif
                </div>   
                <div class="col-xs-6 form-group">
                    
                    <label for="op_pay_mobile_no" class="control-label">Payment Transaction ID:</label>
                  @if(!empty( $orders->First()->payment_gateway_transaction_id ))                   
                  {{ $orders->First()->payment_gateway_transaction_id }}
                  @else
                  Empty
                  @endif
                </div>   

                <div class="col-xs-6 form-group">
                    
                    <label for="op_pay_mobile_no" class="control-label">Payment Order Status:</label>
                  @if(!empty( $orders->First()->payment_order_status ))                   
                  {{ $orders->First()->payment_order_status }}
                  @else
                  Empty
                  @endif
                </div>

            </div>
            @endif
            @endif
            

            <div class="row">
                <div class="col-xs-12 form-group">Bill Information</div>  
                <div class="col-xs-6 form-group">
                    
                    <label for="op_pay_mobile_no" class="control-label">Payment Mode:</label>
                  @if(isset($orders->First()->payment_mode))
                  @if($orders->First()->payment_mode==1 )                   
                  Cash
                  @else
                  Online Payment
                  @endif
                  @endif
                </div>   
                @if(isset($orders->First()->payment_mode))
                @if($orders->First()->payment_mode==0)                   
                <div class="col-xs-6 form-group">                    
                  <label for="op_pay_mobile_no" class="control-label">Total Discount Amount :</label> 
                  @if(!empty( $orders->First()->total_discount_amount ))                   
                  {{ $orders->First()->total_discount_amount }}
                  @else
                  Empty
                  @endif
                </div>
                @endif
                @endif
                
               
            </div>

            <div class="row">
                                
                <div class="col-xs-6 form-group">                    
                  <label for="op_pay_mobile_no" class="control-label">GST Tax :</label> 
                  @if(!empty( $orders->First()->gst_tax ))                   
                  {{ $orders->First()->gst_tax }}
                  @else
                  Empty
                  @endif
                </div>

                <div class="col-xs-6 form-group">                    
                  <label for="op_pay_mobile_no" class="control-label">GST Tax Per :</label> 
                  @if(!empty( $orders->First()->gst_tax_per ))                   
                  {{ $orders->First()->gst_tax_per }}
                  @else
                  Empty
                  @endif
                </div>

              </div>
            
            <div class="row">
                <div class="col-xs-6 form-group">                    
                <label for="op_pay_mobile_no" class="control-label">Base Amount:
                </label>
                  @if(!empty( $orders->First()->base_amount ))                   
                  {{ $orders->First()->base_amount }}
                  @else
                  Empty
                  @endif
                </div>   

                <div class="col-xs-6 form-group">                    
                  <label for="op_pay_mobile_no" class="control-label">Total  Amount :</label> 
                  @if(!empty( $orders->First()->actual_amount ))                   
                  {{ $orders->First()->actual_amount }}
                  @else
                  Empty
                  @endif
                </div>
                
            </div> 

            <div class="row">
             <div class="col-xs-12 form-group"> 
              <center><a href="{{ route('Orders.index') }}" class="btn btn-danger">Back</a></center>
              </div>
            </div>
             
            </div>

    </div>
</div>


@endif
@endsection

<!-- JS scripts for this page only -->
@section('javascript')
@endsection

