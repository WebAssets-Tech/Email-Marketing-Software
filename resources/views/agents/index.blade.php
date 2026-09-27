@extends('layout.' . layout())

@section('subhead')
	<title>@translate(Agents)</title>
@endsection

@section('subcontent')

	<h2 class="intro-y text-lg font-medium mt-10">@translate(Agents)</h2>
	<div class="grid grid-cols-12 gap-6 mt-5">

		<div class="intro-y col-span-12 flex flex-wrap sm:flex-no-wrap items-center mt-2">
			<a href="javascript:;" data-toggle="modal" data-target="#superlarge-limit-modal-size-preview" class="button text-white bg-theme-1 shadow-md mr-2 w-4/12 tooltip" title="@translate(Add New Agent)">
				@translate(Add New Agent)
			</a>
		</div>
		<!-- BEGIN: Users Layout -->		
		@forelse ($agents as $agent)
			@if ($agent->user != null)
				<div class="intro-y col-span-12 md:col-span-4">
					<div class="box">
						<div class="flex flex-col lg:flex-row items-center p-5 border-b border-gray-200 dark:border-dark-5">
							<div class="w-24 h-24 lg:w-12 lg:h-12 image-fit lg:mr-1">
								<img alt="{{ $agent->user->name }}" class="rounded-full tooltip" title="{{ $agent->user->name }}" src="{{ namevatar($agent->user->name) }}">
							</div>
							<div class="lg:ml-2 lg:mr-auto lg:text-left mt-3 lg:mt-0">
								<a href="javascript:;" class="font-medium">{{ Str::upper($agent->user->name) }}</a>
								<div class="text-gray-600 text-md font-medium">{{ $agent->user->email }}</div>
								<div class="text-gray-600 text-md font-medium">@translate(Joining Date:) {{ $agent->user->created_at->format('M d, Y') }}</div>
								@if ($agent->user->active == 0)
									<p class="text-red-600">@translate(This agent is currenty restricted)</p>
								@endif
							</div>
							<div class="flex -ml-2 lg:ml-0 lg:justify-end mt-3 lg:mt-0">
								<a href="javascript:;" data-toggle="modal" data-target="#superlarge-agent-modal-size-preview{{ $agent->user->id }}" class="button button--sm text-white bg-theme-4 border border-gray-300 dark:border-dark-5 dark:text-white m-2">
									@translate(Manage)

									<div class="modal" id="superlarge-agent-modal-size-preview{{ $agent->user->id }}">
										<div class="modal__content modal__content--xl p-10">
											<div class="intro-y flex items-center mt-8">
												<h2 class="text-lg font-medium mr-auto">@translate(Add New Agent)</h2>
											</div>
											<div class="grid grid-cols-12 gap-12 mt-5">
												<div class="intro-y col-span-12 lg:col-span-12">
													<!-- BEGIN: Form Layout -->

													<form class="" enctype="multipart/form-data" action="{{ route('agent.update', $agent->user->id) }}" method="POST">
														@csrf

														<div class="mt-3">
															<div class="input-form"> <label class="flex flex-col sm:flex-row"> @translate(Full Name) <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-gray-600">Ex: John Doe</span>
																</label> <input type="text" value="{{ $agent->user->name }}" name="name" class="input w-full border mt-2" placeholder="Full Name" data-parsley-required>
															</div>
														</div>

														<div class="mt-3">
															<div class="input-form"> <label class="flex flex-col sm:flex-row"> @translate(Email Address) <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-gray-600">Ex: johndoe@mail.com</span>
																</label> <input type="email" value="{{ $agent->user->email }}" name="email" class="input w-full border mt-2" placeholder="Email Address" data-parsley-required>
															</div>
														</div>

														<div class="mt-3">
															<div class="input-form"> <label class="flex flex-col sm:flex-row"> @translate(Password) <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-gray-600">Ex: 12345678</span>
																</label> <input type="password" name="password" class="input w-full border mt-2" placeholder="Password">
															</div>
														</div>

														<button type="submit" class="button bg-theme-1 text-white mt-5">@translate(Update)
														</button>
													</form>
													<!-- END: Form Layout -->

												</div>
											</div>
										</div>
									</div>

								</a>
								<a href="{{ route('agent.restricted', $agent->user->id) }}" class="button button--sm text-white bg-theme-9 border border-gray-300 dark:border-dark-5 dark:text-white m-2">
									@if ($agent->user->active == 0)
										@translate(Restore)
									@else
										@translate(Restrict)
									@endif
								</a>
								<a href="{{ route('agent.destroy', $agent->user->id) }}" class="button button--sm text-white bg-theme-7 border border-gray-300 dark:border-dark-5 dark:text-white m-2">
									@translate(Remove)
								</a>
							</div>
						</div>
					</div>
				</div>
			@endif

		@empty
		@endforelse
	</div>


	{{-- modal --}}

	<div class="modal" id="superlarge-limit-modal-size-preview">
		<div class="modal__content modal__content--xl p-10">
			<div class="intro-y flex items-center mt-8">
				<h2 class="text-lg font-medium mr-auto">@translate(Add New Agent)</h2>
			</div>
			<div class="grid grid-cols-12 gap-12 mt-5">
				<div class="intro-y col-span-12 lg:col-span-12">
					<!-- BEGIN: Form Layout -->

					<form class="" enctype="multipart/form-data" action="{{ route('agent.store') }}" method="POST">
						@csrf

						<div class="mt-3">
							<div class="input-form"> <label class="flex flex-col sm:flex-row"> @translate(Full Name) <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-gray-600">Ex: John Doe</span>
								</label> <input type="text" name="name" class="input w-full border mt-2" placeholder="Full Name" data-parsley-required>
							</div>
						</div>

						<div class="mt-3">
							<div class="input-form"> <label class="flex flex-col sm:flex-row"> @translate(Email Address) <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-gray-600">Ex: johndoe@mail.com</span>
								</label> <input type="email" name="email" class="input w-full border mt-2" placeholder="Email Address" data-parsley-required>
							</div>
						</div>

						<div class="mt-3">
							<div class="input-form"> <label class="flex flex-col sm:flex-row"> @translate(Password) <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-gray-600">Ex: 12345678</span>
								</label> <input type="password" name="password" class="input w-full border mt-2" placeholder="Password" data-parsley-required>
							</div>
						</div>

						<button type="submit" class="button bg-theme-1 text-white mt-5">@translate(Create)
						</button>
					</form>
					<!-- END: Form Layout -->

				</div>
			</div>
		</div>
	</div>

	</div>

	{{-- modal::END --}}


@endsection

@section('script')
	<script src="{{ filePath('assets/js/jquery.js') }}"></script>
	<script src="{{ filePath('assets/js/parsley.js') }}"></script>
	<script src="{{ filePath('assets/js/validation.js') }}"></script>
@endsection
