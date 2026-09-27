@extends('../layout/' . layout())

@section('subhead')
    <title>{{ username() }} Profile</title>
    <style>
        .required-asterisk {
            color: #dc2626;
            margin-left: 2px;
        }

        .parsley-errors-list {
            list-style: none;
            padding: 0;
            margin: 5px 0 0 0;
            color: #dc2626;
            font-size: 0.875rem;
        }

        .parsley-errors-list li {
            margin-top: 3px;
        }

        .parsley-error {
            border-color: #dc2626 !important;
        }
    </style>
@endsection

@section('subcontent')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">{{ org('company_name') ?? 'Maildoll' }}</h2>
    </div>
    <div class="grid grid-cols-12 gap-6">
        <!-- BEGIN: Profile Menu -->
        @include('settings.organization.components.side-menu')
        <!-- END: Profile Menu -->
        <div class="col-span-12 lg:col-span-8 xxl:col-span-9">
            <form action="{{ route('org.setup') }}" method="post" enctype="multipart/form-data">
                @csrf
                <!-- BEGIN: Company Information -->
                <div class="intro-y box lg:mt-5">
                    <div class="flex items-center p-5 border-b border-gray-200 dark:border-dark-5">
                        <h2 class="font-medium text-base mr-auto">@translate(Organization Setup)</h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-5" id="logo">
                            <!-- Header Logo Section -->
                            <div class="col-span-12 xl:col-span-4">
                                <div class="border border-gray-200 dark:border-dark-5 rounded-md p-5">
                                    <div class="w-40 h-40 relative image-fit cursor-pointer zoom-in mx-auto">
                                        <img class="rounded-md object-contain" alt="{{ orgName() }}"
                                            src="{{ logo() }}">
                                    </div>
                                    <div class="w-40 mx-auto cursor-pointer relative mt-5">
                                        <button type="button"
                                            class="button w-full bg-theme-1 text-white">@translate(Change Logo)</button>
                                        <input type="file" name="logo"
                                            class="w-full h-full top-0 left-0 absolute opacity-0">
                                    </div>
                                    <!-- Hint for Header Logo -->
                                    <p class="text-xs text-gray-500 mt-2 text-center">Recommended header logo size: 450x150
                                        px (3:1 ratio).</p>
                                </div>
                            </div>

                            <!-- Favicon Section -->
                            <div class="col-span-12 xl:col-span-4">
                                <div class="border border-gray-200 dark:border-dark-5 rounded-md p-5">
                                    <div class="w-40 h-40 relative image-fit cursor-pointer zoom-in mx-auto">
                                        <img class="rounded-md object-contain" alt="{{ orgName() }}"
                                            src="{{ favIcon() }}">
                                    </div>
                                    <div class="w-40 mx-auto cursor-pointer relative mt-5">
                                        <button type="button"
                                            class="button w-full bg-theme-1 text-white">@translate(Change Favicon)</button>
                                        <input type="file" name="favIcon"
                                            class="w-full h-full top-0 left-0 absolute opacity-0">
                                    </div>
                                    <!-- Hint for Favicon -->
                                    <p class="text-xs text-gray-500 mt-2 text-center">Recommended favicon size: 1:1 aspect
                                        ratio (e.g., 64x64 px or 128x128 px).</p>
                                </div>
                            </div>

                            <!-- Footer Logo Section -->
                            <div class="col-span-12 xl:col-span-4">
                                <div class="border border-gray-200 dark:border-dark-5 rounded-md p-5">
                                    <div class="w-40 h-40 relative image-fit cursor-pointer zoom-in mx-auto">
                                        <img class="rounded-md object-contain" alt="{{ orgName() }}"
                                            src="{{ footerLogo() }}">
                                    </div>
                                    <div class="w-40 mx-auto cursor-pointer relative mt-5">
                                        <button type="button"
                                            class="button w-full bg-theme-1 text-white">@translate(Change Footer Logo)</button>
                                        <input type="file" name="footer_logo"
                                            class="w-full h-full top-0 left-0 absolute opacity-0">
                                    </div>
                                    <!-- Hint for Footer Logo -->
                                    <p class="text-xs text-gray-500 mt-2 text-center">Recommended footer logo size: 450x150
                                        px (3:1 ratio).</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Company Information -->


                <!-- BEGIN: Company Information -->
                <div class="intro-y box lg:mt-5" id="#contact">
                    <div class="flex items-center p-5 border-b border-gray-200 dark:border-dark-5">
                        <h2 class="font-medium text-base mr-auto">@translate(Company Information)</h2>
                    </div>
                    <div class="p-5">

                        <div class="col-span-12 xl:col-span-8">
                            <div class="mt-3">
                                <label>@translate(Company Name) <span class="required-asterisk">*</span>
                                    <small>@translate(required)</small> </label>
                                <input type="text" class="input w-full border bg-gray-100 mt-2"
                                    placeholder="@translate(Company Name)" value="{{ org('company_name') ?? null }}"
                                    name="company_name" required data-parsley-required="true"
                                    data-parsley-required-message="Company name is required">
                            </div>
                            <div class="mt-3">
                                <label>@translate(Company Email) <span class="required-asterisk">*</span>
                                    <small>@translate(required)</small> </label>
                                <input type="email" class="input w-full border bg-gray-100 mt-2"
                                    placeholder="@translate(Company Email)" name="company_email"
                                    value="{{ org('company_email') ?? null }}" required data-parsley-required="true"
                                    data-parsley-type="email" data-parsley-required-message="Company email is required"
                                    data-parsley-type-message="Please enter a valid email address">
                            </div>
                            <div class="mt-3">
                                <label>@translate(Company Phone Number) <span class="required-asterisk">*</span>
                                    <small>@translate(required)</small> </label>
                                <input type="tel" class="input w-full border bg-gray-100 mt-2"
                                    placeholder="@translate(Company Phone Number)" name="company_phone_number"
                                    value="{{ org('company_phone_number') ?? null }}" required data-parsley-required="true"
                                    data-parsley-required-message="Company phone number is required">
                            </div>
                        </div>

                    </div>
                </div>
                <!-- END: Company Information -->


                <!-- BEGIN: Contact Information -->
                <div class="intro-y box lg:mt-5" id="#contact">
                    <div class="flex items-center p-5 border-b border-gray-200 dark:border-dark-5">
                        <h2 class="font-medium text-base mr-auto">@translate(Contact Information)</h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-5">
                            <div class="col-span-12 xl:col-span-6">
                                <div class="mt-3">
                                    <label>@translate(Company Telephone Number) <small class="text-gray-500">@translate(optional)</small>
                                    </label>
                                    <input type="text" name="company_tel_number" class="input w-full border mt-2"
                                        placeholder="@translate(Telephone Number)" value="{{ org('company_tel_number') ?? null }}">
                                </div>
                            </div>
                            <div class="col-span-12 xl:col-span-6">
                                <div class="mt-3">
                                    <label>@translate(Company Address) <small class="text-gray-500">@translate(optional)</small>
                                    </label>
                                    <input type="text" class="input w-full border mt-2"
                                        placeholder="@translate(Company Address)" name="company_address"
                                        value="{{ org('company_address') ?? null }}">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- END: Contact Information -->

                <!-- BEGIN: Social Information -->
                <div class="intro-y box lg:mt-5" id="#social">
                    <div class="flex items-center p-5 border-b border-gray-200 dark:border-dark-5">
                        <h2 class="font-medium text-base mr-auto">@translate(Social Information)</h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-5">
                            <div class="col-span-12 xl:col-span-6">
                                <div class="mt-3">
                                    <label>@translate(facebook) <small class="text-gray-500">@translate(optional)</small>
                                    </label>
                                    <input type="text" name="facebook" class="input w-full border mt-2"
                                        placeholder="@translate(Facebook Link)" value="{{ org('facebook') ?? null }}">
                                </div>
                                <div class="mt-3">
                                    <label>@translate(Linkedin) <small class="text-gray-500">@translate(optional)</small>
                                    </label>
                                    <input type="text" name="linkedin" class="input w-full border mt-2"
                                        placeholder="@translate(Linkedin Link)" value="{{ org('linkedin') ?? null }}">
                                </div>
                            </div>
                            <div class="col-span-12 xl:col-span-6">
                                <div class="mt-3">
                                    <label>@translate(Skype) <small class="text-gray-500">@translate(optional)</small>
                                    </label>
                                    <input type="text" class="input w-full border mt-2" name="skype"
                                        placeholder="@translate(Skype Number)" value="{{ org('skype') ?? null }}">
                                </div>
                                <div class="mt-3">
                                    <label>@translate(Twitter) <small class="text-gray-500">@translate(optional)</small>
                                    </label>
                                    <input type="text" class="input w-full border mt-2" name="twitter"
                                        placeholder="@translate(Twitter Link)" value="{{ org('twitter') ?? null }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Social Information -->

                <!-- BEGIN: Application Settings -->
                <div class="intro-y box lg:mt-5" id="#test">
                    <div class="flex items-center p-5 border-b border-gray-200 dark:border-dark-5">
                        <h2 class="font-medium text-base mr-auto">@translate(Application Settings)</h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-5">
                            <div class="col-span-12 xl:col-span-12">
                                <div class="mt-3">
                                    <label>@translate(Test Connection Email) <span class="required-asterisk">*</span>
                                        <small>@translate(required)</small> <small class="text-gray-500">Ex:
                                            demo@maildoll.com</small> </label>
                                    <input type="email" name="test_connection_email" class="input w-full border mt-2"
                                        placeholder="Test Connection Email"
                                        value="{{ org('test_connection_email') ?? null }}" required
                                        data-parsley-required="true" data-parsley-type="email"
                                        data-parsley-required-message="Test connection email is required"
                                        data-parsley-type-message="Please enter a valid email address">
                                </div>
                                <div class="mt-3">
                                    <label>@translate(Test Connection Sms Number) <span class="required-asterisk">*</span>
                                        <small>@translate(required)</small> <small class="text-gray-500">Ex:
                                            +8801825731327</small> </label>
                                    <input type="text" name="test_connection_sms" class="input w-full border mt-2"
                                        placeholder="Test Connection Sms Number"
                                        value="{{ org('test_connection_sms') ?? null }}" required
                                        data-parsley-required="true"
                                        data-parsley-required-message="Test connection SMS number is required">
                                </div>
                                <div class="mt-3">
                                    <label>@translate(Color) <small class="text-gray-500">@translate(optional)</small>
                                    </label>
                                    <input type="text" name="color" class="input w-full border mt-2"
                                        placeholder="#fffff" value="{{ org('color') ?? null }}">
                                </div>

                                <div class="mt-3">
                                    <label for="default_currencies"
                                        class="flex flex-col sm:flex-row">@translate(Default Currency)</label>
                                    <select name="default_currencies" id="default_currencies"
                                        class="w-full form-select sm:w-1/2">
                                        @foreach (\App\Models\Currency::all() as $currency)
                                            <option value="{{ $currency->id }}"
                                                {{ $currency->id == org('default_currencies') ? 'selected' : '' }}>
                                                {{ $currency->name }} ({{ $currency->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mt-3">
                                    <label for="site_timezone"
                                        class="flex flex-col sm:flex-row">@translate(Timezone)</label>
                                    <select name="site_timezone" id="site_timezone" class="w-full form-select sm:w-1/2">
                                        @forelse (timeZone() as $key => $zone)
                                            <option value="{{ $key }}"
                                                {{ $key == env('TIMEZONE') ? 'selected' : null }}>{{ $key }}
                                                -
                                                {{ $zone }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>

                                <div class="mt-5">
                                    <label>@translate(Choose layout) <small>Default is classic</small> </label>
                                </div>
                                {{-- Theme layouts --}}
                                <div class="grid grid-cols-12 gap-6 mt-5">

                                    <div class="intro-y col-span-12 xl:col-span-6">
                                        <div class="box">
                                            <div class="flex items-start px-5 pt-5">
                                                <div class="w-full flex flex-col lg:flex-row items-center">
                                                    <div class="section over-hide z-bigger">
                                                        <div class="section over-hide z-bigger">
                                                            <div class="container pb-5">
                                                                <div class="row justify-content-center pb-5">
                                                                    <div class="col-12 pb-5">
                                                                        <input class="checkbox-tools" type="radio"
                                                                            value="classic"
                                                                            {{ themeLayout() == 'classic' ? 'checked' : null }}
                                                                            name="layout" id="tool-1">
                                                                        <label class="for-checkbox-tools w-full"
                                                                            for="tool-1">
                                                                            <div class="">
                                                                                <div class="h-60 xxl:h-60 image-fit">
                                                                                    <div
                                                                                        class="rounded-md preview-template">
                                                                                        <div style="background-image: url('{{ filePath('layouts/classic.png') }}');"
                                                                                            class="preview-template">
                                                                                        </div>
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



                                    <div class="intro-y col-span-12 xl:col-span-6">
                                        <div class="box">
                                            <div class="flex items-start px-5 pt-5">
                                                <div class="w-full flex flex-col lg:flex-row items-center">
                                                    <div class="section over-hide z-bigger">
                                                        <div class="section over-hide z-bigger">
                                                            <div class="container pb-5">
                                                                <div class="row justify-content-center pb-5">
                                                                    <div class="col-12 pb-5">
                                                                        <input class="checkbox-tools" type="radio"
                                                                            value="modern"
                                                                            {{ themeLayout() == 'modern' ? 'checked' : null }}
                                                                            name="layout" id="tool-2">
                                                                        <label class="for-checkbox-tools w-full"
                                                                            for="tool-2">
                                                                            <div class="">
                                                                                <div class="h-60 xxl:h-60">
                                                                                    <div
                                                                                        class="rounded-md preview-template">
                                                                                        <div style="background-image: url('{{ filePath('layouts/modern.png') }}');"
                                                                                            class="preview-template">
                                                                                        </div>
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

                                </div>
                                {{-- Theme layouts:END --}}


                                {{-- RTL --}}
                                <div class="mt-5">
                                    <label>@translate(Choose Direction) <small>Default is LTR</small> </label>
                                </div>
                                {{-- Theme layouts --}}
                                <div class="grid grid-cols-12 gap-6 mt-5">

                                    <div class="intro-y col-span-12 xl:col-span-6">
                                        <div class="box">
                                            <div class="flex items-start px-5 pt-5">
                                                <div class="w-full flex flex-col lg:flex-row items-center">
                                                    <div class="section over-hide z-bigger">
                                                        <div class="section over-hide z-bigger">
                                                            <div class="container pb-5">
                                                                <div class="row justify-content-center pb-5">
                                                                    <div class="col-12 pb-5">
                                                                        <input class="checkbox-tools" type="radio"
                                                                            value="ltr"
                                                                            {{ themedirection() == 'ltr' ? 'checked' : null }}
                                                                            name="direction" id="tool-3">
                                                                        <label class="for-checkbox-tools w-full"
                                                                            for="tool-3">
                                                                            <div class="">
                                                                                <div class="h-60 xxl:h-60 image-fit">
                                                                                    <div
                                                                                        class="rounded-md preview-template">
                                                                                        <div style="background-image: url('{{ filePath('layouts/ltr.png') }}');"
                                                                                            class="preview-template">
                                                                                        </div>
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



                                    <div class="intro-y col-span-12 xl:col-span-6">
                                        <div class="box">
                                            <div class="flex items-start px-5 pt-5">
                                                <div class="w-full flex flex-col lg:flex-row items-center">
                                                    <div class="section over-hide z-bigger">
                                                        <div class="section over-hide z-bigger">
                                                            <div class="container pb-5">
                                                                <div class="row justify-content-center pb-5">
                                                                    <div class="col-12 pb-5">
                                                                        <input class="checkbox-tools" type="radio"
                                                                            value="rtl"
                                                                            {{ themedirection() == 'rtl' ? 'checked' : null }}
                                                                            name="direction" id="tool-4">
                                                                        <label class="for-checkbox-tools w-full"
                                                                            for="tool-4">
                                                                            <div class="">
                                                                                <div class="h-60 xxl:h-60">
                                                                                    <div
                                                                                        class="rounded-md preview-template">
                                                                                        <div style="background-image: url('{{ filePath('layouts/rtl.png') }}');"
                                                                                            class="preview-template">
                                                                                        </div>
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

                                </div>
                                {{-- Theme layouts:END --}}

                                {{-- RTL::END --}}



                                {{-- THEME MANAGER --}}

                                <div class="mt-5">
                                    <label>@translate(Select Theme) <small>Default is Argon</small> </label>
                                </div>

                                <div class="grid grid-cols-12 gap-6 mt-5">

                                    <div class="intro-y col-span-12 xl:col-span-6">
                                        <div class="box">
                                            <div class="flex items-start px-5 pt-5">
                                                <div class="w-full flex flex-col lg:flex-row items-center">
                                                    <div class="section over-hide z-bigger">
                                                        <div class="section over-hide z-bigger">
                                                            <div class="container pb-5">
                                                                <div class="row justify-content-center pb-5">
                                                                    <div class="col-12 pb-5">
                                                                        <input class="checkbox-tools" type="radio"
                                                                            value="neon"
                                                                            {{ theme() == 'neon' ? 'checked' : null }}
                                                                            name="theme" id="neon">
                                                                        <label class="for-checkbox-tools w-full"
                                                                            for="neon">
                                                                            <div class="">
                                                                                <div class="h-60 xxl:h-60 image-fit">
                                                                                    <div
                                                                                        class="rounded-md preview-template">
                                                                                        <div style="background-image: url('{{ filePath('themes/neon.png') }}');"
                                                                                            class="preview-template">
                                                                                        </div>
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

                                    <div class="intro-y col-span-12 xl:col-span-6">
                                        <div class="box">
                                            <div class="flex items-start px-5 pt-5">
                                                <div class="w-full flex flex-col lg:flex-row items-center">
                                                    <div class="section over-hide z-bigger">
                                                        <div class="section over-hide z-bigger">
                                                            <div class="container pb-5">
                                                                <div class="row justify-content-center pb-5">
                                                                    <div class="col-12 pb-5">
                                                                        <input class="checkbox-tools" type="radio"
                                                                            value="argon"
                                                                            {{ theme() == 'argon' ? 'checked' : null }}
                                                                            name="theme" id="argon">
                                                                        <label class="for-checkbox-tools w-full"
                                                                            for="argon">
                                                                            <div class="">
                                                                                <div class="h-60 xxl:h-60 image-fit">
                                                                                    <div
                                                                                        class="rounded-md preview-template">
                                                                                        <div style="background-image: url('{{ filePath('themes/argon.png') }}');"
                                                                                            class="preview-template">
                                                                                        </div>
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
                                </div>
                                {{-- THEME MANAGER::ENDS --}}


                                <div class="intro-y col-span-12 xl:col-span-6">
                                    <div class="box">
                                        <div class="flex items-start px-5 pt-5">
                                            <div class="w-full flex flex-col lg:flex-row items-center">
                                                <div class="section over-hide z-bigger">
                                                    <div class="section over-hide z-bigger">
                                                        <div class="container pb-5 flex justify-between">
                                                            {{-- <div class="flex items-center space-x-2 mt-3">
                                                            <input type="checkbox" name="dev_mode"
                                                                class="form-checkbox h-5 w-5 text-indigo-600"
                                                                value="1"
                                                                {{ org('dev_mode') == 1 ? 'checked' : '' }}>
                                                            <label
                                                                class="text-gray-700 font-medium">@translate(Developer Options)</label>
                                                        </div> --}}

                                                            <div class="flex items-center space-x-2 mt-3">
                                                                <input type="checkbox" name="disable_homepage"
                                                                    class="form-checkbox h-5 w-5 text-indigo-600"
                                                                    value="1"
                                                                    {{ env('DISABLE_THEME') == 'YES' ? 'checked' : '' }}>
                                                                <label
                                                                    class="text-gray-700 font-medium">@translate(Disable Homepage)</label>
                                                            </div>

                                                            <div class="mt-3">
                                                                <div class="flex items-center space-x-2">
                                                                    <input type="checkbox" name="disable_contact_form"
                                                                        class="form-checkbox h-5 w-5 text-indigo-600"
                                                                        value="1"
                                                                        {{ env('DISABLE_CONTACT_FORM') == 'YES' ? 'checked' : '' }}>
                                                                    <label
                                                                        class="text-gray-700 font-medium">@translate(Disable Contact Form)</label>
                                                                </div>
                                                            </div>
                                                            <div class="mt-3">
                                                                <div class="flex items-center space-x-2">
                                                                    <input type="checkbox" name="disable_chatgpt"
                                                                        class="form-checkbox h-5 w-5 text-indigo-600"
                                                                        value="1"
                                                                        {{ org('disable_chatgpt') == 1 ? 'checked' : '' }}>
                                                                    <label
                                                                        class="text-gray-700 font-medium">@translate(Disable Chatgpt)</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end mt-4 mb-4">
                            <button type="submit"
                                class="button w-20 bg-theme-1 text-white ml-auto">@translate(Save)</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- END: Application Settings -->
@endsection

@section('script')
    <script src="{{ filePath('assets/js/jquery.js') }}"></script>
    <script src="{{ filePath('assets/js/parsley.js') }}"></script>
    <script src="{{ filePath('assets/js/validation.js') }}"></script>
    <script src="{{ filePath('assets/js/sweetalert2@10.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize Parsley with custom options
            $('form').parsley({
                errorClass: 'parsley-error',
                successClass: 'parsley-success',
                errorsWrapper: '<ul class="parsley-errors-list"></ul>',
                errorTemplate: '<li></li>',
                trigger: 'change',
                errorsContainer: function(field) {
                    return field.$element.parent();
                }
            });

            // Prevent form submission if validation fails
            $('form').on('submit', function(e) {
                var parsleyInstance = $(this).parsley();
                if (!parsleyInstance.isValid()) {
                    e.preventDefault();
                    parsleyInstance.validate();

                    // Scroll to first error
                    var firstError = $('.parsley-error').first();
                    if (firstError.length) {
                        $('html, body').animate({
                            scrollTop: firstError.offset().top - 100
                        }, 500);
                    }
                    return false;
                }
            });
        });
    </script>
@endsection
