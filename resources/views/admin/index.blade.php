@extends('admin.admin_master')
@section('admin')
<div class="container-fluid">
   <div class="row">

   <div class="card col-md-12">
   <h5 class="card-header">Balance Info</h5>
   <div class="card-body">
      <h5 class="card-title"> <span style="font-weight:bolder;">Balance:</span>  </h5>
      @foreach($reply as $repl)
         <h3 class="card-text">430.00 BDT</h3>$repl->balance
      @endforeach  
   </div>
   </div>
                     <!-- /.col-md-6 -->
   </div>
   <!-- /.row -->
</div>
               <!-- /.container-fluid -->
            
@endsection