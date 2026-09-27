@extends('../layout/' . layout())

{{-- @section('css')
@alpinejs
@endsection --}}
@section('subhead')
    <title>@translate(Mailgun Domains)</title>
@endsection

@section('subcontent')
    <div>
        <div class="flex justify-between items-center mt-10 mb-4">
            <h2 class="intro-y text-2xl font-medium">
                @translate(Follow these steps to verify your domain)
            </h2>
            <a href="{{route('mailgun.domain.verify', $domain->domain->name)}}" class="bg-theme-1 p-3   max-h-min rounded-md text-white font-medium">Verify DNS Records</a>
        </div>
    
        <div class="relative mb-8">
            <div class="bg-white rounded-md p-4">
                <h1 class="text-xl">Sending records
                </h1>
                <div class="text-sm">
                    TXT records (known as SPF & DKIM) are required to send and receive email with Mailgun.
                </div>
                <hr class="absolute bg-slate-300 mt-4 mb-8 h-1 w-full left-0">
    
                @foreach ($domain->sending_dns_records as $record)
                    @if ($record->record_type == 'TXT')
                        <div class="mt-10">
                            <h3 class="text-lg font-medium mb-2">
                                @if (str($record->value)->contains('v=spf'))
                                    SPF
                                @else
                                    DKIM
                                @endif
                            </h3>
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th class="p-2 border text-sm">Status</th>
                                        <th class="p-2 border text-sm">Hostname</th>
                                        <th class="p-2 border text-sm">Enter this value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="">
                                        <td class="p-4 border-l border-t border-b text-sm text-center">
                                            @if ($record->valid != 'valid')
                                                <span
                                                    class="text-balck bg-rose-400 px-2 py-1 rounded-full capitalize text-sm">{{ $record->valid == 'valid' ? 'Verified' : 'Unverified' }}</span>
                                            @else
                                                <span
                                                    class="text-black bg-emerald-400 px-2 py-1 rounded-full capitalize text-sm">{{ $record->valid == 'valid' ? 'Verified' : 'Unverified' }}</span>
                                            @endif
                                        </td>
                                        <td class="p-4 border border-r-0 text-sm">
                                            <div class="flex gap-4">
                                                <span role="button" class="w-4 h-4 text-slate-400 font-bold copy-btn tooltip"
                                                    title="@translate(Click to copy)" data-feather="copy"
                                                    data-value="{{ $record->name }}"></span>
                                                {{ $record->name }}
                                            </div>
                                        </td>
                                        <td class="p-4 border border-r-0 text-sm">
                                            <span role="button" class="inline w-4 h-4 mr-4 text-slate-400 font-bold copy-btn tooltip"
                                                title="@translate(Click to copy)" data-feather="copy"
                                                data-value="{{ $record->value }}"></span>
                                            <div class="inline" style="inline-size: 250px; overflow-wrap:break-all;">
                                                {{ $record->value }}
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        <div class="relative mb-8">
            <div class="bg-white rounded-md p-4">
                <h1 class="text-xl">Receiving records
                </h1>
                <div class="text-sm">
                    MX records are recommended for all domains, even if you are only sending messages. Unless you already have
                    MX records for {{ $domain->domain->name }} pointing to another email provider (e.g. Gmail), you should update the
                    following records
                </div>
                <hr class="absolute bg-slate-300 mt-4 mb-8 h-1 w-full left-0">
    
                <div class="mt-10">
                    <h3 class="text-lg font-medium mb-2">
                        MX
                    </h3>
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="p-2 border text-sm">Status</th>
                                <th class="p-2 border text-sm">Hostname</th>
                                <th class="p-2 border text-sm">Priority</th>
                                <th class="p-2 border text-sm">Enter this value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($domain->receiving_dns_records as $record)
                                <tr class="">
                                    <td class="p-4 border-l border-t border-b text-sm text-center">
                                        @if ($record->valid != 'valid')
                                            <span
                                                class="text-balck bg-rose-400 px-2 py-1 rounded-full capitalize text-sm">{{ $record->valid == 'valid' ? 'Verified' : 'Unverified' }}</span>
                                        @else
                                            <span
                                                class="text-black bg-emerald-400 px-2 py-1 rounded-full capitalize text-sm">{{ $record->valid == 'valid' ? 'Verified' : 'Unverified' }}</span>
                                        @endif
                                    </td>
                                    <td class="p-4 border border-r-0 text-sm">
                                        <div class="flex gap-4">
                                            <span role="button" class="w-4 h-4 text-slate-400 font-bold copy-btn tooltip"
                                                title="@translate(Click to copy)" data-feather="copy"
                                                data-value="{{ $domain->domain->name }}"></span>
                                            {{ $domain->domain->name }}
                                        </div>
                                    </td>
                                    <td class="p-4 border border-r-0 text-sm">
                                        <div class="flex gap-4">
                                            <span role="button" class="w-4 h-4 text-slate-400 font-bold copy-btn tooltip"
                                                title="@translate(Click to copy)" data-feather="copy"
                                                data-value="{{ $record->priority }}"></span>
                                            {{ $record->priority }}
                                        </div>
                                    </td>
                                    <td class="p-4 border border-r-0 text-sm">
                                        <span role="button" class="inline w-4 h-4 mr-4 text-slate-400 font-bold copy-btn tooltip"
                                                title="@translate(Click to copy)" data-feather="copy"
                                                data-value="{{ $record->value }}"></span>
                                            <div class="inline" style="inline-size: 250px; overflow-wrap:break-all;">
                                                {{ $record->value }}
                                            </div>
                                    </td>
                                </tr>
                            @endforeach
    
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="relative mb-8">
            <div class="bg-white rounded-md p-4">
                <h1 class="text-xl">Tracking records
                </h1>
                <div class="text-sm">
                    The CNAME record is necessary for tracking opens, clicks, and unsubscribes.
                </div>
                <hr class="absolute bg-slate-300 mt-4 mb-8 h-1 w-full left-0">
    
                <div class="mt-10">
                    <h3 class="text-lg font-medium mb-2">
                        CNAME
                    </h3>
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="p-2 border text-sm">Status</th>
                                <th class="p-2 border text-sm">Hostname</th>
                                <th class="p-2 border text-sm">Enter this value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($domain->sending_dns_records as $record)
                                @if ($record->record_type == 'CNAME')
                                    <tr class="">
                                        <td class="p-4 border-l border-t border-b text-sm text-center">
                                            @if ($record->valid != 'valid')
                                                <span
                                                    class="text-balck bg-rose-400 px-2 py-1 rounded-full capitalize text-sm">{{ $record->valid == 'valid' ? 'Verified' : 'Unverified' }}</span>
                                            @else
                                                <span
                                                    class="text-black bg-emerald-400 px-2 py-1 rounded-full capitalize text-sm">{{ $record->valid == 'valid' ? 'Verified' : 'Unverified' }}</span>
                                            @endif
                                        </td>
                                        <td class="p-4 border border-r-0 text-sm">
                                            <div class="flex gap-4">
                                                <span role="button" class="w-4 h-4 text-slate-400 font-bold copy-btn tooltip"
                                                    title="@translate(Click to copy)" data-feather="copy"
                                                    data-value="{{ $record->name }}"></span>
                                                {{ $record->name }}
                                            </div>
                                        </td>
                                        <td class="p-4 border border-r-0 text-sm">
                                            <span role="button" class="inline w-4 h-4 mr-4 text-slate-400 font-bold copy-btn tooltip"
                                                title="@translate(Click to copy)" data-feather="copy"
                                                data-value="{{ $record->value }}"></span>
                                            <div class="inline" style="inline-size: 250px; overflow-wrap:break-all;">
                                                {{ $record->value }}
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    <div class="text-slate-500 mt-4 font-medium">
                        Once you make the above DNS changes it can take 24-48hrs for those changes to propagate
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            let btns = $('.copy-btn')
            btns.each((i, btn) => {
                btn = $(btn)
                btn.click(function(e) {
                    console.log('hello')
                    navigator.clipboard.writeText(btn.data('value'));
                    btn.attr('title', 'Copied')
                })

            });
        });
    </script>
@endsection
