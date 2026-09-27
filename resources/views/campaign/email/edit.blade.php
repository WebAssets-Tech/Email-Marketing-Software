@extends('layout.' . layout())

@section('subhead')
    <title>@translate(Campaign) - {{ $edit_campaign->name }}</title>
@endsection

@section('subcontent')
    <div class="flex items-center mt-8">
        <h2 class="intro-y text-lg font-medium mr-auto">{{ Str::upper($edit_campaign->name) }}</h2>
    </div>


    <!-- BEGIN: Wizard Layout -->
    <div class="intro-y box py-10 sm:py-20 mt-5">
        <div class="wizard flex lg:flex-row justify-center px-5 sm:px-20">
            <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                <button class="w-10 h-10 rounded-full button text-gray-600 bg-gray-200 dark:bg-dark-1">1</button>
                <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto">@translate(Campaign Information)</div>
            </div>
            <div class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10">
                <button class="w-10 h-10 rounded-full button text-gray-600 bg-gray-200 dark:bg-dark-1">2</button>
                <div class="lg:w-32 text-base lg:mt-3 ml-3 lg:mx-auto text-gray-700 dark:text-gray-600">@translate(Choose Template)
                </div>
            </div>
            <div class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10">
                <button class="w-10 h-10 rounded-full button text-gray-600 bg-gray-200 dark:bg-dark-1">3</button>
                <div class="lg:w-32 text-base lg:mt-3 ml-3 lg:mx-auto text-gray-700 dark:text-gray-600">@translate(Update Audiance)
                </div>
            </div>
            <div class="wizard__line hidden lg:block w-2/3 bg-gray-200 dark:bg-dark-1 absolute mt-5"></div>
        </div>
        <div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200 dark:border-dark-5">
            <!-- BEGIN: Form Layout -->
            <form action="{{ route('campaign.emails.update', $edit_campaign->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="intro-y box p-5">
                    <div>
                        <label>@translate(Campaign Name)</label>
                        <input type="text" class="input w-full border mt-2" name="name" id="campaign_name"
                            value="{{ $edit_campaign->name }}" placeholder="Campaign Name">
                    </div>
                    <div class="input-form mt-3">
                        <label class="flex flex-col sm:flex-row"> @translate(Choose SMTP Server)*</label>
                        <select class="w-full form-select sm:w-1/2" name="smtp_server_id" required id="smtp_server_id">
                            @forelse (getSmtpServerWiseList() as $provider => $email_provider)
                                <optgroup label="{{ Str::upper($provider) }} SERVER('s)">
                                    @forelse ($email_provider as $provider)
                                        <option value="{{ $provider->id }}"
                                            {{ $edit_campaign->smtp_server_id == $provider->id ? 'selected' : null }}
                                            class="normal-case">{{ Str::camel($provider->provider_name) }}</option>
                                    @empty
                                    @endforelse
                                </optgroup>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="mt-3">
                        <label>@translate(Description)</label>
                        <div class="mt-2">
                            <textarea data-simple-toolbar="true" class="editor" name="description" id="description">
                                {{ $edit_campaign->description }}
                            </textarea>
                        </div>
                    </div>
                    @if ($edit_campaign->campaign_attachment != null)
                        <div class="mt-3">

                            <a href="{{ filePath($edit_campaign->campaign_attachment->attachment) }}">
                                <img src="{{ filePath('pdf.png') }}" class="w-20" alt="{{ __('Attachment') }}">
                            </a>

                            <div class="mt-3">
                                <a href="{{ route('campaign.remove.pdf', $edit_campaign->id) }}"
                                    class="button w-24 bg-theme-1 text-white">{{ __('Remove attachment') }}</a>
                            </div>
                        </div>
                    @endif

                    <div class="mt-3">
                        <label>@translate(Attachment) <small>(optional)</small> </label>
                        <div class="mt-2">
                            <input type="file" name="new_attachment" class="form-control" accept=".pdf">
                        </div>
                    </div>

                    <div class="mt-3">
                        <label>@translate(Campaign BCC)</label>
                        <input type="text" class="input w-full border mt-2" name="bcc"
                            value="{{ $edit_campaign->bcc }}" placeholder="Campaign BCC" data-parsley-required>
                        <small class="font-bold">This is the email BCC.</small>
                    </div>
                    <div class="mt-3">
                        <label>@translate(Campaign CC)</label>
                        <input type="text" class="input w-full border mt-2" name="cc"
                            value="{{ $edit_campaign->cc }}" placeholder="Campaign CC" data-parsley-required>
                        <small class="font-bold">This is the email CC.</small>
                    </div>

                    <div class="mt-3">
                        <label>Active Status</label>
                        <div class="mt-2">
                            <input type="checkbox" value="1" id="status" class="input input--switch border"
                                name="status" {{ $edit_campaign->status == 1 ? 'checked' : '' }}>
                        </div>
                    </div>


                    <div class="text-right mt-5">
                        <button type="submit" class="button w-40 bg-theme-1 text-white">@translate(Update Information)</button>
                    </div>

                    {{-- TEMPLATE --}}

                    <div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200 dark:border-dark-5">
                        <!-- BEGIN: Form Layout -->

                        <div class="grid grid-cols-12 gap-6 mt-5">
                            @foreach (emailTemplates() as $templates)
                                <div class="intro-y col-span-12 md:col-span-6 lg:col-span-6">
                                    <div class="box">
                                        <div class="flex items-start px-5 pt-5">
                                            <div class="w-full flex flex-col lg:flex-row items-center">
                                                <div class="section over-hide z-bigger">
                                                    <div class="section over-hide z-bigger">
                                                        <div class="container pb-5">
                                                            <div class="row justify-content-center pb-5">
                                                                <div class="col-12 pb-5">
                                                                    <input class="checkbox-tools" type="radio"
                                                                        value="{{ $templates->id }}" name="template_id"
                                                                        id="tool-{{ $templates->id }}"
                                                                        {{ $edit_campaign->template_id == $templates->id ? 'checked' : '' }}>
                                                                    <label class="for-checkbox-tools w-full"
                                                                        for="tool-{{ $templates->id }}">
                                                                        <div class="">
                                                                            <div class="h-60 xxl:h-60 image-fit">
                                                                                <div class="rounded-md preview-template">
                                                                                    {{ $templates->title }}
                                                                                    <div style="background-image: url('{{ filePath($templates->preview ?? notFound('no-preview.png')) }}');"
                                                                                        class="preview-template"></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="text-right mt-5">
                            <button type="submit" class="button w-40 bg-theme-1 text-white">@translate(Update Template)</button>
                        </div>
                        <!-- END: Form Layout -->
                    </div>

                    {{-- TEMPLATE::END --}}


                    {{-- AUDIANCE --}}
                    <div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200 dark:border-dark-5">
                        {{-- EMAILS OR GROUPS --}}
                        <div class="grid grid-cols-12 gap-6 mt-5">
                            <div class="intro-y col-span-12 md:col-span-12 lg:col-span-12">
                                <a href="#emailsSection" onclick="emailsList()" id="">
                                    <div class="box shadow bg-theme-1 text-white">
                                        <div class="flex items-start px-5 pt-5">
                                            <div class="w-full flex flex-col lg:flex-row items-center">
                                                <div
                                                    class="lg:ml-4 text-center flex items-center lg:text-left mt-3 mb-3 lg:mt-0">
                                                    <i data-feather="mail" class="mr-2"></i> @translate(CLICK TO GET EMAILS)
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                        </div>
                        {{-- EMAILS OR GROUPS::END --}}
                        {{-- emails --}}
                        <div id="emails_list" class="hidden">
                            <div id="emailsSection">

                            </div>
                            <!-- BEGIN: Inbox Content -->
                            <div class="intro-y inbox box mt-5">
                                <div class="loader_card animate-pulse col-span-12 lg:col-span-9 xxl:col-span-10 mt-5">
                                    @for ($i = 1; $i < 21; $i++)
                                        <div class="flex">
                                            <div class="rounded-full mt-2 h-8 w-10 bg-gray-400"></div>
                                            <div class="w-full mr-4 ml-4 h-8 mt-2 rounded-full bg-gray-400"></div>
                                            <div class="w-20 h-8 mt-2 rounded-full bg-gray-400"></div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                            {{-- Search String --}}
                            <div
                                class="p-5 flex flex-col-reverse sm:flex-row text-gray-600 border-b border-gray-200 dark:border-dark-1">
                                <div
                                    class="flex items-center mt-3 sm:mt-0 border-t sm:border-0 border-gray-200 pt-5 sm:pt-0 mt-5 sm:mt-0 -mx-5 sm:mx-0 px-5 sm:px-0">
                                    <input class="input border border-gray-500 checkAll" id="check_all" type="checkbox">

                                    <p class="ml-3">
                                        Select all in this page
                                    </p>

                                </div>
                                <div class="flex items-center sm:ml-auto">
                                    <div class="dark:text-gray-300 mx-auto">@translate(Total)
                                        {{ number_format(emailCount()) }}
                                        email(s)
                                    </div>
                                    <div class="ml-0 sm:ml-4 sm:mt-0 mt-2 border rounded shadow-sm p-2">
                                        <div
                                            class="sm:text-right relative text-gray-700 dark:text-gray-300 overflow-hidden">
                                            <input onload="$(this).focus()" class="border-none outline-none ring-0"
                                                name="search" id="search-email"
                                                value="{{ request()->query('search') }}" autofocus
                                                placeholder="Search...">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-search w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0">
                                                <circle cx="11" cy="11" r="8"></circle>
                                                <line x1="21" y1="21" x2="16.65" y2="16.65">
                                                </line>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Search String End --}}

                            <div class="col-span-12 lg:col-span-9 xxl:col-span-10" id="campaignLoadPage"></div>

                        </div>
                        <!-- END: Inbox Content -->

                    </div>
                    {{-- emails:END --}}

                </div>

                <!-- END: Form Layout -->
        </div>
        {{-- AUDIANCE::END --}}

    </div>
    </form>
    <!-- END: Form Layout -->
    </div>
    </div>
    <!-- END: Wizard Layout -->
@endsection

@section('script')
    <input type="hidden" id="campaign_emails_edit_url"
        value="{{ route('campaign.contacts.emails.edit', $edit_campaign->id) }}">
    <input type="hidden" id="campaign_email_fetch_data"
        value="{{ route('campaign.contacts.fetch_data.edit', $edit_campaign->id) }}">

    <script src="{{ filePath('assets/js/jquery.js') }}"></script>

    <script src="{{ filePath('bladejs/campaigns/email/edit.js') }}"></script>
    <script>
        $(document).ready(function() {
            localStorage.removeItem('idsArr');
            var a = null;
            // Parse the serialized data back into an aray of objects
            a = JSON.parse(localStorage.getItem('idsArr')) || [];
            // Push the new data (whether it be an object or anything else) onto the array
            a.push(
                <?php echo CampaignEmailArrayToString($edit_campaign->id); ?>
            );
            // Alert the array value
            // Re-serialize the array back into a string and store it in localStorage
            localStorage.setItem('idsArr', JSON.stringify(a));
        });
    </script>
    <script src="{{ filePath('bladejs/campaigns/email/search.js') }}"></script>
@endsection
