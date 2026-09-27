@extends('frontend.' . theme() . '.layouts.master')

@push('styles')
    <style>
        .payment-btn>img {
            transition: all 300ms ease-in;
        }

        .payment-btn:hover .payment-btn>img {
            transform: scale(1.1);
        }
    </style>
@endpush
@section('content')
    @php
        if (session()->has('coupon')) {
            $plan->price = $plan->price - session()->get('coupon_amount');
        }
    @endphp

    <div class="container my-5 pt-5">
        <div class="py-5 mt-5 text-center">
            <h2 class="h2">@translate(Checkout form)</h2>
            <p class="lead">{{ orgName() }} @translate(needs your information to purchase any plan. Please fill-up the form and make payment.)</p>
        </div>
        <div class="row" x-data="{ 'email': '{{ old('email') ?? auth()->user()?->email }}', name: '{{ old('name') ?? auth()->user()?->name }}', phone: '{{ old('phone') }}' }">
            <div class="col-md-4 order-md-2 mb-4">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">@translate(Your cart)</span>
                    <span class="badge badge-secondary badge-pill">1</span>
                </h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <h6 class="my-0">{{ Str::upper($plan->name) }}</h6>
                            <small class="text-muted">{{ $plan->duration }} @translate(Month)</small> •
                            <small class="text-muted">{{ $plan->emails }} @translate(Emails)</small> •
                            <small class="text-muted">{{ $plan->sms }} @translate(SMS)</small> •
                        </div>
                        <span class="text-muted">{{ formatPrice($plan->price) }}</span>
                    </li>


                    {{-- Check Session --}}
                    @if (session()->has('coupon'))
                        <li class="list-group-item d-flex justify-content-between">
                            <span> {{ session()->get('coupon')['code'] }} </span>
                            <strong>{{ session()->get('coupon')['discount_amount'] }}%</strong>
                        </li>
                    @endif

                    <li class="list-group-item d-flex justify-content-between">
                        <span>@translate(Total)</span>
                        <strong>{{ formatPrice($plan->price) }}</strong>
                    </li>

                    <li class="list-group-item justify-content-between">
                        <form action="{{ route('coupon.apply') }}" method="get">
                            <input type="hidden" name="subscription_id" value="{{ $plan->id }}">
                            <label for="code">@translate(Coupon Code)</label>
                            <input type="text" class="form-control w-100 @error('code') is-invalid @enderror"
                                id="code" name="code" placeholder="Enter Coupon Code Here"
                                value="@if (session()->has('coupon')) {{ session()->get('coupon')['code'] }} @endif"
                                required>
                            @error('code')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <button class="btn-sm btn-primary w-100 mt-2">
                                @translate(Apply Coupon)
                            </button>
                        </form>
                    </li>

                </ul>

            </div>
            <div class="col-md-8 order-md-1">
                <h4 class="mb-3">@translate(Billing address)</h4>

                <form class="needs-validation" novalidate action="{{ route('payment.pay') }}" method="POST">

                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="firstName">First name <span
                                    class="badge badge-pill badge-secondary">required</span></label>
                            <input type="text" onload="userName()" class="form-control w-100" id="firstName"
                                name="name" x-model='name' placeholder="Full Name" value="{{ auth()->user()?->name }}"
                                required>
                            <div class="invalid-feedback">
                                @translate(Valid first name is required.)
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email">Email <span
                                    class="badge badge-pill badge-secondary">required</span></label>
                            <input type="email" x-model="email"
                                class="form-control w-100 @error('email') is-invalid @enderror" name="email"
                                id="email" value="{{ auth()->user()?->email }}" placeholder="you@example.com">

                            @error('email')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                            <div class="invalid-feedback">
                                @translate(Please enter a valid email address.)
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone">Phone <span
                                    class="badge badge-pill badge-secondary">required</span></label>
                            <input type="phone" x-model="phone"
                                class="form-control w-100 @error('phone') is-invalid @enderror" name="phone"
                                value="{{old('phone')}}"
                                id="phone" placeholder="+88016XXXXXXXX">

                            @error('phone')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                            <div class="invalid-feedback">
                                @translate(Please enter a valid email address.)
                            </div>
                        </div>
                    </div>

                    @guest
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="Password">Password <span
                                        class="badge badge-pill badge-secondary">required</span></label>
                                <input type="password" onload="userPassword()" class="form-control w-100" name="password"
                                    id="Password" placeholder="Password" required>
                                <div class="invalid-feedback">
                                    @translate(Password is required.)
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password">Confirm Password <span
                                        class="badge badge-pill badge-secondary">required</span></label>
                                <input type="password" class="form-control w-100" id="confirm_password"
                                    placeholder="Confirm Password" required>
                                <div class="invalid-feedback">
                                    @translate(Confirm password is required.)
                                </div>
                            </div>
                        </div>

                    @endguest




                    <hr class="mb-4">

                    <h4 class="mb-4">@translate(Payment Methods)</h4>


                    <div class="row">

                        @foreach (paymentGateways() as $item => $service)
                            @php
                                $lower = strtolower($item);
                                $upper = strtoupper($item);
                            @endphp
                            @switch($item)
                                @case('free')
                                    @if ($isFree)
                                        {{-- FREE --}}
                                        <div class="col-md-3 text-center">
                                            <img src="{{ filePath('frontend/images/gateway/free.jpg') }}" class="img-fluid "
                                                alt="#Free">
                                        </div>
                                        {{-- FREE::END --}}
                                    @endif
                                @break

                                @default
                                    @if (enable_disable_payment_gateway($lower) && !$isFree)
                                    
                                        <div action="{{ route('payment.pay') }}"
                                            class="col-6 col-md-4 col-lg-4 col-xl-3 text-center px-2 mb-4">
                                            <button
                                                @if ($service->shouldRedirect) value="{{ $lower }}"
                                            name='method'
                                            @else
                                            type="button"
                                             data-toggle="modal" 
                                             data-target="#exampleModal3" @endif
                                                class="payment-btn bg-white border h-100 w-100 px-3 py-2"
                                                style="border-radius:10px;">
                                                <img src="{{ $service->getLogoUrl() }}" class="img-fluid "
                                                    alt="#{{ $lower }}">
                                            </button>
                                        </div>
                                    @endif
                                @break
                            @endswitch
                        @endforeach

                    </div>


                    @if ($isFree)
                        <hr class="mb-4 mt-4">

                        {{-- @if (freeDateLimitCheck($plan->name))
							<a href="javascript:;" class="btn btn-primary btn-lg btn-block not-allowed">@translate(Plan is already running)</a>
						@else --}}
                        <button class="btn btn-primary btn-lg btn-block" name="method" value="free"
                            type="submit">@translate(Continue to checkout)</button>
                        {{-- @endif --}}
                    @endif

                </form>

                <!-- Modal -->
                <div class="modal fade h-auto " id="exampleModal3" tabindex="-1">
                    <div class="modal-dialog md:max-w-full lg:max-w-[920px]">
                        <div class="modal-content">
                            <div class="modal-body">

                                @include('stripe.payment', ['plan' => $plan])

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn-sm btn-primary" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>



    {{-- Stripe --}}

    <form class="" enctype="multipart/form-data" action="{{ route('getPaymentWithStripe') }}" method="GET"
        id="form-stripe">
        <input type="hidden" name="subscriptoin_plan_id" value="{{ $plan->id }}">
        <input type="hidden" name="plan_name" value="{{ $plan->name }}">
        <input type="hidden" name="amount" value="{{ StripePrice($plan->price) }}">
        <input type="hidden" name="payment_type" value="stripe">
        <input type="hidden" id="stripe-name" name="name" required>
        <input type="hidden" id="stripe-email" name="email" required>
        <input type="hidden" id="stripe-password" name="password" required>
    </form>

    {{-- Razorpay --}}
    @php
        $razorpay = session('razorpay') ?? null;
    @endphp
    <form id="razorpay-form" class="hidden"
        action="{{ $razorpay ? $razorpay['callback_url'] : route('payment.callback') }}" method="POST">
        @csrf
        <input type="hidden" name="method" value="razorpay">
        <input type="hidden" name="razorpay[payment_id]">
        <input type="hidden" name="razorpay[order_id]">
        <input type="hidden" name="razorpay[signature]">
    </form>

    @push('scripts')
        @if ($razorpay)
            <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
            <script>
                "use strict";

                var options = {
                    "key": "{{ $razorpay['key'] }}", // Razorpay ID
                    "amount": "{{ $razorpay['amount'] }}", // Amount
                    "currency": "{{ $razorpay['currency'] }}",
                    "name": "{{ $razorpay['name'] }}",
                    "description": "{{ $razorpay['description'] }}",
                    "image": "{{ logo() }}", // replace this link with actual logo,
                    "callback_url": "{{ $razorpay['callback_url'] }}",
                    "order_id": "{{ $razorpay['order_id'] }}", //Created Order id in first method
                    "handler": function(response) {
                        $('[name="razorpay[payment_id]"]').val(response.razorpay_payment_id);
                        $('[name="razorpay[order_id]"]').val(response.razorpay_order_id);
                        $('[name="razorpay[signature]"]').val(response.razorpay_signature);
                        $('#razorpay-form').submit();
                    },
                    "prefill": {
                        "name": "{{ $razorpay['name'] }}",
                        "email": "{{ $razorpay['email'] }}",
                        "contact": "{{ $razorpay['contact'] }}"
                    },
                    "notes": {
                        "address": "{{ $razorpay['address'] }}"
                    },
                    "theme": {
                        "color": "#F37254"
                    }
                };
                var rpay = new Razorpay(options);
                $(function() {
                    rpay.open();
                })
            </script>
        @endif
    @endpush
@endsection
