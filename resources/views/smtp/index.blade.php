@extends('../layout/' . layout())

{{-- @section('css')
@alpinejs
@endsection --}}
@section('subhead')
    <title>@translate(Mail Servers)</title>
@endsection

@section('subcontent')
    <div class="flex gap-2 items-center">
        <h2 class="intro-y text-lg font-medium mt-10">@translate(Mail Servers)</h2>
        <a href="javascript:;" data-toggle="modal" data-target="#test-connection"
            class="button button--lg flex items-center justify-center bg-white text-primary mt-10 border-primary">
            @translate(Update Test Email)
        </a>
    </div>
    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y col-span-12 block sm:flex  items-center mt-2">

            @can('Admin')
                <a href="javascript:;" data-toggle="modal" data-target="#superlarge-modal-size-preview-smtp"
                    class="button button--lg flex items-center justify-center bg-theme-4 text-white mt-2">
                    <i class="w-4 h-4 mr-2" data-feather="edit-3"></i> @translate(Create New Server)
                </a>
                @if ($mailgunService != null && $mailgunService->api_key != null)
                    <a href="{{ route('mailgun.domain.index') }}"
                        class="ml-0 sm:ml-4 sm:mt-0 button button--lg flex items-center justify-center bg-theme-4 text-white mt-2">
                        <i class="w-4 h-4 mr-2" data-feather="tool"></i> @translate(Manage Mailgun Domains)
                    </a>
                @endif
            @endcan

            <div class="ml-0 sm:ml-4 sm:mt-0 mt-2">
                <div class="sm:text-right relative text-gray-700 dark:text-gray-300">
                    <input type="text" class="input w-full box pr-10 placeholder-theme-13" placeholder="Search..."
                        id="smsIndex">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i>
                </div>
            </div>

        </div>

    </div>

    <div class="grid mt-5">
        @forelse (getSmtpServerWiseList() as $provider => $email_provider)
            <hr>
            <div class="h-16">
                <h2 class="block font-medium text-base mt-5">{{ Str::upper($provider) }} SERVER('s)</h2>
            </div>
            <hr>

            <div class="grid sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-4 mt-5 mb-5">
                @forelse ($email_provider as $provider)
                    <div class="box">
                        <div class="flex items-start px-5 pt-5 pb-5">
                            <div class="w-full flex flex-col lg:flex-row">

                                <h2 class="block font-medium text-base mt-5">{{ Str::upper($provider->provider_name) }}
                                    SERVER</h2>

                                <div class="mt-3">
                                    @can('Admin')
                                        <h3 class="block mt-3">Driver: {{ $provider->driver }}</h3>
                                        <h3 class="block mt-3">Host: {{ $provider->host }}</h3>
                                        <h3 class="block mt-3">Port: {{ $provider->port }}</h3>
                                        <h3 class="block mt-3">Username: {{ $provider->username }}</h3>
                                        <h3 class="block mt-3">Encryption: {{ $provider->encryption }}</h3>
                                        <h3 class="block mt-3">Test Connection: {{ $testConnectionEmail }}</h3>
                                    @endcan
                                    <h3 class="block mt-3">Sender Email:
                                        {{ $provider->sender_email->sender_email_address ?? null }}</h3>
                                    <h3 class="block mt-3">Sender Name: {{ $provider->sender_email->sender_name ?? null }}
                                    </h3>
                                </div>

                                <div class="mt-5">
                                    @can('Admin')
                                        <a href="{{ route('smtp.configure', $provider->id) }}"
                                            class="button button--sm inline-block mb-3 text-white bg-theme-4 mr-2">@translate(Re - configure)</a>

                                        <a href="{{ route('smtp.configure.destroy', $provider->id) }}"
                                            class="button button--sm inline-block mb-3 text-white bg-theme-6 mr-2">@translate(Remove)</a>
                                    @endcan

                                    @can('Customer')
                                        <a href="{{ route('smtp.configure', $provider->id) }}"
                                            class="button button--sm inline-block mb-3 text-white bg-theme-4 mr-2">@translate(Update Sender Information)</a>
                                        @if ($provider->api_key != null)
                                            @if ($provider->domain)
                                                <a href="{{ route('mailgun.domain.show', $provider->domain->name) }}"
                                                    class="button button--sm inline-block mb-3 text-white bg-theme-4 mr-2">
                                                    @translate(Verify Doamin)
                                                </a>
                                            @else
                                                <a href="javascript:;" data-toggle="modal"
                                                    data-target="#domain-modal-{{ $provider->id }}"
                                                    class="button button--sm inline-block mb-3 text-white bg-theme-4 mr-2">
                                                    @translate(Add New Domain)
                                                </a>
                                                {{-- MODAL --}}
                                                <div class="modal" id="domain-modal-{{ $provider->id }}">
                                                    <div class="modal__content modal__content--xl p-10">
                                                        <div class="intro-y items-center mt-8">
                                                            <h2 class="text-lg font-medium mr-auto">@translate(Add New Domain)</h2>

                                                            <form action="{{ route('mailgun.domain.store') }}" class="mt-6"
                                                                method="post">
                                                                @csrf
                                                                <div class="input-form mb-2">
                                                                    <label class="flex flex-col sm:flex-row" for="domain_name">
                                                                        @translate(Domain Name)*</label>
                                                                    <input type="text" name="domain_name"
                                                                        class="input w-64 sm:w-full border mt-2"
                                                                        id="domain_name" placeholder="example.com"
                                                                        data-parsley-type="text" required>
                                                                </div>
                                                                <input type="text" class="hidden"
                                                                    value="{{ $provider->id }}" name="email_service_id">

                                                                <div class="text-end">
                                                                    <button type="submit"
                                                                        class="button text-white bg-theme-1 mr-2">@translate(Next)</button>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endcan

                                    <a href="{{ route('smtp.connection.test', $provider->id) }}"
                                        class="button button--sm inline-block mb-3 text-white bg-theme-7 mr-2">@translate(Test Connection)</a>

                                </div>

                            </div>
                        </div>
                    </div>



                @empty
                @endforelse
            </div>

        @empty
        @endforelse
    </div>

    {{-- MODAL --}}
    <div class="modal" id="test-connection">
        <div class="modal__content modal__content--xl p-10">
            <div class="intro-y items-center mt-8">
                <h2 class="text-lg font-medium mr-auto">@translate(Test connection email)</h2>
            </div>
            <div class="grid grid-cols-12 gap-12 mt-5">
                <div class="intro-y col-span-12 lg:col-span-12">
                    <!-- BEGIN: Form Layout -->

                    <form class="" enctype="multipart/form-data" action="{{ route('env.update') }}" name="myform"
                        method="POST">
                        @csrf

                        <div class="mt-6">
                            <div class="input-form">
                                <label class="flex flex-col sm:flex-row" for="from"> @translate(Test Connection ) Email</label>
                                <input type="email" name="TEST_CONNECTION_MAIL" class="input w-64 sm:w-full border mt-2"
                                    id="test_connection_email" placeholder="Email From Address"
                                    value="{{ org('test_connection_email') }}" data-parsley-type="email">
                            </div>
                        </div>
                </div>

                <div>
                    <button type="submit" class="button text-white bg-theme-1 mr-2">@translate(Save)</button>
                </div>

                </form>
                <!-- END: Form Layout -->

            </div>
        </div>
    </div>



    {{-- MODAL --}}
    <div class="modal" id="superlarge-modal-size-preview-smtp">
        <div class="modal__content modal__content--xl p-10">
            <div class="intro-y items-center mt-8">
                <h2 class="text-lg font-medium mr-auto">@translate(Add SMTP Server)</h2>

                <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md mt-3"
                    role="alert">
                    <div class="flex">
                        <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path
                                    d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
                            </svg></div>
                        <div>
                            <p class="font-bold">Important message</p>
                            <p class="text-sm"> - Use <strong>Webmail</strong> as your own SMTP server. </p>
                        </div>
                    </div>
                </div>

            </div>
            <div class="grid grid-cols-12 gap-12 mt-5">
                <div class="intro-y col-span-12 lg:col-span-12">
                    <!-- BEGIN: Form Layout -->

                    <form class="" enctype="multipart/form-data" x-data="{ isMailgun: false, provider: '' }"
                        action="{{ route('smtp.configure.store') }}" onsubmit="return validateform()" name="myform"
                        method="POST">
                        @csrf

                        <div class="mt-3">
                            <div class="input-form">
                                <label class="flex flex-col sm:flex-row"> @translate(SMTP Providers)*</label>
                                <select x-model="provider" @change="isMailgun = provider == 'mailgun' ? true : false"
                                    class="w-64 sm:w-full form-select" name="provider_name" required>
                                    @forelse (smtp_provider_list() as $smtp_provider_list)
                                        <option value="{{ Str::lower($smtp_provider_list) }}">{{ $smtp_provider_list }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="mt-6" x-show="isMailgun">
                            <div class="input-form">
                                <label class="flex flex-col sm:flex-row" for="api_key"> @translate(API KEY)*</label>
                                <input type="text" name="api_key" class="input w-64 sm:w-full border mt-2"
                                    id="api_key" placeholder="API SECRET KEY" data-parsley-type="text">
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="input-form">
                                <label class="flex flex-col sm:flex-row"> @translate(Driver)</label>
                                <select class="w-64 sm:w-full form-select" name="driver">
                                    @forelse (smtp_driver_list() as $smtp_driver_list)
                                        <option value="{{ $smtp_driver_list }}">{{ Str::upper($smtp_driver_list) }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="input-form">
                                <label class="flex flex-col sm:flex-row" for="host"> @translate(Provider Name)*</label>
                                <input type="text" name="provider_name" class="input w-64 sm:w-full border mt-2"
                                    id="provider_name" placeholder="Enter Provider Title" data-parsley-type="text"
                                    required>
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="input-form">
                                <label class="flex flex-col sm:flex-row" for="host"> @translate(Host)*</label>
                                <input type="text" name="host" class="input w-64 sm:w-full border mt-2"
                                    id="host" placeholder="smtp.mailtrap.io" data-parsley-type="text" required>
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="input-form">
                                <label class="flex flex-col sm:flex-row" for="Port"> @translate(Port)*</label>
                                <input type="number" name="port" class="input w-64 sm:w-full border mt-2"
                                    id="Port" placeholder="2525" data-parsley-type="number" required>
                                <small class="block">2525, 25, 587, 465</small>
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="input-form">
                                <label class="flex flex-col sm:flex-row" for="username"> @translate(Username)*</label>
                                <input type="text" name="username" class="input w-64 sm:w-full border mt-2"
                                    id="username" placeholder="username" data-parsley-type="text" required>
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="input-form">
                                <label class="flex flex-col sm:flex-row" for="password"> @translate(Password)*</label>
                                <input type="password" name="password" class="input w-64 sm:w-full border mt-2"
                                    id="password" placeholder="password" data-parsley-type="text" required>
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="input-form">
                                <label class="flex flex-col sm:flex-row"> @translate(Security)</label>
                                <select class="w-64 sm:w-full form-select" name="encryption">
                                    <option value="">No Encryption</option>
                                    <option value="tls">TLS</option>
                                    <option value="ssl">SSL</option>
                                </select>
                            </div>
                        </div>


                        <div class="mt-6">
                            <div class="input-form">
                                <label class="flex flex-col sm:flex-row" for="from"> @translate(Email From Address)*</label>
                                <input type="email" name="from" class="input w-64 sm:w-full border mt-2"
                                    id="from" placeholder="Email From Address" data-parsley-type="email" required>
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="input-form">
                                <label class="flex flex-col sm:flex-row" for="from_name"> @translate(Email From Name)*</label>
                                <input type="text" name="from_name" class="input w-64 sm:w-full border mt-2"
                                    id="from_name" placeholder="Email From Name" data-parsley-type="text" required>
                            </div>
                        </div>


                </div>

                <div>
                    <button type="submit" class="button text-white bg-theme-1 mr-2">@translate(Save)</button>
                </div>

                </form>
                <!-- END: Form Layout -->

            </div>
        </div>
    </div>


    {{-- MODAL::END --}}

@endsection

@section('script')
@endsection
