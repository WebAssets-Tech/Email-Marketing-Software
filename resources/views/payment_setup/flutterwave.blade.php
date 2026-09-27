@extends('layout.' . layout())

@section('subhead')
	<title>@translate(Flutterwave Setup)</title>
@endsection

@section('subcontent')
	<div class="flex items-center mt-8">
		<h2 class="intro-y text-lg font-medium mr-auto">@translate(Flutterwave Setup)</h2>
	</div>
	<!-- BEGIN: Wizard Layout -->
	<div class="intro-y box mt-5">
 
		<div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200 dark:border-dark-5">
			<!-- BEGIN: Form Layout -->
			<form action="{{ route('payment.setup.flutterwave.store') }}" method="GET">
				<div class="intro-y box p-5">
					<div>
						<label>@translate(Flutterwave Public Key)</label>
						<input type="text" value="{{ env('FLW_PUBLIC_KEY') ?? '' }}" class="input w-full border mt-2" name="flutterwave_client_id" placeholder="Flutterwave Public Key">
					</div>

					<div class="mt-3">
						<div>
							<label>@translate(Flutterwave Secret Key)</label>
							<input type="text" value="{{ env('FLW_SECRET_KEY') ?? '' }}" class="input w-full border mt-2" name="flutterwave_secret" placeholder="Flutterwave Secret Key">
						</div>
					</div>

					<div class="mt-3">
						<div>
							<label>@translate(Flutterwave Secret Hash)</label>
							<input type="text" value="{{ env('FLW_SECRET_HASH') ?? '' }}" class="input w-full border mt-2" name="flutterwave_hash" placeholder="Flutterwave Secret Hash">
						</div>
					</div>

					<div class="mt-3">
						<label class="flex flex-col sm:flex-row"> @translate(Choose Currency)</label>
						<select class="w-full form-select sm:w-1/2" name="FLW_CURRENCY" required>
							@forelse (flutterwaveSupportedCountries() as $currency => $symbol)
								<option value="{{ $symbol }}" class="normal-case" {{ env('FLW_CURRENCY') == $symbol ? 'selected' : null }}>
									{{ $currency }} - {{ $symbol }}
								</option>
							@empty
							@endforelse
						</select>
					</div>

					<div class="text-right mt-5">
						@if (env('FLW_PAYMENT') == 'YES')
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
	<form action="{{ route('payment.flutterwave.enabledisable') }}" method="GET" style="display: none" id="SetupPaymentFormEnableDisable">
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
