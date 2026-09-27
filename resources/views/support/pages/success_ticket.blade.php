@extends('../layout/' . layout())

@section('subhead')
<title>@translate(Support Tickets)</title>
@endsection

@section('subcontent')
	<h2 class="intro-y text-lg font-medium mt-10">@translate(Support Ticket)</h2>
	<div class="grid mt-5">
      <h1 class="text-5xl text-center">Successfully Created Support ticket</h1>
	</div>
@endsection

@section('script')
	<script src="{{ filePath('bladejs/notes/index.js') }}"></script>
@endsection
