@extends('../layout/' . layout())

@section('subhead')
<title>@translate(Coupon Management)</title>
@endsection

@section('subcontent')
<h2 class="intro-y text-lg font-medium mt-10">@translate(Coupon Management)</h2>




<div class="grid">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-no-wrap items-center mt-2">
        <a href="javascript:;" data-toggle="modal" data-target="#superlarge-currency-modal-size-preview" class="button w-80 sm:w-4/12 text-white bg-theme-1 shadow-md mr-2 tooltip" title="@translate(Add New Coupon)">
            @translate(Add New Coupon)
        </a>
    </div>
    <!-- BEGIN: Data List -->
    <div class="intro-y col-span-12 overflow-auto">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-no-wrap">@translate(SL.)</th>
                    <th class="text-center whitespace-no-wrap">@translate(CODE)</th>
                    <th class="text-center whitespace-no-wrap">@translate(START DATE)</th>
                    <th class="text-center whitespace-no-wrap">@translate(END DATE)</th>
                    <th class="text-center whitespace-no-wrap">@translate(TYPE)</th>
                    <th class="text-center whitespace-no-wrap">@translate(AMOUNT)</th>
                    <th class="text-center whitespace-no-wrap">@translate(MIN. PURCHASE)</th>
                    <th class="text-center whitespace-no-wrap">@translate(MAX. USAGE)</th>
                    <th class="text-center whitespace-no-wrap">@translate(STATUS)</th>
                    <th class="text-center whitespace-no-wrap">@translate(ACTION)</th>
                </tr>
            </thead>
            <tbody class="mailLogName">
                @forelse (coupons() as $coupon)
                <tr class="intro-x">
                    <td class="text-center">
                        <div class="w-10 h-10 image-fit zoom-in">
                            <img alt="#{{ $coupon->id }}" class="tooltip rounded-full" src="{{ commonAvatar($coupon->id) }}" title="{{ $coupon->id }}">
                        </div>
                    </td>
                    <td class="text-center tooltip" title="@translate(CODE)">{{ $coupon->code }}</td>
                    <td class="text-center tooltip" title="@translate(START DATE)">{{ $coupon->date_from }}</td>
                    <td class="text-center tooltip" title="@translate(END DATE)">{{ $coupon->date_to }}</td>
                    <td class="text-center tooltip" title="@translate(TYPE)">{{ $coupon->type }}</td>
                    <td class="text-center tooltip" title="@translate(RATE)">{{ $coupon->discount_amount }}%</td>
                    <td class="text-center tooltip" title="@translate(MINIMUM PURCHASE)">{{ $coupon->minimum_purchase }}</td>
                    <td class="text-center tooltip" title="@translate(MAXIMUM USAGE)">{{ $coupon->maximum_usage }}</td>

                    <td class="text-center tooltip" title="@translate(STATUS)">

                        <div class="flex items-center justify-center {{ $coupon->status == 1 ? 'text-theme-9' : 'text-theme-6' }}">
                            <i data-feather="check-square" class="w-4 h-4 mr-2"></i> {{ $coupon->status == 1 ? 'Active' : 'Inactive' }}
                        </div>

                    </td>

                    <td class="text-center">
                        <div class="flex justify-center items-center">
                            <a class="flex items-center mr-3 tooltip" title="@translate(Edit)" href="{{ route('coupon.edit', $coupon->id) }}">
                                <i data-feather="check-square" class="w-4 h-4 mr-1"></i>
                            </a>
                            <a class="flex items-center text-theme-6 tooltip" href="{{ route('coupon.destroy', $coupon->id) }}" title="@translate(Delete)">
                                <i data-feather="trash-2" class="w-4 h-4 mr-1"></i>
                            </a>
                        </div>
                    </td>

                </tr>
                @empty
                <td colspan="8">
                    <div class="text-center">
                        <img src="{{ notFound('currency.png') }}" class=" w-6/12 m-auto no-shadow" alt="#coupon-not-found">
                    </div>
                </td>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="intro-y col-span-12 text-center">
        <div class="md:block mx-auto text-gray-600">Showing {{ coupons()->firstItem() ?? '0' }} to {{ coupons()->lastItem() ?? '0' }} of {{ coupons()->total() }} entries</div>
    </div>
    <!-- END: Data List -->
    <!-- BEGIN: Pagination -->
    {{ coupons()->links('vendor.pagination.custom') }}
    <!-- END: Pagination -->

</div>



{{-- modal --}}

<div class="modal" id="superlarge-currency-modal-size-preview">
    <div class="modal__content modal__content--xl p-10">
        <div class="intro-y flex items-center mt-8">
            <h2 class="text-lg font-medium mr-auto">@translate(Add New Coupon)</h2>
        </div>
        <div class="12 mt-5">
            <div class="intro-y">
                <!-- BEGIN: Form Layout -->

                <form class="" enctype="multipart/form-data" action="{{ route('coupon.store') }}" method="POST">
                    @csrf

                    <div class="mt-3">
                        <div class="input-form">
                            <label class="flex flex-col sm:flex-row">
                                @translate(Coupon Code)
                            </label>
                            <input type="text" name="code" class="input w-full border mt-2" placeholder="@translate(Coupon Code)" data-parsley-required>
                            <small>Ex: CODE25</small>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="input-form">
                            <label class="flex flex-col sm:flex-row">
                                @translate(Coupon Starting Date & Time)
                            </label>

                            <input class="datepicker input w-full border mb-2" name="start_date" data-single-mode="true" data-parsley-required>
                            <input class="input w-full border" type="time" name="start_time" data-parsley-required>

                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="input-form">
                            <label class="flex flex-col sm:flex-row">
                                @translate(Coupon Ending Date & Time)
                            </label>
                            <input class="datepicker input w-full border mb-2" name="end_date" data-single-mode="true" data-parsley-required>
                            <input class="input w-full border" type="time" name="end_time" data-parsley-required>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="input-form">
                            <label class="flex flex-col sm:flex-row">
                                @translate(Discount Type)
                            </label>
                            <select data-search="false" name="type" id="" class="w-full form-select sm:w-1/2" data-parsley-required>
                                <option value="percentage">Percentage</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="input-form">
                            <label class="flex flex-col sm:flex-row">
                                @translate(Rate)
                            </label>
                            <input type="number" name="discount_amount" class="input w-full border mt-2" placeholder="@translate(Discount Amount)" data-parsley-required>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="input-form">
                            <label class="flex flex-col sm:flex-row">
                                @translate(Minimum Purchase) <small>(@translate(optional))</small>
                            </label>
                            <input type="number" name="minimum_purchase" class="input w-full border mt-2" placeholder="@translate(Minimum Purchase)">
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="input-form">
                            <label class="flex flex-col sm:flex-row">
                                @translate(Maximum Usage Validity) <small>(@translate(optional))</small>
                            </label>
                            <input type="number" name="maximum_usage" class="input w-full border mt-2" placeholder="@translate(Maximum Usage Validity)">
                            <small>Note: The coupon will application limited use only.</small>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label>Active Status</label>
                        <div class="mt-2">
                            <input type="checkbox" value="1" class="input input--switch border" name="status">
                        </div>
                    </div>

                    <button type="submit" class="button bg-theme-1 text-white mt-5">
                        @translate(Release Coupon)
                    </button>
                </form>
                <!-- END: Form Layout -->

            </div>
        </div>
    </div>
</div>

{{-- modal::END --}}
@endsection

@section('script')

@endsection