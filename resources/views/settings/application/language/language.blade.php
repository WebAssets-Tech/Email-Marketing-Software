@extends('../layout/side-menu')

@section('subhead')
    <title>@translate(Language Settings)</title>
@endsection
@push('styles')
    <style>
        .intro-y:nth-child(2) {
            z-index: 0 !important;
        }
    </style>
@endpush

@section('subcontent')
    @include('settings.application.language.components.lang_list')
@endsection

@section('script')
@endsection
