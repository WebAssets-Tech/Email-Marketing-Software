@extends('frontend.argon.layouts.master')

@section('content')
	<!-- Section 1 -->
	<section class="space-5 pb-5">
		<div class="container text-center">
			<h1 class="font-weight-bold display-4">{{ $page->title }}</h1>
			<div>
				
				{!! $page->body !!}
			</div>
		</div>
	</section>
	<!-- End Section 1 -->
@endsection