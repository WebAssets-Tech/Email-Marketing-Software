@extends('../layout/' . layout())

@section('subhead')
    <title>@translate(Chat GPT)</title>
@endsection

@section('subcontent')
    <div class="content">
        <div class="flex h-screen antialiased text-gray-800">
            <div class="flex flex-row h-full w-full overflow-x-hidden">
                <div class="flex flex-col py-8 pl-6 pr-2 w-64 bg-white flex-shrink-0">
                    <div class="flex flex-row items-center justify-center h-12 w-full">
                        <div class="flex items-center justify-center rounded-2xl text-indigo-700 bg-indigo-100 h-10 w-10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-2 font-bold text-2xl">Conversations</div>
                    </div>

                    <div class="flex flex-col mt-8">
                        <div class="flex flex-row items-center justify-between text-xs">
                            <span class="font-bold">Recent Conversations</span>
                        </div>
                        <div class="flex flex-col space-y-1 mt-4 -mx-2 h-48 overflow-y-auto">
                            @foreach (chatgptRecent() as $item)
                                <a href="{{ route('chat.gpt.single', $item->parent_id) }}" class="flex flex-row items-center hover:bg-gray-100 rounded-xl p-2">
                                    <div class="flex items-center justify-center h-8 w-8 bg-orange-200 rounded-full">P</div>
                                    <div class="ml-2 text-sm font-semibold">{{ substr($item->question, 0,  15)  }}</div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="flex flex-col flex-auto h-full p-6">
                    <div class="flex flex-col flex-auto flex-shrink-0 rounded-2xl bg-gray-100 h-fit p-4">
                        <div class="flex flex-col h-64 overflow-x-auto mb-4">
                            <div class="flex items-center justify-center h-screen">
                            <h2 class="text-center text-3xl">ChatGPT Waiting</h2>
                            </div>

                        </div>
                        
                        <form action="{{ route('chat.gpt.chat.store') }}" method="POST">
                            @csrf
                            <div class="flex flex-row items-center h-16 rounded-xl bg-white w-full px-4">

                                <div>
                                    <button class="flex items-center justify-center text-gray-400 hover:text-gray-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="flex-grow ml-4">
                                    <div class="relative w-full">
                                        <input type="text" name="prompt"
                                            id="prompt"
                                            class="flex w-full border rounded-xl focus:outline-none focus:border-indigo-300 pl-4 h-10"
                                            placeholder="Enter Your Replies" />
                                        <button
                                            class="absolute flex items-center justify-center h-full w-12 right-0 top-0 text-gray-400 hover:text-gray-600">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <button type="submit"
                                        class="flex items-center justify-center bg-indigo-500 hover:bg-indigo-600 rounded-xl text-white px-4 py-1 flex-shrink-0">
                                        <span>Send</span>
                                        <span class="ml-2">
                                            <svg class="w-4 h-4 transform rotate-45 -mt-px" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ filePath('bladejs/notes/index.js') }}"></script>

@endsection
