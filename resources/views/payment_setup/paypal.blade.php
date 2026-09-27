@extends('layout.' . layout())

@section('subhead')
    <title>@translate(Paypal Setup)</title>
@endsection

@section('subcontent')
    <div class="flex items-center mt-8">
        <h2 class="intro-y text-lg font-medium mr-auto">@translate(Paypal Setup)</h2>
    </div>
    <!-- BEGIN: Wizard Layout -->
    <div class="intro-y box mt-5">

        <div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200 dark:border-dark-5">
            <!-- BEGIN: Form Layout -->
            <form action="{{ route('payment.setup.paypal.create') }}" method="GET">
                <div class="intro-y box p-5">
                    <div>
                        <label>@translate(PayPal Client ID)</label>
                        <input type="text" value="{{ env('PAYPAL_CLIENT_ID') ?? '' }}" class="input w-full border mt-2"
                            name="paypal_client_id" placeholder="Paypal Client ID">
                    </div>

                    <div class="mt-3">
                        <div>
                            <label>@translate(PayPal Secret Key)</label>
                            <input type="text" value="{{ env('PAYPAL_SECRET') ?? '' }}" class="input w-full border mt-2"
                                name="paypal_secret" placeholder="Paypal Secret Key">
                        </div>
                    </div>
                    <div class="mt-3">
                        <div>
                            <label>@translate(PayPal Mode)</label>
                            <select class="input w-full border flex-1" name="paypal_mode">
                                <option value="sandbox" @if (env('PAYPAL_MODE') == 'sandbox') selected @endif>@translate(Sandbox)
                                </option>
                                <option value="live" @if (env('PAYPAL_MODE') == 'live') selected @endif>@translate(Live)
                                </option>
                            </select>
                        </div>

                        <div class="text-right mt-5">
                            @if (env('PAYPAL_PAYMENT') == 'YES')
                                <a onclick="PaymentButtonDisable()"
                                    class="button w-24 bg-theme-1 text-black">@translate(Disable)</a>
                            @else
                                <a onclick="PaymentButtonDisable()"
                                    class="button w-24 bg-theme-1 text-white">@translate(Enable)</a>
                            @endif
                            <button type="submit" class="button w-24 bg-theme-1 text-white">@translate(Setup)</button>
                        </div>
                    </div>
            </form>
            <!-- END: Form Layout -->
        </div>
    </div>
    <!-- END: Wizard Layout -->
    <form action="{{ route('payment.setup.paypal.disable') }}" method="GET" style="display: none"
        id="SetupPaymentFormDisable">
        <input type="hidden" value="NO" name="paypal_disable">
    </form>
@endsection

@section('script')
    <script src="{{ filePath('assets/js/jquery.js') }}"></script>
    <script src="{{ filePath('assets/js/parsley.js') }}"></script>
    <script src="{{ filePath('assets/js/validation.js') }}"></script>
    <script>
        function PaymentButtonDisable() {
            document.getElementById("SetupPaymentFormDisable").submit();
        }
    </script>
@endsection
