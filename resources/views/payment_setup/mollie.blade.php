@extends('layout.' . layout())

@section('subhead')
	<title>@translate(Mollie Setup)</title>
@endsection

@section('subcontent')
	<div class="flex items-center mt-8">
		<h2 class="intro-y text-lg font-medium mr-auto">@translate(Mollie Setup)</h2>
	</div>
	<!-- BEGIN: Wizard Layout -->
	<div class="intro-y box mt-5">
 
		<div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200 dark:border-dark-5">
			<!-- BEGIN: Form Layout -->
			<form action="{{ route('mollie.payment.setup') }}" method="POST">
				@csrf
				<div class="intro-y box p-5">
					<div>
						<label>@translate(MOLLIE KEY)</label>
						<input type="text" value="{{ env('MOLLIE_KEY') ?? '' }}" class="input w-full border mt-2" name="MOLLIE_KEY" placeholder="MOLLIE KEY" required>
					</div>

					<div class="mt-3">
						<div>
							<label>@translate(MOLLIE PARTNER ID)</label>
							<input type="text" value="{{ env('MOLLIE_PARTNER_ID') ?? '' }}" class="input w-full border mt-2" name="MOLLIE_PARTNER_ID" placeholder="MOLLIE PARTNER ID" required>
						</div>
					</div>

					<div class="mt-3">
						<div>
							<label>@translate(MOLLIE PROFILE ID)</label>
							<input type="text" value="{{ env('MOLLIE_PROFILE_ID') ?? '' }}" class="input w-full border mt-2" name="MOLLIE_PROFILE_ID" placeholder="MOLLIE PROFILE ID" required>
						</div>
					</div>

					{{-- <div class="mt-3">
						<div>
							<label>@translate(MOLLIE WEBHOOK URL)</label>
							<input type="text" value="{{ route('webhooks.mollie') }}" class="input w-full border mt-2" placeholder="MOLLIE WEBHOOK URL" value="{{ route('webhooks.mollie') }}" disabled>
						</div>
					</div> --}}

					<div class="text-right mt-5">
						@if (env('MOLLIE') == 'YES')
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
	<form action="{{ route('payment.mollie.enabledisable') }}" method="GET" style="display: none" id="SetupPaymentFormEnableDisable">
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
