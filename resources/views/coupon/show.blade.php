@extends('../layout/' . layout())

@section('subhead')

@endsection

@section('subcontent')

<div class="flex items-center mt-8">
    <h2 class="intro-y text-lg font-medium mr-auto">@translate(Editing) {{ $coupon->code }}</h2>
</div>

<div class="intro-y box py-10 sm:py-20 mt-5">
    <div class="wizard flex lg:flex-row justify-center px-5 sm:px-20">
        <div class="grid grid-cols-12 gap-12 mt-5">
            <div class="intro-y col-span-12 lg:col-span-12">
                <!-- BEGIN: Form Layout -->

                <form class="" 
                    enctype="multipart/form-data"
                    action="{{ route('coupon.update', $coupon->id) }}"
                    method="POST">
                    @csrf

                    <div class="mt-3">
                        <div class="input-form"> 
                            <label class="flex flex-col sm:flex-row"> 
                                @translate(Coupon Code)
                            </label> 
                            <input type="text" 
                                    name="code"
                                    value="{{ $coupon->code }}"
                                    class="input w-full border mt-2" 
                                    placeholder="@translate(Coupon Code)" 
                                    data-parsley-required>
                                    <small>Ex: CODE25</small>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="input-form"> 
                            <label class="flex flex-col sm:flex-row"> 
                                @translate(Coupon Starting Date & Time)
                            </label> 
                            <input class="datepicker input w-56 border" name="start_date" value="{{ Carbon\Carbon::parse($coupon->date_from)->format('d M, Y') }}" data-single-mode="true" data-parsley-required>
                            <input class="input w-56 border" type="time" value="{{ Carbon\Carbon::parse($coupon->date_from)->format('h:i:s') }}" name="start_time" data-parsley-required>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="input-form"> 
                            <label class="flex flex-col sm:flex-row"> 
                                @translate(Coupon Ending Date & Time)
                            </label> 
                            <input class="datepicker input w-56 border" name="end_date" value="{{ Carbon\Carbon::parse($coupon->date_to)->format('d M, Y') }}" data-single-mode="true" data-parsley-required>
                            <input class="input w-56 border" type="time" name="end_time" value="{{ Carbon\Carbon::parse($coupon->date_to)->format('h:i:s') }}" data-parsley-required>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="input-form"> 
                            <label class="flex flex-col sm:flex-row"> 
                                @translate(Discount Type)
                            </label> 
                            <select data-search="false" 
                                    name="type" 
                                    id="" 
                                    class="w-full form-select sm:w-1/2" 
                                    data-parsley-required>
                                <option value="percentage" {{ $coupon->type == 'percentage' ? 'selected' : null }}>@translate(Percentage)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="input-form"> 
                            <label class="flex flex-col sm:flex-row"> 
                                @translate(Rate)
                            </label> 
                            <input type="number" 
                                    name="discount_amount" 
                                    class="input w-full border mt-2" 
                                    value="{{ $coupon->discount_amount }}"
                                    placeholder="@translate(Discount Amount)" 
                                    data-parsley-required>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="input-form"> 
                            <label class="flex flex-col sm:flex-row"> 
                                @translate(Minimum Purchase) <small>(@translate(optional))</small>
                            </label> 
                            <input type="number" 
                                    name="minimum_purchase" 
                                    class="input w-full border mt-2" 
                                    value="{{ $coupon->minimum_purchase }}"
                                    placeholder="@translate(Minimum Purchase)">
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="input-form"> 
                            <label class="flex flex-col sm:flex-row"> 
                                @translate(Maximum Usage Validity) <small>(@translate(optional))</small>
                            </label> 
                            <input type="number" 
                                    name="maximum_usage" 
                                    class="input w-full border mt-2" 
                                    value="{{ $coupon->maximum_usage }}"
                                    placeholder="@translate(Maximum Usage Validity)">
                                    <small>Note: The coupon will application limited use only.</small>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label>Active Status</label>
                        <div class="mt-2">
                            <input type="checkbox" value="1" class="input input--switch border" name="status" {{ $coupon->status == 1 ? 'checked' : null }}>
                        </div>
                    </div>

                    <button type="submit"
                            class="button bg-theme-1 text-white mt-5">
                            @translate(Update Coupon)
                    </button>
                </form>
                <!-- END: Form Layout -->
        
            </div>
        </div>
    </div>
</div>


@endsection

@section('script')
 
@endsection