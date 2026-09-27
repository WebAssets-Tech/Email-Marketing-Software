@extends('layout.' . layout())

@section('subhead')
<title>@translate(Bounce Checker)</title>
@endsection

@section('subcontent')
<div class="flex items-center mt-8">
    <h2 class="intro-y text-lg font-medium mr-auto">@translate(Bounce Checker)</h2>
</div>
<!-- BEGIN: Wizard Layout -->
<div class="intro-y box mt-5">

    <div class="px-5 sm:px-20 mt-10 p-10 border-t border-gray-200 dark:border-dark-5">
        <!-- BEGIN: Form Layout -->
        <div class="intro-y box p-5 block sm:flex items-center">
            <div>
                <label>@translate(Email Address)</label>
                <input type="email" class="input w-full border mt-2 mr-3" name="email" placeholder="Email Address" required id="emailAddress" data-parsley-required data-parsley-type="email">
            </div>


            <div class="block ml-0 sm:ml-3 mt-3 sm:mt-8">
                <button type="button" onclick="checkBounce()" class="button block w-full bg-theme-1 text-white">@translate(Check)</button>
            </div>

        </div>
        <!-- END: Form Layout -->
    </div>
</div>
<!-- END: Wizard Layout -->


<!-- BEGIN: Wizard Layout -->
<div class="intro-y box mt-5">

    <div class="px-5 sm:px-20 mt-10 py-10 border-t border-gray-200 dark:border-dark-5 hidden" id="resultOfBoundBox">
        <!-- BEGIN: Form Layout -->
        <div class="intro-y box p-5">

            <div id="checkResult"></div>


        </div>
        <!-- END: Form Layout -->
    </div>
</div>
<!-- END: Wizard Layout -->

{{-- Loader --}}
<div class="loading hidden"></div>
{{-- Loader::end --}}

<input type="hidden" value="{{ route('bounce.checker') }}" id="bounce_checker_url">

@endsection

@section('script')
<script src="{{ filePath('assets/js/email_contacts.js') }}"></script>

<script src="{{ filePath('assets/js/jquery.js') }}"></script>
<script src="{{ filePath('assets/js/parsley.js') }}"></script>
<script src="{{ filePath('assets/js/validation.js') }}"></script>

@endsection