@extends('../layout/' . layout())

@section('subhead')
    <title>@translate(Write New Page)</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ filePath('assets/css/dropify.css') }}">
@endsection


@section('subcontent')
    <div class="flex items-center mt-8">
        <h2 class="intro-y text-lg font-medium mr-auto">@translate(Write New Page)</h2>
    </div>
    <!-- BEGIN: Wizard Layout -->
    <div class="intro-y box mt-5">

        <div class="px-5 sm:px-20 mt-10 py-10 border-t border-gray-200 dark:border-dark-5">
            <div class="grid grid-cols-12 gap-6 mt-5 items-center">
                <div class="intro-y col-span-12 md:col-span-12 lg:col-span-12">
                    <div class="box">
                        <div class="text-left lg:text-left p-5">
                            <h2 class="font-bold text-lg">Subject: {{ $contact->subject }}</h2>
                            <div>{{ $contact->message }}</div>
                            <div class="flex items-center justify-center lg:justify-start text-slate-500 mt-5">
                                <x-feathericon-mail class="w-5 h-5 mr-5"/>{{ $contact->email }}
                            </div>
                            <div class="flex items-center justify-center lg:justify-start text-slate-500 mt-1">
                                <x-feathericon-user class="w-5 h-5 mr-5"/>{{ $contact->full_name }}
                            </div>
                            <div class="flex items-center justify-center lg:justify-start text-slate-500 mt-1">
                                <x-feathericon-clock class="w-5 h-5 mr-5"/>{{ $contact->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-12 gap-6 mt-5 items-center">
                <div class="intro-y col-span-12 md:col-span-12 lg:col-span-12">
                    <div class="box">
                        <div class="text-left lg:text-left p-5">
                            <h2 class="font-bold text-lg">Reply</h2>
                            <div>{!! $contact->reply !!}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BEGIN: Form Layout -->
            @if(Route::currentRouteName() != 'contact.show')
            <form action="{{ route('contact.replay.sent', $contact->id) }}" method="GET">
                @csrf
                <div class="intro-y box p-5">
                    <div class="mt-3">
                        <label>@translate(Description)</label>
                        <div class="mt-2">
                            <textarea class="editor" name="body"></textarea>
                        </div>
                    </div>
                    <div class="text-right mt-5">
                        <button type="submit" class="button w-50 bg-theme-1 text-white">@translate(Submit)</button>
                    </div>
                </div>
            </form>
            @endif
            <!-- END: Form Layout -->
        </div>
    </div>
    <!-- END: Wizard Layout -->
@endsection

@section('script')
    <script src="{{ filePath('assets/js/jquery.js') }}"></script>
    <script src="{{ filePath('assets/js/parsley.js') }}"></script>
    <script src="{{ filePath('assets/js/validation.js') }}"></script>
    <script src="{{ filePath('assets/js/dropify.js') }}"></script>
    <script src="{{ filePath('assets/js/sweetalert2@10.js') }}"></script>
    <script src="{{ filePath('bladejs/dropify.js') }}"></script>



    <script>
        //    this is dynamic script, error message receiving from laravel query

        @if ($errors->any())
            Swal.fire(
                '',
                @foreach ($errors->all() as $error)
                    "{{ $error }}",
                @endforeach
            )
        @endif
    </script>
@endsection
