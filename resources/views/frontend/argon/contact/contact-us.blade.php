@extends('frontend.argon.layouts.master')

@section('content')
	<!-- Section 1 -->
	<section class="space-5 pb-5">
		<div class="container text-center">
			<h1 class="font-weight-bold display-4">Let's Talk</h1>
			<p class="lead w-lg-75 mx-auto">Let's talk about everything to make the products you love most and succeed your business. We will reply within 1 business day 100% of the time!</p>
		</div>
	</section>
	<!-- End Section 1 -->
	<!-- Section 2 -->
	<section class="space-3 pt-0">
		<div class="container">
			<div class="card card-body px-md-5 py-md-5">
				<h6 class="font-weight-bold">Your Information</h6>
				@if(session()->has('message'))
					<div class="bg-indigo-100 rounded-lg py-5 px-6 mb-4 text-base text-indigo-700 mb-3" role="alert">
						{{ session()->get('message') }}
					</div>
				@endif


				<form class="mt-4" action="{{ route('contact.store') }}" method="POST">
					@csrf
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Full name</label>
								<input type="text" class="form-control @error('full_name') is-invalid @enderror" id="name" name="name" placeholder="Your full name" required>
								@error('full_name')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="email">Email address</label>
								<input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Your working email" required>
								@error('email')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
								<label for="company">Company</label>
								<input type="text" class="form-control @error('company') is-invalid @enderror" id="company" name="company" placeholder="Your company" required>
								@error('company')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
										
									</span>
								@enderror
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="subject">Subject</label>
								<input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" placeholder="Your subject" required>
								@error('subject')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>
						<div class="col-12 mt-4">
							<div class="form-group">
								<label for="message">Message</label>
								<textarea class="form-control  @error('message') is-invalid @enderror" id="message" rows="10" name="message" placeholder="Your message" required></textarea>
								@error('message')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>
					</div>
					<button type="submit" class="btn btn-primary btn-block">Send Message</button>
				</form>
			</div>
		</div>
	</section>
	<!-- End Section 2 -->

	<!-- Section 3 -->
	<section class="space-3 pt-0">
		
			<div class="container">
				<div class="row text-center justify-content-around">
					@php
						$items = [
							['id' => 'img100','defaultUrl'=>filePath('frontend/argon/assets/img/MdiPhone.svg'),  'title'=>'phone','description'=>'We will reply within 1 business day 100% of the time', 'value'=>'+6548715987', 'hyperlink'=>'+89974'],
							['id' => 'img101','defaultUrl'=>filePath('frontend/argon/assets/img/MdiEmail.svg'), 'title'=>'Email','description'=>'We will reply within 1 business day 100% of the time', 'value'=>'hello@mail.com', 'hyperlink'=>'hello@mail.com'],
							['id' => 'img102','defaultUrl'=>filePath('frontend/argon/assets/img/MdiMapMarker.svg'), 'title'=>'Get Location','description'=>'We will reply within 1 business day 100% of the time', 'value'=>"hello", 'hyperlink'=>'google.com'],
						]
					@endphp
					@foreach ($items as $key => $item)
					@php
						$icon_id = 'img'.'11105'.$key;
						$title_id = '11106'.$key;
						$upload_id = 7+$key;
						$description_id = '11107'.$key;
						$value_id = '11108'.$key;
						$hyperlink_id = '11109'.$key;
					@endphp
								<div class="col-md-6 col-lg-3 mb-4 mb-lg-0" data-aos="zoom-in-up" data-aos-delay="100">
										<div class="px-4 py-4 hover-translate-y h-100">
												@can('Admin')
														<div class="avatar-upload">
																<div class="avatar-edit">
																		<input type='file' id="imageUpload{{$upload_id}}" accept=".png, .jpg, .jpeg" />
																		<label for="imageUpload{{$upload_id}}"></label>
																</div>
																<div class="avatar-preview">
																	<div id="imagePreview{{$upload_id}}" class="liveImagePreview{{$upload_id}} w-12 h-12" data-img={{$icon_id}}
																			style="background-image: url({{ argonContent($icon_id) != null ? argonImagePath(argonContent($icon_id)) : $item['defaultUrl'] }});">
																	</div>
																</div>
															</div>

															@else
															<img class="w-12 h-12"  data-img={{$icon_id}}
																	src="{{ argonContent($icon_id) != null ? argonImagePath(argonContent($icon_id)) : $item['defaultUrl'] }}"
																	alt="Illustration" height="100px" width="100px">
												@endcan

												@can('Admin')
														<h5 class="mt-4 mb-2 editable" data-cid="{{$title_id}}">{{argonContent($title_id) ?? $item['title']}}</h5>
														<p class="editable" data-cid="{{$description_id}}">{{argonContent($description_id) ?? $item['description']}}</p>
														<p  data-toggle="modal" data-target="#exampleModal{{$item['id']}}">
															{{ argonContent($value_id) ?? $item['value'] }}
														</p>
															<div class="modal fade h-auto" id="exampleModal{{$item['id']}}" tabindex="-1">
																<div class="modal-dialog">
																		<div class="modal-content">
																		<div class="modal-body">
																				
																				<div class="mb-3">
																					<label for="">Contact Value</label>
																						<p class="editable is-modified border p-2"
																								data-cid="{{$value_id}}"
																								tabindex="1">
																								{{ argonContent($value_id) ?? $item['value'] }}
																						</p>
																				</div>
																				<div class="mb-3">
																					<label for="">Hyper Link</label>
																						<p class="editable is-modified border p-2"
																								data-cid="{{$hyperlink_id}}"
																								tabindex="1">
																								{{ argonContent($hyperlink_id) ?? $item['hyperlink'] }}
																						</p>
																				</div>
						
																		</div>
																		<div class="modal-footer">
																				<button type="button" class="btn-sm btn-primary" data-dismiss="modal">Save changes</button>
																		</div>
																		</div>
																</div>
															</div>
												@else
														<h5 class="mt-4 mb-2">{{argonContent($title_id)??$item['title']}}</h5>
														<p>{{argonContent($description_id)??$item['description']}}</p>
														<a href="{{argonContent($value_id)?? $item['value']}}" >{!!argonContent($value_id)?? $item['value']!!}</a>
														
												@endcan
										</div>
								</div>
					@endforeach
				</div>
			</div>			
	</section>	
@endsection

