@extends('admin.admin_master')
@section('admin')
<div class="container-fluid">
   <div class="row">

   <div class="card col-md-12">
   <h5 class="card-header">Balance Info</h5>
   <div class="card-body">
      <h5 class="card-title"> <span style="font-weight:bolder;">Balance:</span>  </h5>
    
         <h3 class="card-text">430.00 BDT</h3>
     
   </div>
   </div>
                     <!-- /.col-md-6 -->
   </div>
   <!-- /.row -->
</div>


<div class="container">
   <div class="row">
      <div class="col-md-12">
         <table class="table">
            <thead>
               <tr>
                  <th scope="col">#</th>
                  <th scope="col">Product</th>
                  <th scope="col">Currency</th>
                  <th scope="col">Amount</th>
                  <th scope="col">Invoice</th>
                  <th scope="col">Transaction ID</th>
                  <th scope="col">Status</th>
                  <th scope="col">Action</th>
               </tr>
            </thead>
            <tbody>
               @foreach($orders as $order)
               <tr @if($order->status == 'Processing') class="table-success" @endif>
                  <th scope="row"> {{ $order->id }} </th>
                  <td>{{ $order->product }}</td>
                  <td>{{ $order->currency }}</td>
                  <td>{{ $order->amount }}</td>
                  <td>{{ $order->invoice }}</td>
                  <td>{{ $order->trxID }}</td>
                  <td>{{ $order->status }}</td>
                  <td> <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary" >View</a> </td>
               </tr>
               @endforeach
            </tbody>
         </table>
      </div>
   </div>
</div>
               <!-- /.container-fluid -->
            
@endsection