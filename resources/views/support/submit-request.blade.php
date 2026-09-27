@extends('../layout/' . layout())

@section('subhead')
    <title>@translate(New Support Ticket)</title>
@endsection

@section('subcontent')

<div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">@translate(New Support Ticket)</h2>
    </div>
    <div class="grid grid-cols-12 gap-12">
        <!-- BEGIN: Profile Menu -->
        <!-- END: Profile Menu -->
        <div class="col-span-12 lg:col-span-12 xxl:col-span-12">
            <!-- BEGIN: Company Information -->
            <div class="intro-y box lg:mt-5">
                <div class="flex items-center p-5 border-b border-gray-200 dark:border-dark-5">
                    <h2 class="font-medium text-base mr-auto">@translate(New Support Ticket)</h2>
                </div>
                <div class="p-5">
                <form action="{{ route('submit.request.submit') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-12 gap-5" id="seo">
                        
                        <div class="col-span-12 xl:col-span-12">

                            <div>
                                <label>@translate(Name)</label>
                                <input type="text" class="input w-full border bg-gray-100 mt-2" placeholder="Enter The Subject" value="{{ Auth::user()->name }}" name="name" data-parsley-required>
                            </div>
                            <div>
                                <label>@translate(Email)</label>
                                <input type="email" class="input w-full border bg-gray-100 mt-2" placeholder="Enter The Email" value="{{ Auth::user()->email }}" name="email" data-parsley-required>
                            </div>
                            <div>
                                <label>@translate(Phone)</label>
                                <input type="number" class="input w-full border bg-gray-100 mt-2" placeholder="Enter The Number" value="{{ Auth::user()->phone }}" name="phone_number" data-parsley-required>
                            </div>
                            <div>
                                <label>@translate(Subject)</label>
                                <input type="text" class="input w-full border bg-gray-100 mt-2" placeholder="Enter The Subject" value="{{ old('subject') }}" name="subject" data-parsley-required>
                            </div>
                      

                            <div class="mt-3">
                                <label>@translate(Write your issue in details.)</label>
                                <div class="mt-2">
                                    <textarea rows="8" data-simple-toolbar="true" class="editor resize-none border rounded-md w-full" name="desc" data-parsley-required>{{ old('desc') }}</textarea>
                                </div>
                            </div>
                          
                        </div>
                </div>

                <div class="flex justify-start mt-4">
                            <button type="submit" class="button w-20 bg-theme-1 text-white ml-auto">@translate(Save)</button>
                        </div>
            </form>
                </div>
            </div>
            <!-- END: Company Information -->
           
        </div>
    </div>

@endsection

@section('script')
 
@endsection