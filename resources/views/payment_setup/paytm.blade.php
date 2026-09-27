@extends('layout.' . layout())

@section('subhead')
	<title>@translate(Paytm Setup)</title>
@endsection

@section('subcontent')
	<div class="flex items-center mt-8">
		<h2 class="intro-y text-lg font-medium mr-auto">@translate(Paytm Setup)</h2>
	</div>
	<!-- BEGIN: Wizard Layout -->
	<div class="intro-y box mt-5">

		<div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200 dark:border-dark-5">
			<!-- BEGIN: Form Layout -->
			<form action="{{ route('paytm.store') }}" method="GET">
				<div class="intro-y box p-5">
					<div>
						<label>@translate(Paytm Merchant Id)</label>
						<input type="text" value="{{ env('PAYTM_MERCHANT_ID') ?? '' }}" class="input w-full border mt-2" name="paytm_merchant_id" placeholder="Paytm Merchant Id">
					</div>

					
					<div class="mt-3">
						<div>
							<label>@translate(Paytm Merchant Key)</label>
							<input type="text" value="{{ env('PAYTM_MERCHANT_KEY') ?? '' }}" class="input w-full border mt-2" name="paytm_merchant_key" placeholder="Paytm Merchant Key">
						</div>
					</div>

					<div class="mt-3">
						<div>
							<label>@translate(Paytm Payment Website)</label>
							<input type="text" value="{{ env('PAYTM_WEBSITE') ?? '' }}" class="input w-full border mt-2" name="paytm_payment_website" placeholder="Paytm Payment Website">
						</div>
					</div>

					<div class="mt-3">
						<div>
							<label>@translate(Paytm Channel)</label>
							<input type="text" value="{{ env('PAYTM_CHANNEL') ?? '' }}" class="input w-full border mt-2" name="paytm_merchant_channel" placeholder="Paytm Merchant Channel">
						</div>
					</div>

					<div class="mt-3">
						<div>
							<label>@translate(Paytm Merchant Currency)</label>
							<select class="w-full form-select sm:w-1/2" name="paytm_merchant_industry_type" required="">
								<option value="Retail" {{ env('PAYTM_INDUSTRY_TYPE') == 'Retail' ? 'selected' : null }}>Retail</option>
							</select>
						</div>
					</div>

					<div class="text-right mt-5">
						@if (env('PAYTM') == 'YES')
							<a onclick="PaymentEnableDisable()" class="button w-24 bg-theme-1 text-black">@translate(Disable)</a>
						@else
							<a onclick="PaymentEnableDisable()" class="button w-24 bg-theme-1 text-white">@translate(Enable)</a>
						@endif
						<button type="submit" class="button w-24 bg-theme-1 text-white">@translate(Setup)</button>
					</div>
				</div>
			</form>
			<!-- END: Form Layout -->
		</div>
	</div>
	<!-- END: Wizard Layout -->
	<form action="{{ route('payment.paytm.enabledisable') }}" method="GET" style="display: none" id="SetupPaymentFormEnableDisable">
		<input type="hidden" value="NO" />
	</form>
@endsection

@section('script')
	<script src="{{ filePath('assets/js/jquery.js') }}"></script>
	<script src="{{ filePath('assets/js/parsley.js') }}"></script>
	<script src="{{ filePath('assets/js/validation.js') }}"></script>
	<script>
		function PaymentEnableDisable() {
			document.getElementById("SetupPaymentFormEnableDisable").submit();
		}
	</script>
@endsection
