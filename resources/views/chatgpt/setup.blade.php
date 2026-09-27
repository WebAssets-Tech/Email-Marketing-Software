@extends('layout.' . layout())

@section('subhead')
	<title>@translate(ChatGTP Setup)</title>
@endsection

@section('subcontent')
	<div class="flex items-center mt-8">
		<h2 class="intro-y text-lg font-medium mr-auto">@translate(ChatGTP Setup)</h2>
	</div>
	<!-- BEGIN: Wizard Layout -->
	<div class="intro-y box mt-5">
 
		<div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200 dark:border-dark-5">
			<!-- BEGIN: Form Layout -->
			<form action="{{ route('chat.gpt.setup.update') }}" method="GET">
				<div class="intro-y box p-5">
					<div>
						<label>@translate(ChatGPT API Key)</label>
						<input type="text" value="{{ env('CHATGPT_API_KEY') ?? '' }}" class="input w-full border mt-2" name="CHATGPT_API_KEY" placeholder="ChatGPT API Key">
					</div>

					<div class="text-right mt-5">
						<button type="submit" class="button w-24 bg-theme-1 text-white">@translate(Update)</button>
					</div>
				</div>
			</form>
			<!-- END: Form Layout -->
		</div>
	</div>
	<!-- END: Wizard Layout -->
@endsection

@section('script')
	
@endsection
