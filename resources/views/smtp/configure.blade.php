@extends('layout.' . layout())

@section('subhead')
    <title>@translate(SMTP Settings) - {{ $e_server->name }}</title>
@endsection

@section('subcontent')

    <div class="intro-y col-span-12 flex flex-wrap sm:flex-no-wrap items-center mt-2">
        <a href="{{ route('smtp.index') }}" class="button text-white bg-theme-1 shadow-md mr-2">
            @translate(SMTP List)
        </a>

    </div>

    <div class="intro-y flex items-center mt-8">

        <h2 class="text-lg font-medium mr-auto">{{ Str::upper($e_server->name) }} @translate(SERVER)</h2>
    </div>
    <div class="grid grid-cols-12 gap-6">

        <div class="col-span-12 lg:col-span-12 xxl:col-span-12">


            <!-- BEGIN: Social Information -->
            <div class="intro-y box lg:mt-5" id="#social">
                <div class="flex items-center p-5 border-b border-gray-200 dark:border-dark-5">
                    <h2 class="font-medium text-base mr-auto">@translate(Server Information)</h2>
                </div>
                <div class="p-5">
                    <form method="POST" action="{{ route('smtp.configure.update', $mail) }}">
                        @csrf

                        {{-- <input type="hidden" value="{{ $e_server->provider_name }}" name="provider_name"> --}}

                        <div class="grid grid-cols-2 gap-5" x-data="{isMailgun: '{{$e_server->provider_name}}' == 'mailgun' ? true : false}">


                            @can('Admin')
                                <div class="mt-3">
                                    <label>@translate(MAIL MAILER)</label>

                                    <select class="w-full form-select sm:w-1/2 mt-2" name="driver">
                                        @forelse (smtp_driver_list() as $smtp_driver_list)
                                            <option value="{{ $smtp_driver_list }}"
                                                {{ $smtp_driver_list == $e_server->driver ? 'selected' : null }}>
                                                {{ Str::upper($smtp_driver_list) }}</option>
                                        @empty
                                        @endforelse
                                    </select>

                                </div>
                                <div class="mt-3" x-show="isMailgun">
                                    <div class="input-form">
                                        <label class="flex flex-col sm:flex-row" for="api_key"> @translate(API KEY)*</label>
                                        <input type="text" name="api_key" class="input w-64 sm:w-full border mt-2" id="api_key"
                                            placeholder="API SECRET KEY" data-parsley-type="text" value="{{$e_server->api_key}}">
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label>@translate(Provider Name)</label>
                                    <input type="text" name="provider_name" class="input w-full border mt-2"
                                        placeholder="MAIL HOST" value="{{ $e_server->provider_name ?? null }}">
                                </div>
                                <div class="mt-3">
                                    <label>@translate(MAIL HOST)</label>
                                    <input type="text" name="host" class="input w-full border mt-2"
                                        placeholder="MAIL HOST" value="{{ $e_server->host ?? null }}">
                                </div>
                                <div class="mt-3">
                                    <label>@translate(MAIL PORT)</label>
                                    <input type="text" placeholder="MAIL PORT" class="input w-full border mt-2"
                                        name="port" value="{{ $e_server->port ?? null }}">
                                    <small>Your options are port 25, 2525, or 587 for TLS ports. Or, port 465 for SSL <span
                                            class="tooltip" title="help">Help</span> </small>
                                </div>
                                <div class="mt-3">
                                    <label>@translate(MAIL USERNAME)</label>
                                    <input type="text" placeholder="MAIL USERNAME" class="input w-full border mt-2"
                                        name="username" value="{{ $e_server->username ?? null }}">
                                </div>

                                <div class="mt-3">
                                    <label>@translate(MAIL PASSWORD)</label>
                                    <input type="text" name="password" class="input w-full border mt-2"
                                        placeholder="MAIL PASSWORD" value="{{ $e_server->password ?? null }}">
                                </div>
                                <div class="mt-3">
                                    <label>@translate(MAIL ENCRYPTION)</label>

                                    <select class="w-full form-select sm:w-1/2 mt-2" name="encryption">
                                        <option value="" {{ $e_server->encryption == '' ? 'selected' : null }}>No
                                            Encryption</option>
                                        <option value="tls" {{ $e_server->encryption == 'tls' ? 'selected' : null }}>TLS
                                        </option>
                                        <option value="ssl" {{ $e_server->encryption == 'ssl' ? 'selected' : null }}>SSL
                                        </option>
                                    </select>

                                </div>



                                <div class="mt-3">
                                    <label>@translate(MAIL FROM ADDRESS)</label>
                                    <input type="text" class="input w-full border mt-2" placeholder="MAIL FROM ADDRESS"
                                        name="from" value="{{ $e_server->sender_email->sender_email_address ?? null }}">
                                </div>
                                <div class="mt-3">
                                    <label>@translate(MAIL FROM NAME)</label>
                                    <input type="text" class="input w-full border mt-2" placeholder="MAIL FROM NAME"
                                        name="from_name" value="{{ $e_server->sender_email->sender_name ?? null }}">
                                </div>
                            @endcan


                            @can('Customer')
                                <div class="mt-3">
                                    <label>@translate(MAIL FROM ADDRESS)</label>
                                    <input type="text" class="input w-full border mt-2" placeholder="MAIL FROM ADDRESS"
                                        name="from" value="{{ $e_server->sender_email->sender_email_address ?? null }}">
                                </div>
                                <div class="mt-3">
                                    <label>@translate(MAIL FROM NAME)</label>
                                    <input type="text" class="input w-full border mt-2" placeholder="MAIL FROM NAME"
                                        name="from_name" value="{{ $e_server->sender_email->sender_name ?? null }}">
                                </div>
                            @endcan


                        </div>
                        <div class="flex justify-end mt-4">
                            <button type="submit"
                                class="button w-20 bg-theme-1 text-white ml-auto">@translate(Save)</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END: Social Information -->


        </div>
    </div>
@endsection

@section('script')
@endsection