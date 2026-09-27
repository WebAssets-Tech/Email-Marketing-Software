<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	@yield('css')

	<link rel="stylesheet" href="{{ filePath('dist/css/app.css') }}" />
	<link rel="stylesheet" href="{{ filePath('dist/css/custome-dashboard.css') }}" />

	{{-- RLT --}}

	@if (checkDBConnection() == true && Schema::hasTable('organization_setups'))
		@if (checkthemeDirection()->exists())
			@if (themeDirection() == 'rtl')
				<link rel="stylesheet" href="{{ filePath('assets/css/rtl.css') }}">
			@endif
		@endif
	@endif

	{{-- RLT::END --}}

	<link rel="stylesheet" href="{{ filePath('assets/css/style.css') }}">

	@if (checkDBConnection() == true && Schema::hasTable('organization_setups'))
		@if (checkthemeLayout()->exists())
			@if (themeLayout() == 'classic')
				<link rel="stylesheet" href="{{ filePath('assets/css/classic.css') }}">
			@else
				<style>
					.app {
						background-color: {
								{
								color()
							}
						}

						;
					}
				</style>
			@endif
		@else
			<link rel="stylesheet" href="{{ filePath('assets/css/classic.css') }}">
		@endif
	@endif


	<meta property="og:title" content="@yield('head')">
	<meta property="og:type" content="website">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">




	@if (checkDBConnection() == true && Schema::hasTable('organization_setups'))
		@if (Schema::hasColumn('organization_setups', 'company_name') || Schema::hasColumn('organization_setups', 'logo'))
			<link href="{{ favIcon() }}" rel="shortcut icon">
			<meta property="og:url" content="{{ org('company_name') ?? 'Maildoll' }}">
			<meta property="og:image" content="{{ logo() }}">
		@else
			<link href="{{ favIcon() }}" rel="shortcut icon">
		@endif
	@endif

	@if (checkDBConnection() == true && Schema::hasTable('seos'))
		<meta name="description" content="{{ seo('description') ?? null }}">
		<meta name="keywords" content="{{ seo('keywords') ?? null }}">

		{{-- OPEN GRAPH --}}
		<meta name="og:description" content="{{ seo('description') ?? null }}">
	@endif

	@yield('head')

	<script src="{{ filePath('bladejs/google-translate.js') }}"></script>

	@notifyCss

	<!-- END: CSS Assets-->
</head>

<body>
	<h2 class="intro-y text-lg font-medium mt-10">@translate(Campaign Statistics)</h2>
	<div class="grid grid-cols-12 gap-6 mt-5">
		<!-- BEGIN: Data List -->
		<div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
			<table class="table table-report -mt-2">
				<thead>
					<tr>
						<th class="whitespace-no-wrap">@translate(ID)</th>
						<th class="text-center whitespace-no-wrap">@translate(CAMPAIGN)</th>
						<th class="text-center whitespace-no-wrap">@translate(SERVED)</th>
						<th class="text-center whitespace-no-wrap">@translate(EMAILS)</th>
						<th class="text-center whitespace-no-wrap">@translate(CLICKS)</th>
						<th class="text-center whitespace-no-wrap">@translate(UNIQUE CLICKS)</th>
						<th class="text-center whitespace-no-wrap">@translate(DELIVERED)</th>
						<th class="text-center whitespace-no-wrap">@translate(OPEN RATE)</th>
						<th class="text-center whitespace-no-wrap">@translate(ACTION)</th>
					</tr>
				</thead>

				<tbody class="mailLogName">
					@forelse ($campaigns as $campaign)
						@if ($campaign->campaign_name != null)
							<tr class="intro-x">
								<td class="text-center">
									<div class="w-10 h-10 image-fit zoom-in">
										<img alt="#{{ $campaign->campaign_name->name }}" class="tooltip rounded-full" src="{{ commonAvatar($campaign->campaign_name->name) }}" title="{{ $campaign->campaign_name->name }}">
									</div>
								</td>
								<td class="text-center tooltip" title="@translate(CAMPAIGN)">

									{{ $campaign->campaign_name->name }}

								</td>
								<td class="text-center tooltip" title="@translate(SERVED)">

									{{ campaignTotalRan($campaign->campaign_id) }} Time('s)

								</td>
								<td class="text-center tooltip" title="@translate(EMAILS)">

									{{ campaignTracker($campaign->campaign_id)->total }}

								</td>
								<td class="text-center tooltip" title="@translate(CLICKS)">

									{{ campaignEmailTotalClicks($campaign->campaign_id) }}

								</td>
								<td class="text-center tooltip" title="@translate(UNIQUE CLICKS)">

									{{ campaignEmailUniqueClicks($campaign->campaign_id) }}

								</td>
								<td class="text-center tooltip" title="@translate(DELIVERED)">

									<div class="progress-bar">
										<span class="progress-bar-fill" style="width: {{ campaignDeliveryRate($campaign->campaign_id) }}%;">
											{{ campaignEmailDelivered($campaign->campaign_id) }}/{{ campaignEmailNotOpenedAndNotOpen($campaign->campaign_id) }}
										</span>
									</div>

								</td>
								<td class="text-center tooltip" title="@translate(OPEN RATE)">

									<div class="progress-bar">
										<span class="progress-bar-fill" style="width: {{ campaignOpenRate($campaign->campaign_id) }}%;">
											{{ campaignEmailClicked($campaign->campaign_id) }}/{{ campaignEmailNotOpenedAndNotOpen($campaign->campaign_id) }}
										</span>
									</div>

								</td>

								<td class="text-center">
									<a href="{{ route('tracker.campaign', $campaign->campaign_id) }}" class="maildoll-text-center">
										<i data-feather="eye" class="tooltip" title="@translate(View)"></i>
									</a>
									<a href="{{ route('campaign.export', $campaign->campaign_id) }}" class="maildoll-text-center">
										<i data-feather="database" class="tooltip" title="@translate(Export)"></i>
									</a>
								</td>
							</tr>
						@endif

					@empty
						<td colspan="9">
							<div class="text-center">
								<img src="{{ notFound('log.png') }}" class="m-auto no-shadow" alt="#campaign-not-found">
							</div>
						</td>
					@endforelse
				</tbody>
			</table>
		</div>
		<div class="intro-y col-span-12 text-center">
		</div>

	</div>
	<script src="{{ filePath('dist/js/app.js') }}"></script>
	<!-- END: JS Assets-->

	<script src="{{ filePath('bladejs/mail-logs/index.js') }}"></script>

	<script src="{{ filePath('assets/js/default.js') }}"></script>
	<script src="{{ filePath('assets/js/sweetalert2@10.js') }}"></script>
</body>

</html>
