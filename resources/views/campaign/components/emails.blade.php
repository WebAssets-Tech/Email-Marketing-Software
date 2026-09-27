            <button type="submit"
                class="button w-50 ml-8 mt-5 mr-2 mb-3 flex items-center justify-center bg-theme-1 text-white campaign-email">
                <i data-feather="activity" class="w-4 h-4 mr-2"></i>
                @translate(Save Campaign)
            </button>

            <div id="emailsSection"></div>

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
                {{-- Search String --}}
                <div
                    class="p-5 flex flex-col-reverse sm:flex-row text-gray-600 border-b border-gray-200 dark:border-dark-1">
                    <div
                        class="flex items-center mt-3 sm:mt-0 border-t sm:border-0 border-gray-200 pt-5 sm:pt-0 mt-5 sm:mt-0 -mx-5 sm:mx-0 px-5 sm:px-0">

                        <label class="mt-3 flex items-center gap-3">
                            <input class="input border border-gray-500 checkAll" id="check_all" type="checkbox">
                            <span>Select all in this page</span>
                        </label>
                        
                    </div>
                    <div class="flex items-center sm:ml-auto">
                        <div class="dark:text-gray-300 mx-auto">@translate(Total) {{ number_format(emailCount()) }}
                            email(s)
                        </div>
                        <div class="ml-0 sm:ml-4 sm:mt-0 mt-2 border rounded shadow-sm p-2">
                            <div class="sm:text-right relative text-gray-700 dark:text-gray-300 overflow-hidden">
                                <input onload="$(this).focus()" class="border-none outline-none ring-0" name="search"
                                    id="search-email" value="{{ request()->query('search') }}" autofocus
                                    placeholder="Search...">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-search w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Search String End --}}
                <div class="col-span-12 lg:col-span-9 xxl:col-span-10" id="campaignLoadPage"></div>

            </div>
            <!-- END: Inbox Content -->

            {{-- Groups --}}
            <div class="grid grid-cols-12 gap-6 mt-5" id="groupsSection">
                @foreach (emailGroups('email') as $group)
                    <div class="intro-y col-span-12 md:col-span-6 lg:col-span-4">
                        <div class="box">
                            <div class="flex items-start px-5 pt-5">
                                <div class="w-full flex flex-col lg:flex-row items-center">
                                    <div class="">
                                        <img alt="#{{ $group->name }}" class="rounded-md"
                                            src="{{ commonAvatar($group->name) }}">
                                    </div>

                                    <div class="lg:ml-4 text-center lg:text-left mt-3 mb-3 lg:mt-0">
                                        <input type="checkbox" value="{{ $group->id }}" id="{{ $group->id }}"
                                            data-group-id="{{ $group->id }}"
                                            class="input mt-3 group_id input--switch border" name="group_id">
                                        <label for="{{ $group->id }}">
                                            {{ $group->name }}
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{-- Groups:END --}}

            <button type="submit"
                class="button w-50 ml-8 mt-5 mr-2 mb-3 flex items-center justify-center bg-theme-1 text-white campaign-email">
                <i data-feather="activity" class="w-4 h-4 mr-2"></i>
                @translate(Save Campaign)
            </button>

            <input type="hidden" value="{{ route('campaign.emails.store') }}" id="campaign_email_url">
            <input type="hidden" value="{{ route('campaign.index') }}" id="campaign_list_url">

            <input type="hidden" id="campaign_emails_url" value="{{ route('campaign.contacts.emails') }}">
            <input type="hidden" id="campaign_email_fetch_data" value="{{ route('campaign.contacts.fetch_data') }}">

            <script src="{{ filePath('assets/js/jquery.js') }}"></script>

            <script src="{{ filePath('bladejs/campaigns/components/emails.js') }}"></script>
            <script src="{{ filePath('bladejs/campaigns/email/search.js') }}"></script>
