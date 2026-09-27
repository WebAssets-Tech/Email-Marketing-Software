@extends('../layout/' . layout())

@section('subhead')
    <title>@translate(SMS Providers)</title>
@endsection

@section('subcontent')
    <h2 class="intro-y text-lg font-medium mt-10">@translate(SMS Providers)</h2>
    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y col-span-12 block sm:flex  items-center mt-2">

            @can('Admin')
                <a href="javascript:;" data-toggle="modal" data-target="#superlarge-modal-size-preview-smtp"
                    class="button button--lg flex items-center justify-center bg-theme-4 text-white mt-2">
                    <i class="w-4 h-4 mr-2" data-feather="edit-3"></i> @translate(Create New SMS Server)
                </a>
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
        @forelse (getSMSServerWiseList() as $provider => $sms_provider)
            <hr>
            <div >
                <h2 class="block font-medium text-base mt-5">{{ $provider == 'aakash' ? 'VEDALLY' : Str::upper($provider) }}
                    SERVER('s)</h2>
                @if ($provider == 'whatsapp')
                    <div style="width:max-content; max-width: max-content;" class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md mt-3"
                        role="alert">
                        <div class="flex" style="width:max-content; max-width: max-content;">
                            <div class="py-1" style="width:max-content; max-width: max-content;"><svg class="fill-current h-6 w-6 text-teal-500 mr-4"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path
                                        d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
                                </svg></div>
                            <div style="width:max-content; max-width: max-content;">
                                <p class="font-bold" style="width:max-content; max-width: max-content;">Things to consider when sending Whatsapp messages.</p>
                                <ol class="text-sm" style="width:max-content; max-width: max-content;">
                                    <li>1. Your customer must send the initial message to you.</li>
                                    <li>2. Once the conversation starts, there is a 24-hour window for sending any message content.</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <hr>

            <div class="grid sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-4 mt-5 mb-5">
                @forelse ($sms_provider as $provider)
                    <div class="box">
                        <div class="flex items-start px-5 pt-5 pb-5">
                            <div class="w-full flex flex-col lg:flex-row">

                                <h2 class="block font-medium text-base mt-5">
                                    {{ $provider->sms_name == 'aakash' ? 'VEDALLY' : Str::upper($provider->sms_name) }}
                                    SERVER</h2>


                                <div class="mt-3">
                                    @can('Admin')
                                        <h3 class="block mt-3">SMS ID: {{ $provider->sms_id }}</h3>
                                        <h3 class="block mt-3">SMS TOKEN: {{ $provider->sms_token }}</h3>
                                        @if ($provider->url)
                                            <h3 class="block mt-3">URL: {{ $provider->url }}</h3>
                                        @endif
                                    @endcan

                                    <h3 class="block mt-3">Sender From: {{ $provider->sms_sender_id->sms_from ?? null }}
                                    </h3>
                                    <h3 class="block mt-3">Sender Number: {{ $provider->sms_sender_id->sms_number ?? null }}
                                    </h3>

                                </div>

                                <div class="mt-5">
                                    @can('Admin')
                                        <a href="{{ route('sms.admin.configure.edit', $provider->id) }}"
                                            class="button button--sm inline-block mb-3 text-white bg-theme-4 mr-2">@translate(Re - configure)</a>

                                        <a href="{{ route('sms.admin.configure.destroy', $provider->id) }}"
                                            class="button button--sm inline-block mb-3 text-white bg-theme-6 mr-2">@translate(Remove)</a>
                                    @endcan

                                    @can('Customer')
                                        <a href="{{ route('sms.admin.configure.edit', $provider->id) }}"
                                            class="button button--sm inline-block mb-3 text-white bg-theme-4 mr-2">@translate(Update Sender Information)</a>
                                    @endcan

                                    <a href="{{ route('sms.connection.test', $provider->id) }}"
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
    <div class="modal" id="superlarge-modal-size-preview-smtp">
        <div class="modal__content modal__content--xl p-10">
            <div class="intro-y items-center mt-8">
                <h2 class="text-lg font-medium mr-auto">@translate(Add SMS Server)</h2>
            </div>

            <div class="grid grid-cols-12 gap-12 mt-5">
                <div class="intro-y col-span-12 lg:col-span-12">
                    <!-- BEGIN: Form Layout -->

                    <form class="" enctype="multipart/form-data" action="{{ route('sms.admin.configure') }}"
                        onsubmit="return validateform()" name="myform" method="GET">
                        <div class="mt-3">
                            <div class="input-form">
                                <label class="flex flex-col sm:flex-row"> @translate(SMS Providers)*</label>
                                <select class="w-64 sm:w-full form-select" name="sms_name" required>
                                    @forelse (sms_provider_list() as $sms_provider_list)
                                        <option value="{{ Str::lower($sms_provider_list) }}">
                                            {{ $sms_provider_list == 'aakash' ? 'vedally' : $sms_provider_list }}</option>
                                    @empty
                                    @endforelse
                                </select>
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
    </div>

    {{-- MODAL::END --}}

@endsection

@section('script')
@endsection
