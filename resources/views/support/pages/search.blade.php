@extends('../layout/' . layout())

@section('subhead')
    <title>@translate(Support Tickets)</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6 mt-8">
        <div class="col-span-12 lg:col-span-3 2xl:col-span-2">
            <h2 class="intro-y text-lg font-medium mr-auto mt-2">
                @translate(Support Tickets)
            </h2>

            <!-- BEGIN: Inbox Menu -->
            <div class="intro-y box bg-theme-1 p-5 mt-6">
                <a href="{{ route('submit.request') }}"
                    class="button button--lg flex items-center justify-center text-gray-700 dark:text-gray-300 w-full bg-white dark:bg-theme-1 mt-2">@translate(new Ticket())
                </a>
                <div class="border-t border-theme-3 dark:border-dark-5 mt-6 pt-6 text-white">
                    <a href="{{ route('support.ticket.new') }}"
                        class="flex items-center px-3 py-2 rounded-md bg-theme-20 dark:bg-dark-1 font-medium">
                        <i class="w-4 h-4 mr-2" data-feather="mail"></i> @translate(Inbox) </a>
                    <a href="{{ route('support.ticket.sent.reply') }}" class="flex items-center px-3 py-2 mt-2 rounded-md">
                        <i class="w-4 h-4 mr-2" data-feather="star"></i> @translate(Sent) </a>
                    <a href="{{ route('support.ticket.sent.starred') }}"
                        class="flex items-center px-3 py-2 mt-2 rounded-md"> <i class="w-4 h-4 mr-2"
                            data-feather="inbox"></i> @translate(Starred) </a>
                    <a href="{{ route('support.ticket.unread') }}" class="flex items-center px-3 py-2 mt-2 rounded-md"> <i
                            class="w-4 h-4 mr-2" data-feather="send"></i> @translate(Unread) </a>
                    <a href="{{ route('ticket.solved') }}" class="flex items-center px-3 py-2 mt-2 rounded-md"> <i
                            class="w-4 h-4 mr-2" data-feather="trash"></i> @translate(Solved) </a>
                </div>
            </div>
            <!-- END: Inbox Menu -->
        </div>

        <div class="col-span-12 lg:col-span-9 2xl:col-span-10">
            <!-- BEGIN: Inbox Filter -->
            <div class="intro-y flex flex-col-reverse sm:flex-row items-center">
                <div class="w-full sm:w-auto relative mr-auto mt-3 sm:mt-0">
                    <form action="{{ route('support.ticket.search') }}" method="get">
                        <i class="w-4 h-4 absolute my-auto inset-y-0 ml-3 left-0 z-10 text-gray-700 dark:text-gray-300"
                            data-feather="search"></i>
                        <input type="text" name="ticket_no"
                            class=" input w-full sm:w-64 box px-10 text-gray-700 dark:text-gray-300 placeholder-theme-13"
                            placeholder="Search Ticket">
                        <input type="hidden" type="submit">
                    </form>
                </div>
            </div>
            <!-- END: Inbox Filter -->
            <!-- BEGIN: Inbox Content -->
            <div class="intro-y inbox box mt-5">
                <div class="overflow-x-auto sm:overflow-x-visible">
                    <div class="intro-y">
                        <div class="bg-white p-8 rounded-md w-full">
                            <div class=" flex items-center justify-between pb-6">
                                <div>
                                    <h2 class="text-gray-600 font-semibold">Ticket</h2>
                                    <span class="text-xs">All Support Ticket</span>
                                </div>
                                <div class="flex items-center justify-between">

                                    <div class="lg:ml-40 ml-10 space-x-8">
                                        <a href="{{ route('submit.request') }}"
                                            class="bg-indigo-600 px-4 py-2 rounded-md text-white font-semibold tracking-wide cursor-pointer">New
                                            Ticket</a>
                                        <a href="{{ route('campaign.create') }}"
                                            class="bg-indigo-600 px-4 py-2 rounded-md text-white font-semibold tracking-wide cursor-pointer">Campaign
                                            Create</a>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
                                    <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
                                        <table class="min-w-full leading-normal">
                                            <thead>
                                                <tr>
                                                    <th
                                                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                        Ticket No.
                                                    </th>
                                                    <th
                                                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                        Name
                                                    </th>
                                                    <th
                                                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                        Type
                                                    </th>
                                                    <th
                                                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                        Created at
                                                    </th>
                                                    <th
                                                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                        Status
                                                    </th>
                                                    <th
                                                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (Auth::user()->user_type == 'Admin')
                                                    @foreach ($user_tickets as $ticket)
                                                        <tr>

                                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                                <p class="text-gray-900 whitespace-no-wrap">
                                                                    {{ $ticket->ticket_no }}</p>
                                                            </td>

                                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                                <div class="flex items-center">
                                                                    <div class="flex-shrink-0 w-10 h-10">
                                                                        <img class="w-full h-full rounded-full"
                                                                            src="{{ filePath('/') . $ticket->user->photo }}"
                                                                            alt="" />
                                                                    </div>
                                                                    <div class="ml-3">
                                                                        <p class="text-gray-900 whitespace-no-wrap">
                                                                            {{ $ticket->user->name }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                                <p class="text-gray-900 whitespace-no-wrap">
                                                                    {{ $ticket->user->user_type }}</p>
                                                            </td>
                                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                                <p class="text-gray-900 whitespace-no-wrap">
                                                                    {{ $ticket->created_at->diffForHumans() }}
                                                                </p>
                                                            </td>
                                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                                <span
                                                                    class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                                                    <span aria-hidden
                                                                        class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                                                    <span class="relative">Active</span>
                                                                </span>
                                                            </td>
                                                            <td
                                                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm flex w-full flex-wrap">
                                                                <a class="pr-2"
                                                                    href="{{ route('ticket.reply', $ticket->ticket_no) }}">
                                                                    <x-feathericon-message-square />
                                                                </a>
                                                                <form
                                                                    action="{{ route('support.ticket.mark.star', $ticket->ticket_no) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <button type="submit">
                                                                        @if ($ticket->important == true)
                                                                            <x-feathericon-star class="text-blue-300" />
                                                                        @else
                                                                            <x-feathericon-star />
                                                                        @endif
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    @foreach ($user_tickets_spesific_user as $ticket)
                                                        {{-- <a href="{{ route('ticket.reply', $ticket->ticket_no) }}"> --}}
                                                        <tr>
                                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                                <div class="flex items-center">
                                                                    <div class="flex-shrink-0 w-10 h-10">
                                                                        <img class="w-full h-full rounded-full"
                                                                            src="{{ $ticket->user->photo }}"
                                                                            alt="" />
                                                                    </div>
                                                                    <div class="ml-3">
                                                                        <p class="text-gray-900 whitespace-no-wrap">
                                                                            {{ $ticket->user->name }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                                <p class="text-gray-900 whitespace-no-wrap">
                                                                    {{ $ticket->user->user_type }}</p>
                                                            </td>
                                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                                <p class="text-gray-900 whitespace-no-wrap">
                                                                    {{ $ticket->created_at->diffForHumans() }}
                                                                </p>
                                                            </td>
                                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                                <span
                                                                    class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                                                    <span aria-hidden
                                                                        class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                                                    <span class="relative">Active</span>
                                                                </span>
                                                            </td>
                                                            <td
                                                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm flex w-full flex-wrap">
                                                                <a class="pr-2"
                                                                    href="{{ route('ticket.reply', $ticket->ticket_no) }}">
                                                                    <x-feathericon-message-square />
                                                                </a>
                                                                <form
                                                                    action="{{ route('support.ticket.mark.star', $ticket->ticket_no) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <button type="submit">
                                                                        @if ($ticket->important == true)
                                                                            <x-feathericon-star class="text-blue-300" />
                                                                        @else
                                                                            <x-feathericon-star />
                                                                        @endif
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="p-5 flex flex-col sm:flex-row items-center text-center sm:text-left text-gray-600">
                    <div class="dark:text-gray-300">4.41 GB (25%) of 17 GB used Manage</div>
                    <div class="sm:ml-auto mt-2 sm:mt-0 dark:text-gray-300">Last account activity: 36 minutes ago</div>
                </div>
            </div>
            <!-- END: Inbox Content -->
        </div>

    </div>
@endsection

@section('script')
@endsection
