@extends('../layout/' . layout())

@section('subhead')
    <title>
        @translate(Contacts)</title>
@endsection

@section('subcontent')
    <h2 class="intro-y text-lg font-medium mt-10">@translate(Contacts)</h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-no-wrap items-center mt-2">
            <a href="" class="button text-white bg-theme-1 shadow-md mr-2 w-4/12 tooltip"
                title="@translate(Add new contact)">@translate(Go to Campaign)</a>

            <div class="w-full sm:w-auto ml-2 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="text-right relative text-gray-700 dark:text-gray-300">
                    <input type="text" class="input w-full md:w-56  mt-4 md:mt-0 box pr-10 placeholder-theme-13"
                        placeholder="Search..." id="noteIndex">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i>
                </div>
            </div>

        </div>
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto ">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-no-wrap">@translate(ICON)</th>
                        <th class="whitespace-no-wrap">@translate(EMAIL)</th>
                        <th class="text-center whitespace-no-wrap">@translate(MESSAGE)</th>
                        <th class="text-center whitespace-no-wrap">@translate(CREATED AT)</th>
                        <th class="text-center whitespace-no-wrap">@translate(ACTIONS)</th>
                    </tr>
                </thead>
                <tbody class="noteName">
                    @forelse ($contacts as $contact)
                        <tr class="intro-x">
                            <td class="">
                                <div class="flex">
                                    <div class="w-10 h-10 image-fit zoom-in">
                                        <img alt="{{ $contact->full_name }}" class="tooltip rounded-full"
                                            src="{{ namevatar($contact->full_name) }}" title="{{ $contact->full_name }}">
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="javascript:;" class="font-medium tooltip inline-block"
                                    title="{{ $contact->email }}">{{ $contact->email }}
                                </a>
                            </td>

                            <td class="text-center">
                                <span class="text-center">{{ $contact->subject }}</span>
                            </td>

                            <td class="text-center">{{ $contact->created_at->diffForHumans() }}</td>

                            <td class="table-report__action ">
                                <div class="flex justify-center items-center">

									<a class="flex items-center mr-3 tooltip" title="@translate(Reply)" target="_blank" href="{{ route('contact.replay', $contact->id) }}">
										<i data-feather="mail" class="w-4 h-4 mr-1"></i>
									</a>
									<a class="flex items-center mr-3 tooltip" title="@translate(View)" target="_blank" href="{{ route('contact.show', $contact->id) }}">
										<i data-feather="eye" class="w-4 h-4 mr-1"></i>
									</a>
									<button onclick="document.getElementById('delete-form-' + '{{ $contact->id }}').submit();" class="flex items-center text-theme-6 tooltip" type="submit" title="@translate(Delete)">
										<i data-feather="trash-2" class="w-4 h-4 mr-1"></i>
									</button>
									<form id="delete-form-{{ $contact->id }}" action="{{ route('contact.destroy', $contact->id) }}" method="POST" style="display: none;">
										@csrf()
										@method('DELETE')
									</form>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <td colspan="6">
                            <div class="text-center">
                                <img src="{{ notFound('note.png') }}" class="w-2/5 m-auto no-shadow" alt="#note-not-found">
                            </div>
                        </td>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="intro-y col-span-12 text-center">
            <div class="md:block mx-auto text-gray-600">{{ __('Showing') }} {{ $contacts->firstItem() ?? '0' }}
                {{ __('to') }} {{ $contacts->lastItem() ?? '0' }} {{ __('of') }} {{ $contacts->total() }}
                {{ __('entries') }}</div>
        </div>
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->
        {{ $contacts->links('vendor.pagination.custom') }}
        <!-- END: Pagination -->
    </div>
@endsection

@section('script')
    <script src="{{ filePath('bladejs/notes/index.js') }}"></script>
@endsection
