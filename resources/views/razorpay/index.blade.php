<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ orgName() }} - Stripe Payment</title>
    <link href="{{ favIcon() }}" rel="shortcut icon">
    <link rel="stylesheet" href="{{ filePath('assets/stripe/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ filePath('assets/stripe/style.css') }}">
</head>

<body>
    <div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-12 col-lg-11 col-xl-10">
            <div class="card card0">
                <div class="row">
                    <div class="col-md-12 d-block p-0 box">
                        <div class="card rounded-0 border-0 card1 pr-xl-4 pr-lg-3">
                            <div class="row justify-content-center">
                                <div class="col-11">
                                    <h3 class="text-center mt-4 mb-4 package-title text-uppercase font-weight-bold" id="heading0">@translate(Purchasing From)</h3>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-5 fit-image text-center"> <img src="{{ logo() }}" height="auto" width="200px"> </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-11">
                                    <h2 class="text-center mt-5 mb-3 package-title text-uppercase" id="sub-heading1">@translate(Purchasing)
                                        <br> <span class="text-clr">{{ Str::upper(subscriptionName($subscriptoin_plan_id)) }} </span>@translate(Plan)</h2>
                                </div>
                            </div>

                                <div class="container">
                                    <div class="row mt-5">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="stripe-card">
                                                        <label class="mb-1 gift stripe-card__sub-title text-muted"><small>@translate(To)</small></label>
                                                        <h6 class="stripe-card__title text-capitalize">
                                                            {{ orgName() }}
                                                        </h6>
                                                        <div class="row mt-3">
                                                            <div class="col-12"> 
                                                                <label class="gift text-muted mb-1">
                                                                    <small >@translate(Recipient email)</small>
                                                                </label>
                                                                <br>
                                                                <h6 class="stripe-card__title">
                                                                        {{ org('company_email') }}
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="stripe-card">
                                                        <label class="mb-1 gift stripe-card__sub-title text-muted"><small>@translate(From)</small></label>
                                                        <h6 class="stripe-card__title text-capitalize">
                                                            @if (Auth::check())
                                                        {{ Auth::user()->name }}
                                                            @else
                                                            {{ $name }}
                                                        @endif
                                                        </h6>
                                                        <div class="row mt-3">
                                                            <div class="col-12"> <label class="gift text-muted mb-1"><small >@translate(Recipient email)</small></label><br>
                                                                <h6 class="stripe-card__title">
                                                                    @if (Auth::check())
                                                                        {{ Auth::user()->email }}
                                                                    @else
                                                                        {{ $email }}
                                                                    @endif
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <button id="rzp-button1" hidden>Pay</button>
                                        </div>
                                    </div>
                                </div>

                        </div>
                    </div>
                  
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ filePath('assets/stripe/jquery.js') }}"></script>
<script src="{{ filePath('assets/stripe/bootstrap.bundle.js') }}"></script>
<script src="{{ filePath('assets/stripe/script.js') }}"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
"use strict";

var options = {
    "key": "{{$response['razorpayId']}}", // Razorpay ID
    "amount": "{{$response['amount']}}", // Amount
    "currency": "{{$response['currency']}}",
    "name": "{{$response['name']}}",
    "description": "{{$response['description']}}",
    "image": "https://example.com/your_logo", // replace this link with actual logo
    "order_id": "{{$response['orderId']}}", //Created Order id in first method
    "handler": function (response){
        document.getElementById('rzp_paymentid').value = response.razorpay_payment_id;
        document.getElementById('rzp_orderid').value = response.razorpay_order_id;
        document.getElementById('rzp_signature').value = response.razorpay_signature;
        document.getElementById('rzp-paymentresponse').click();
    },
    "prefill": {
        "name": "name",
        "email": "email",
        "contact": "0541515"
    },
    "notes": {
        "address": "dahanu"
    },
    "theme": {
        "color": "#F37254"
    }
};
var rpay = new Razorpay(options);
window.onload = function(){
    document.getElementById('rzp-button1').click();
};

document.getElementById('rzp-button1').onclick = function(e){
    rpay.open();
    e.preventDefault();
}
</script>

<form action="{{route('razorpay.payment.payment')}}" method="POST" hidden>
        @csrf
        <input type="text" class="form-control" id="rzp_paymentid"  name="rzp_paymentid">
        <input type="text" class="form-control" id="rzp_orderid" name="rzp_orderid">
        <input type="text" class="form-control" id="rzp_signature" name="rzp_signature">
    <button type="submit" id="rzp-paymentresponse" class="btn btn-primary">Submit</button>
</form>

</body>
</html>

