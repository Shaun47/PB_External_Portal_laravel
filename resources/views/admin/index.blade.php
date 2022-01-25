@extends('admin.admin_master')
@section('admin')


<div class="container">
   <div class="row">
      <div class="col-md-12">
         <div class="card-body">
         <form action="" id="recharge" onsubmit="event.preventDefault();">
         <div class="mb-3 row">
            <label for="amount" class="col-sm-2 col-form-label">Amount</label>
            <div class="col-sm-10">
               <input type="text" class="form-control amount" id="amount">
            </div>
         </div>
         <div class="mb-3 row">
            <label for="invoice" class="col-sm-2 col-form-label">Invoice No</label>
            <div class="col-sm-10">
               <input type="text" class="form-control invoice" id="invoice">
            </div>
         </div>
         

            <button class="btn btn-primary" id="bKash_button">Pay With Bkash</button>
            </form>
         </div>
      </div>
   </div>
</div>


               <!-- /.container-fluid -->



               <script src="https://code.jquery.com/jquery-1.8.3.min.js"
        integrity="sha256-YcbK69I5IXQftf/mYD8WY0/KmEDCv1asggHpJk1trM8=" crossorigin="anonymous"></script>

<script id="myScript"
        src="https://scripts.sandbox.bka.sh/versions/1.2.0-beta/checkout/bKash-checkout-sandbox.js"></script>

<script>
    var accessToken = '';

    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{!! route('pb.token') !!}",
            type: 'POST',
            contentType: 'application/json',
            success: function (data) {
                console.log('got data from token  ..');
                console.log(JSON.stringify(data));

                accessToken = JSON.stringify(data);
            },
            error: function () {
                console.log('error');

            }
        });

        var paymentConfig = {
            createCheckoutURL: "{!! route('pb.createpayment') !!}",
            executeCheckoutURL: "{!! route('pb.executepayment') !!}"
        };


        var paymentRequest;
        paymentRequest = {amount: $('#amount').val(), intent: 'sale', invoice: $('#invoice').val()};
        console.log(JSON.stringify(paymentRequest));

        bKash.init({
            paymentMode: 'checkout',
            paymentRequest: paymentRequest,
            createRequest: function (request) {
                console.log('=> createRequest (request) :: ');
                console.log(request);

                $.ajax({
                    url: paymentConfig.createCheckoutURL + "?amount=" + paymentRequest.amount + "&invoice=" + paymentRequest.invoice,
                    type: 'GET',
                    contentType: 'application/json',
                    success: function (data) {
                        console.log('got data from create  ..');
                        console.log('data ::=>');
                        console.log(JSON.stringify(data));

                        var obj = JSON.parse(data);

                        if (data && obj.paymentID != null) {
                            paymentID = obj.paymentID;
                            bKash.create().onSuccess(obj);
                        }
                        else {
                            console.log('error');
                            bKash.create().onError();
                        }
                    },
                    error: function () {
                        console.log('error');
                        bKash.create().onError();
                    }
                });
            },

            executeRequestOnAuthorization: function () {
                console.log('=> executeRequestOnAuthorization');
                $.ajax({
                    url: paymentConfig.executeCheckoutURL + "?paymentID=" + paymentID,
                    type: 'GET',
                    contentType: 'application/json',
                    success: function (data) {
                        console.log('got data from execute  ..');
                        console.log('data ::=>');
                        console.log(JSON.stringify(data));

                        urls = '{!! route("pb.makePayment", ":amount") !!}';
                        urls = urls.replace(':amount', paymentRequest.amount);

                        data = JSON.parse(data);
                        if (data && data.paymentID != null) {
                            // alert('[SUCCESS] data : ' + JSON.stringify(data));
                            window.location.href = urls;
                        } 
                        else {
                            bKash.execute().onError();
                        }
                    },
                    error: function () {
                        bKash.execute().onError();
                    }
                });
            }
        });

        console.log("Right after init ");
    });

    function callReconfigure(val) {
        bKash.reconfigure(val);
    }

    function clickPayButton() {
        $("#bKash_button").trigger('click');
    }
</script>

<script>

window.addEventListener('load', function(){
   document.getElementById("recharge").reset()

});

</script>
            
@endsection