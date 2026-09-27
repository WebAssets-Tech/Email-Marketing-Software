@extends('../layout/' . layout())

{{-- @section('css')
@alpinejs
@endsection --}}
@section('subhead')
    <title>@translate(Mailgun Domains)</title>
@endsection

@section('subcontent')
    <h2 class="intro-y text-lg font-medium mt-10">@translate(Mail Servers)</h2>
    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y col-span-12 block sm:flex  items-center mt-2">

            @can('Admin')
                <a href="javascript:;" data-toggle="modal" data-target="#superlarge-modal-size-preview-smtp"
                    class="button button--lg flex items-center justify-center bg-theme-4 text-white mt-2">
                    <i class="w-4 h-4 mr-2" data-feather="edit-3"></i> @translate(Add New Domain)
                </a>
            @endcan
        </div>

    </div>

    <div class="grid mt-5">
        <div class="intro-y col-span-12 overflow-auto">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-no-wrap">ICON</th>
                        <th class="whitespace-no-wrap">NAME</th>
                        <th class="text-center whitespace-no-wrap">STATUS</th>
                        <th class="text-center whitespace-no-wrap">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="campaignEmailName">
                    @forelse ($domains as $domain)
                        <tr>
                            <td>
                                @if ($domain->state == 'unverified')
                                    <i class="w-5 h-5 text-red-500 font-bold" data-feather="x-circle"></i>
                                @else
                                    <i class="w-5 h-5 text-green-500 font-bold" data-feather="check-circle"></i>
                                @endif
                            </td>
                            <td class="font-bold">
                                {{ $domain->name }}
                            </td>
                            <td class="text-center">
                                @if ($domain->state == 'unverified')
                                    <span
                                        class="text-red-600 bg-red-200 p-2 rounded-full capitalize text-md">{{ $domain->state }}</span>
                                @else
                                    <span
                                        class="text-green-600 bg-green-200 p-2 rounded-full capitalize text-md">{{ $domain->state }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($domain->state == 'unverified')
                                    <a href="{{ route('mailgun.domain.show', $domain->name) }}"
                                        class="bg-theme-1 p-3   max-h-min rounded-md text-white font-medium">
                                        Verify DNS Records
                                    </a>
                                @else
                                <span
                                        class="text-green-600 font-bold bg-green-200 p-2 rounded-full capitalize text-md">Verified</span>
                                    {{-- <a href="{{ route('mailgun.domain.destroy', $domain->name) }}">
                                        Delete
                                    </a> --}}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="text-center">
                                    <img src="{{ filePath('not_found/emailList.png') }}" class="m-auto no-shadow"
                                        alt="#campaign-not-found">
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>



    {{-- MODAL --}}
    <div class="modal" id="superlarge-modal-size-preview-smtp">
        <div class="modal__content modal__content--xl p-10">
            <div class="intro-y items-center mt-8">
                <h2 class="text-lg font-medium mr-auto">@translate(Add New Domain)</h2>

                <form action="{{route('mailgun.domain.store')}}" class="mt-6" method="post">
                    @csrf
                    <div class="input-form mb-2">
                        <label class="flex flex-col sm:flex-row" for="domain_name">
                            @translate(Domain Name)*</label>
                        <input type="text" name="domain_name"
                            class="input w-64 sm:w-full border mt-2" id="domain_name"
                            placeholder="example.com" data-parsley-type="text" required>
                    </div>

                    <div class="text-end">
                        <button type="submit"
                            class="button text-white bg-theme-1 mr-2">@translate(Next)</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- MODAL::END --}}
@endsection

@section('script')
@endsection
