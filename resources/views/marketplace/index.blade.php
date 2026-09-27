@extends('../layout/' . layout())

@section('subhead')
  <title>@translate(Marketplace)</title>
@endsection

@section('css')
  <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
@endsection

@section('subcontent')

  <div class="intro-y mt-8 flex items-center">
    <h2 class="mr-auto text-lg font-medium">@translate(Marketplace)</h2>
    <a href="javascript:;" data-toggle="modal" data-target="#superlarge-modal-help">
      <i data-feather="help-circle" class="tooltip text-theme-1" title="@translate(Help)"></i>
    </a>
  </div>

  <div class="mt-5 grid grid-cols-12 gap-6">
    <div class="intro-y col-span-12 sm:col-span-6 xl:col-span-4">
      <div class="report-box zoom-in">
        <div class="box p-5">
          <div class="flex">
            <i data-feather="dollar-sign" class="tooltip text-theme-1" title="@translate(Total Earnings)"></i>
          </div>
          <div class="mt-6 text-3xl font-medium leading-8">${{ marketplace_total_sales() }}</div>
          <div class="mt-1 text-base text-slate-500">@translate(Total Earnings)</div>
        </div>
      </div>
    </div>
    <div class="intro-y col-span-12 sm:col-span-6 xl:col-span-4">
      <div class="report-box zoom-in">
        <div class="box p-5">
          <div class="flex">
            <i data-feather="dollar-sign" class="tooltip text-theme-1" title="@translate(Today Earnings)"></i>
          </div>
          <div class="mt-6 text-3xl font-medium leading-8">${{ marketplace_today_earnings() }}</div>
          <div class="mt-1 text-base text-slate-500">@translate(Today Earnings)</div>
        </div>
      </div>
    </div>
    <div class="intro-y col-span-12 sm:col-span-6 xl:col-span-4">
      <div class="report-box zoom-in">
        <div class="box p-5">
          <div class="flex">
            <i data-feather="shopping-bag" class="tooltip text-theme-1" title="@translate(Sales)"></i>
          </div>
          <div class="mt-6 text-3xl font-medium leading-8">{{ marketplace_today_new_buyer_count() }}</div>
          <div class="mt-1 text-base text-slate-500">@translate(Today Sales)</div>
        </div>
      </div>
    </div>
    <div class="intro-y col-span-12 sm:col-span-6 xl:col-span-4">
      <div class="report-box zoom-in">
        <div class="box p-5">
          <div class="flex">
            <i data-feather="users" class="tooltip text-theme-1" title="@translate(Sales)"></i>
          </div>
          <div class="mt-6 text-3xl font-medium leading-8">{{ marketplace_unique_buyer_email() }}</div>
          <div class="mt-1 text-base text-slate-500">@translate(new Buyers())</div>
        </div>
      </div>
    </div>
    <div class="intro-y col-span-12 sm:col-span-6 xl:col-span-4">
      <div class="report-box zoom-in">
        <div class="box p-5">
          <div class="flex">
            <i data-feather="users" class="tooltip text-theme-9" title="@translate(Buyers)"></i>
          </div>
          <div class="mt-6 text-3xl font-medium leading-8">{{ marketplace_total_buyer() }}</div>
          <div class="mt-1 text-base text-slate-500">@translate(Total Buyers)</div>
        </div>
      </div>
    </div>
    <div class="intro-y col-span-12 sm:col-span-6 xl:col-span-4">
      <div class="report-box zoom-in">
        <div class="box p-5">
          <div class="flex">
            <i data-feather="trending-up" class="tooltip text-theme-7" title="@translate(Trending)"></i>
          </div>
          <div class="mt-6 text-2xl font-medium leading-8">
            {{ marketplace_country_code(Str::upper(marketplace_most_purchased_country())) }}({{ Str::upper(marketplace_most_purchased_country()) }})
          </div>
          <div class="mt-1 text-base text-slate-500">@translate(Trending CSV)</div>
        </div>
      </div>
    </div>
  </div>


  <!-- BEGIN: Profile Info -->
  <div class="intro-y box mt-5 px-5 pt-5">
    <div id="monthly-chart-1"></div>
  </div>
  <!-- END: Profile Info -->
  <div class="tab-content mt-5">

    <div class="mt-8 grid grid-cols-12 gap-6">
      <div class="col-span-12 lg:col-span-3 2xl:col-span-2">
        <h2 class="intro-y mr-auto mt-2 text-lg font-medium">@translate(CSV File Manager)</h2>
        <!-- BEGIN: File Manager Menu -->
        <div class="intro-y box mt-6 p-5">

          <div class="mt-1">
            <a href="javascript:;" class="tooltip mt-2 flex items-center rounded-md px-3 py-2" title="Settings"
               data-toggle="modal" data-target="#superlarge-modal-settings">
              <i data-feather="dollar-sign" class="mr-2 h-4 w-4"></i> @translate(Pricing Setup)
            </a>
          </div>

          <div class="mt-1">
            <a href="{{ route('marketplace.buyers') }}" class="tooltip mt-2 flex items-center rounded-md px-3 py-2"
               title="Buyers">
              <i data-feather="users" class="mr-2 h-4 w-4"></i>
              @translate(Buyers)

              @if (marketplace_today_new_buyer_count() > 0)
                <span class="text-theme-1 ml-1">
                  ({{ marketplace_today_new_buyer_count() }})
                </span>
              @endif


            </a>
          </div>

          <div class="mt-1">
            <a href="{{ route('marketplace.csv.viewer') }}" target="_blank"
               class="tooltip mt-2 flex items-center rounded-md px-3 py-2" title="CSV Viewer">
              <i data-feather="dollar-sign" class="mr-2 h-4 w-4"></i> @translate(CSV Viewer)
            </a>
          </div>

        </div>

        <div class="intro-y mt-6">
          <div class="report-box zoom-in mt-6">
            <div class="box p-5">
              <div class="flex">
                <i data-feather="file"></i>
              </div>
              <div class="tooltip mt-6 text-3xl font-medium leading-8"
                   title="CSV Files: {{ count(marketplace_csv_files()) }}">{{ count(marketplace_csv_files()) }}</div>
              <div class="tooltip mt-1 text-base text-slate-500" title="CSV Files">@translate(CSV Files)</div>
            </div>
          </div>

          <div class="report-box zoom-in mt-6">
            <div class="box p-5">
              <div class="flex">
                <i data-feather="hard-drive"></i>
              </div>
              <div class="tooltip mt-6 text-3xl font-medium leading-8"
                   title="@translate(Total Size): {{ all_csv_file_size() }}">{{ all_csv_file_size() }}</div>
              <div class="tooltip mt-1 text-base text-slate-500" title="@translate(Total Size)">@translate(Total Size)</div>
            </div>
          </div>

          <div class="report-box zoom-in mt-6">
            <div class="box p-5">
              <div class="tooltip mt-1 text-2xl font-medium leading-8" title="@translate(CSV Pricing Calculator)">@translate(CSV Pricing Calculator)
              </div>

              <div class="intro-y mt-6 items-center">

                <div class="mt-6">
                  <code class="text-red-500">
                    email quantity × each email price = total
                  </code>
                </div>

                <div class="mt-6">
                  <code class="text-theme-1">
                    Example: 5 × $5 = $25
                  </code>
                </div>

                <div class="mt-6 grid grid-cols-12 gap-2">
                  <input type="number" id="quantity_per_email_widget" min="0"
                         onkeyup="QuantityPerEmailWidget()" class="input col-span-6 w-full border bg-gray-100"
                         placeholder="Quantity Of Email">
                  <input type="number" id="quantity_price_widget" onkeyup="QuantityPriceWidget()"
                         class="input col-span-6 w-full border bg-gray-100" placeholder="Each Email Price">
                </div>

                <div class="mt-6 text-center">
                  <code style="font-family: 'Fredoka', sans-serif;font-size: 46px;">
                    <span id="quantity_per_email_html_widget">0</span>
                    <span>×</span>
                    <span id="quantity_price_html_widget">0</span>
                    <span>=</span>
                    <span id="quantity_total_price_html_widget">$0</span>
                  </code>
                </div>

              </div>

            </div>
          </div>
        </div>

        <!-- END: File Manager Menu -->
      </div>
      <div class="col-span-12 lg:col-span-9 2xl:col-span-10">
        <!-- BEGIN: File Manager Filter -->
        <div class="intro-y flex flex-col-reverse items-center sm:flex-row">

          <a href="javascript:;" data-toggle="modal" data-target="#superlarge-modal-size-preview-csv"
             class="button button--lg bg-theme-4 mt-2 flex w-full items-center justify-center text-white">
            <i class="mr-2 h-4 w-4" data-feather="edit-3"></i> @translate(Upload New CSV File)
          </a>

        </div>
        <!-- END: File Manager Filter -->
        <!-- BEGIN: Directory & Files -->
        <div class="intro-y mt-5 grid grid-cols-12 gap-2 sm:gap-6">

          @forelse (marketplace_csv_files() as $marketplace_csv_file)
            <div class="intro-y col-span-4 sm:col-span-3 md:col-span-3 2xl:col-span-2">
              <div class="file box zoom-in relative rounded-md px-3 px-5 pb-5 pt-8 sm:px-5">

                <div class="file__icon file__icon--file image-fit tooltip mx-auto w-2/4"
                     title="{{ Str::upper($marketplace_csv_file->country) }}">
                  <div class="file__icon__file-name text-xs">{{ Str::upper($marketplace_csv_file->country) }}</div>
                </div>

                <span class="tooltip mt-4 block truncate text-center font-medium"
                      title="{{ $marketplace_csv_file->csv_file_path }}">
                  {{ $marketplace_csv_file->csv_file_path }}
                </span>
                <div class="tooltip mt-0.5 text-center text-xs text-slate-500"
                     title="filesize: {{ directory_csv_file_size($marketplace_csv_file->csv_file_path) }}">
                  {{ directory_csv_file_size($marketplace_csv_file->csv_file_path) }}
                </div>
                <div class="tooltip mt-0.5 text-center text-xs text-slate-500"
                     title="Last update: {{ $marketplace_csv_file->updated_at->diffForHumans() }}">
                  {{ $marketplace_csv_file->updated_at->diffForHumans() }}
                </div>

                <div class="dropdown tooltip absolute right-0 top-0 ml-auto mr-2 mt-3" title="Action">
                  <a href="javascript:;" class="dropdown-toggle h-5 w-5 text-gray-500 dark:text-gray-300">
                    <i data-feather="more-vertical" class="h-4 w-4"></i>
                  </a>
                  <div class="dropdown-box w-28">
                    <div class="dropdown-box__content box dark:bg-dark-1 p-2">

                      <a href="javascript:;" data-toggle="modal"
                         data-target="#superlarge-modal-size-preview-csv-upgrade_{{ $marketplace_csv_file->country }}"
                         class="dark:bg-dark-1 dark:hover:bg-dark-2 tooltip block flex items-center rounded-md bg-white p-2 transition duration-300 ease-in-out hover:bg-gray-200"
                         title="Upload">
                        <i data-feather="upload" class="mr-2 h-4 w-4"></i> @translate(Update)
                      </a>

                      {{-- UPGRADE MODAL --}}
                      <div class="modal modal-slide-over"
                           id="superlarge-modal-size-preview-csv-upgrade_{{ $marketplace_csv_file->country }}">
                        <div class="modal__content modal__content--xl p-10">
                          <div class="intro-y mt-8 items-center">
                            <h2 class="mr-auto text-lg font-medium">@translate(Update CSV File) -
                              {{ $marketplace_csv_file->csv_file_path }}</h2>
                          </div>

                          <div class="mt-5 grid grid-cols-12 gap-12">
                            <div class="intro-y col-span-12 lg:col-span-12">
                              <!-- BEGIN: Form Layout -->

                              <form action="{{ route('marketplace.csv_update', $marketplace_csv_file->country) }}"
                                    method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="csv_update" id="csv_upgrader" accept=".csv">

                                <button type="submit">Update</button>

                              </form>

                            </div>

                            <!-- END: Form Layout -->

                          </div>
                        </div>
                      </div>
                      {{-- UPGRADE MODAL::END --}}

                      @if ($marketplace_csv_file->marketplace_setting)
                        <a href="javascript:;" data-toggle="modal"
                           data-target="#superlarge-modal-size-preview-csv-pricing_{{ $marketplace_csv_file->country }}"
                           class="dark:bg-dark-1 dark:hover:bg-dark-2 tooltip block flex items-center rounded-md bg-white p-2 transition duration-300 ease-in-out hover:bg-gray-200"
                           title="Pricing">
                          <i data-feather="dollar-sign" class="mr-2 h-4 w-4"></i> @translate(Pricing)
                        </a>

                        {{-- UPGRADE MODAL --}}
                        <div class="modal modal-slide-over"
                             id="superlarge-modal-size-preview-csv-pricing_{{ $marketplace_csv_file->country }}">
                          <div class="modal__content modal__content--xl p-10">

                            <div class="intro-y mt-6 items-center">
                              <h2 class="mr-auto text-lg font-medium">{{ Str::upper($marketplace_csv_file->country) }} -
                                @translate(CSV Pricing)</h2>
                            </div>

                            <div class="mt-5 grid grid-cols-12 gap-12">
                              <div class="intro-y col-span-12 lg:col-span-12">
                                <!-- BEGIN: Form Layout -->

                                <form action="{{ route('marketplace.csv_settings.update', $marketplace_csv_file->country) }}"
                                      method="post">
                                  @csrf

                                  <select name="country" class="form-select w-full" required>
                                    <option value="">Select Country</option>
                                    @forelse (App\Models\MarketplaceCSV::OrderBy('country')->get() as $country_name_code_list)
                                      <option value="{{ Str::lower($country_name_code_list->country) }}"
                                              {{ Str::lower($country_name_code_list->country) == $marketplace_csv_file->country ? 'selected' : null }}>
                                        {{ Str::upper($country_name_code_list->country) }}
                                      </option>
                                    @empty
                                    @endforelse
                                  </select>


                                  <div class="mt-6 grid grid-cols-12 gap-2">
                                    <input type="number" name="min"
                                           value="{{ marketplace_setting($marketplace_csv_file->id)->min ?? 0 }}"
                                           class="input col-span-6 w-full border bg-gray-100" placeholder="Min amount"
                                           required>
                                    <input type="number" min="1" name="max"
                                           value="{{ marketplace_setting($marketplace_csv_file->id)->max ?? 0 }}"
                                           class="input col-span-6 w-full border bg-gray-100" placeholder="Max amount"
                                           required>
                                  </div>

                                  <div class="input-group mt-6">
                                    <input type="number" min="0"
                                           value="{{ marketplace_setting($marketplace_csv_file->id)->each_price ?? 0 }}"
                                           name="each_price" class="input w-full border bg-gray-100"
                                           placeholder="Per Email Price" required>
                                    <small>All prices are in USD($)</small>
                                  </div>

                                  <button type="submit" class="button bg-theme-1 mr-2 mt-2 w-full text-white">Update
                                    Data</button>

                                </form>

                                <!-- END: Form Layout -->
                              </div>
                            </div>

                          </div>
                        </div>
                        {{-- UPGRADE MODAL::END --}}
                      @endif

                      <a href="{{ route('marketplace.csv.destroy', $marketplace_csv_file->country) }}"
                         class="dark:bg-dark-1 dark:hover:bg-dark-2 tooltip block flex items-center rounded-md bg-white p-2 transition duration-300 ease-in-out hover:bg-gray-200"
                         title="Trash">
                        <i data-feather="trash" class="mr-2 h-4 w-4"></i> @translate(Trash)
                      </a>

                      <a href="{{ route('marketplace.csv.download', $marketplace_csv_file->country) }}"
                         class="dark:bg-dark-1 dark:hover:bg-dark-2 tooltip block flex items-center rounded-md bg-white p-2 transition duration-300 ease-in-out hover:bg-gray-200"
                         title="Download">
                        <i data-feather="download-cloud" class="mr-2 h-4 w-4"></i> @translate(Download)
                      </a>

                    </div>
                  </div>
                </div>

              </div>
            </div>

          @empty

            <div class="intro-y col-span-12 sm:col-span-12 md:col-span-12 2xl:col-span-12">
              <img src="{{ notFound('campain-not-found.png') }}" class="no-shadow m-auto" alt="#csv-not-found">
            </div>
          @endforelse

        </div>
        <!-- END: Directory & Files -->
      </div>
    </div>
  </div>

  {{-- NEW UPLOAD MODAL --}}
  <div class="modal modal-slide-over" id="superlarge-modal-size-preview-csv">
    <div class="modal__content modal__content--xl p-10">
      <div class="intro-y mt-8 items-center">
        <h2 class="mr-auto text-lg font-medium">@translate(Upload New CSV File)</h2>
      </div>

      <div class="mt-5 grid grid-cols-12 gap-12">
        <div class="intro-y col-span-12 lg:col-span-12">
          <!-- BEGIN: Form Layout -->

          <div id="csv_country_form">
            <form class="" enctype="multipart/form-data" action="{{ route('marketplace.csv_upload') }}"
                  method="POST">
              @csrf

              <label for="csv_country" class="flex flex-col sm:flex-row">@translate(Country Code)</label>
              <select name="country" id="csv_country" class="form-select w-full sm:w-1/2">
                <option value="">Select Country</option>
                @forelse (country_name_code_lists() as $country_name_code_list)
                  @if (!marketplace_csv_exists($country_name_code_list['code']))
                    <option value="{{ Str::lower($country_name_code_list['code']) }}">
                      {{ $country_name_code_list['country'] }}({{ $country_name_code_list['code'] }})
                    </option>
                  @endif
                @empty
                @endforelse
              </select>

              <div>
                <button type="submit" class="button bg-theme-1 mr-2 mt-2 text-white"
                        onclick="SubmitCountryForm()">@translate(Save)</button>
              </div>

            </form>

          </div>

          <div class="hidden" id="fildpond_form">
            <input type="file" name="filepond" class="filepond" id="csv_uploader" accept=".csv">

            <div>
              <button type="submit" class="button bg-theme-1 mr-2 mt-2 text-white"
                      onclick="BackToCountryForm()">@translate(Back)</button>
            </div>
          </div>


        </div>

        <!-- END: Form Layout -->

      </div>
    </div>
  </div>
  {{-- NEW UPLOAD MODAL::END --}}


  <!-- BEGIN: Modal Content -->
  <div class="modal modal-slide-over" id="superlarge-modal-settings">
    <div class="modal__content modal__content--xl p-10">

      <div class="intro-y mt-6 items-center">
        <h2 class="mr-auto text-lg font-medium">@translate(CSV Pricing)</h2>
      </div>

      <div class="mt-5 grid grid-cols-12 gap-12">
        <div class="intro-y col-span-12 lg:col-span-12">
          <!-- BEGIN: Form Layout -->
          <form action="{{ route('marketplace.csv_settings') }}" method="post">
            @csrf

            <select name="country" class="form-select w-full" required>
              <option value="">Select Country</option>
              @forelse (App\Models\MarketplaceCSV::OrderBy('country')->get() as $country_name_code_list)
                @if (!marketplace_settings_exists($country_name_code_list->id))
                  <option value="{{ Str::lower($country_name_code_list->country) }}">
                    {{ Str::upper($country_name_code_list->country) }}
                  </option>
                @endif
              @empty
              @endforelse
            </select>

            <div class="mt-6 grid grid-cols-12 gap-2">
              <input type="number" name="min" class="input col-span-6 w-full border bg-gray-100"
                     placeholder="Min amount" required>
              <input type="number" min="1" name="max" class="input col-span-6 w-full border bg-gray-100"
                     placeholder="Max amount" required>
            </div>

            <div class="input-group mt-6">
              <input type="text" min="0" name="each_price" class="input w-full border bg-gray-100"
                     placeholder="Per Email Price" required>
              <small>All prices are in USD($)</small>
            </div>

            <button type="submit" class="button bg-theme-1 mr-2 mt-2 w-full text-white">Save Data</button>

          </form>

          <!-- END: Form Layout -->
        </div>
      </div>

      <div class="intro-y mt-6 items-center">
        <h2 class="mr-auto text-lg font-medium">@translate(Pricing Calculator)</h2>

        <div class="mt-6">
          <code class="text-red-500">
            quantity of email × each email price = total price
          </code>
        </div>

        <div class="mt-6">
          <code class="text-theme-1">
            Example: 5 × $5 = $25
          </code>
        </div>

        <div class="mt-6 grid grid-cols-12 gap-2">
          <input type="number" id="quantity_per_email" min="0" onkeyup="QuantityPerEmail()"
                 class="input col-span-6 w-full border bg-gray-100" placeholder="Quantity Of Email">
          <input type="number" id="quantity_price" onkeyup="QuantityPrice()"
                 class="input col-span-6 w-full border bg-gray-100" placeholder="Each Email Price">
        </div>

        <div class="mt-6 text-center">
          <code style="font-family: 'Fredoka', sans-serif;font-size: 46px;">
            <span id="quantity_per_email_html">0</span>
            <span>×</span>
            <span id="quantity_price_html">0</span>
            <span>=</span>
            <span id="quantity_total_price_html">$0</span>
          </code>
        </div>

      </div>

    </div>
  </div>
  <!-- END: Modal Content -->

  <!-- BEGIN: Modal Content -->
  <div class="modal modal-slide-over" id="superlarge-modal-help">
    <div class="modal__content modal__content--xl p-10">

      <div class="intro-y mt-6 items-center">
        <h2 class="mr-auto text-lg font-medium">@translate(How it works?)</h2>
      </div>

      <div class="mt-5 grid grid-cols-12 gap-12">
        <div class="intro-y col-span-12 lg:col-span-12">
          <!-- BEGIN: Form Layout -->

          <h2 class="font-medium">
            1. How Marketplace works?
          </h2>
          <br>
          <h5>
            - You can upload your CSV file to the system. <br>
            - You can set the price for each email. <br>
            - You can set the minimum and maximum amount of emails. <br>
            - Customer can buy emails from the marketplace. <br>
            - Customer can see the email quantity and the total price. <br>
            - Customer can pay for the emails. <br>
            - Customer will get an email after the purchase. <br>
            - Customer will get the CSV after the purchase, make sure the SMTP settings working file. <br>
          </h5>
          <br>

          <h2 class="font-medium">
            2. How to upload CSV file?
          </h2>
          <br>
          <h5>
            - Go to "Upload New CSV FIle" selet country and upload CSV file. <br>
            - You can see the CSV file in the "CSV File Manager" section. <br>
          </h5>
          <br>

          <h5 class="font-medium">
            3. How to update CSV file?
          </h5>
          <br>

          <h5>
            - Go to "CSV File Manager" section and select the CSV file you want to update. <br>
            - You can see the CSV file in the "CSV File Manager" section. <br>
          </h5>
          <br>

          <h2 class="font-medium">
            4. How to set the price for each email?
          </h2>
          <br>

          <h5>
            - Go to "Pricing Setup" section and set the price for each email. <br>
            - You can see the price in the "Pricing Calculator" section. <br>
          </h5>
          <br>

          <h2 class="font-medium">
            5. How to set the minimum and maximum amount of emails?
          </h2>
          <br>

          <h5>
            - Go to "Pricing Setup" section and set the minimum and maximum amount of emails. <br>
            - You can see the minimum and maximum amount in the "Pricing Calculator" section. <br>
          </h5>
          <br>

          <h2 class="font-medium">
            6. How to buy emails?
          </h2>
          <br>

          <h5>
            - Go to "Marketplace Page" section and select the country and the quantity of emails. <br>
            - You can see the total price in the "Pricing Calculator" section. <br>
            - You can see the email quantity and the total price in the "Buy Emails" section. <br>
            - You can pay for the emails in the "Buy Emails" section. <br>
            - You will get an email after the purchase. <br>
            - You will get the CSV after the purchase, make sure the SMTP settings working file. <br>
          </h5>

          <!-- END: Form Layout -->
        </div>
      </div>

    </div>
  </div>
  <!-- END: Modal Content -->



@endsection

@section('script')
  <script src="{{ filePath('assets/js/apexcharts.js') }}"></script>
  <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
  <script>
    "use strict";

    var options = {
      series: [{
        name: '($)Sales',
        data: [
          @forelse (monthlyWiseSales() as $monthlyWiseSale)
            {{ $monthlyWiseSale->price }},
          @empty
          @endforelse
        ]
      }],
      chart: {
        height: 350,
        type: 'bar',
      },
      plotOptions: {
        bar: {
          borderRadius: 10,
          columnWidth: '50%',
        }
      },
      dataLabels: {
        enabled: true
      },
      stroke: {
        width: 2
      },

      grid: {
        row: {
          colors: ['#fff', '#f2f2f2']
        }
      },
      xaxis: {
        labels: {
          rotate: -45
        },
        categories: [
          @forelse (monthlyWiseSales() as $monthlyWiseSale)
            '{{ monthNameByNumber($monthlyWiseSale->month) }}',
          @empty
          @endforelse
        ],
        tickPlacement: 'on'
      },
      yaxis: {
        title: {
          text: 'Monthly Sales($)',
        },
      },
      fill: {
        type: 'gradient',
        gradient: {
          shade: 'light',
          type: "horizontal",
          shadeIntensity: 0.25,
          gradientToColors: undefined,
          inverseColors: true,
          opacityFrom: 0.85,
          opacityTo: 0.85,
          stops: [50, 0, 100]
        },
      }
    };

    var chart = new ApexCharts(document.querySelector("#monthly-chart-1"), options);
    chart.render();

    // Country Form
    function SubmitCountryForm() {
      // preventDefault
      event.preventDefault();
      var country = $('#csv_country').val();
      if (country == '') {
        alert('Please select country');
        return false;
      } else {
        // XMLHttpRequest
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '{{ route('marketplace.csv_upload') }}', true);
        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
          if (xhr.readyState == 4 && xhr.status == 200) {
            var data = JSON.parse(xhr.responseText);
            console.log(data);
          }
        }
        xhr.send('country=' + country);
      }
      $('#fildpond_form').removeClass('hidden');
      $('#csv_country_form').addClass('hidden');
    }

    // BackToCountryForm
    function BackToCountryForm() {
      // preventDefault
      event.preventDefault();
      $('#fildpond_form').addClass('hidden');
      $('#csv_country_form').removeClass('hidden');
    }

    // Filepond
    FilePond.parse(document.body);

    FilePond.setOptions({

      server: {
        process: {

          url: '{{ route('marketplace.csv_upload') }}',
          data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
          },
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          onload: function(response) {
            console.log(response);
            alert(response);
            location.reload();
          },
          onerror: function(response) {
            console.log(response);
            alert(response);
            location.reload();
          },

        },

      },


    });
    // Filepond::END

    function QuantityPerEmail() {
      var quantity_per_email = document.getElementById('quantity_per_email').value;

      if (quantity_per_email == '') {
        quantity_per_email = 0;
      }

      document.getElementById('quantity_per_email_html').innerHTML = quantity_per_email;

      var quantity_price = document.getElementById('quantity_price').value;

      if (quantity_price == '') {
        quantity_price = 0;
      }

      document.getElementById('quantity_price_html').innerHTML = quantity_price;

      var quantity_total_price = quantity_per_email * quantity_price;

      if (quantity_total_price == '') {
        quantity_total_price = 0;
      }

      document.getElementById('quantity_total_price_html').innerHTML = '$' + quantity_total_price;
    }

    function QuantityPrice() {
      var quantity_per_email = document.getElementById('quantity_per_email').value;

      if (quantity_per_email == '') {
        quantity_per_email = 0;
      }

      document.getElementById('quantity_per_email_html').innerHTML = quantity_per_email;

      var quantity_price = document.getElementById('quantity_price').value;

      if (quantity_price == '') {
        quantity_price = 0;
      }

      document.getElementById('quantity_price_html').innerHTML = quantity_price;

      var quantity_total_price = quantity_per_email * quantity_price;

      if (quantity_total_price == '') {
        quantity_total_price = 0;
      }

      document.getElementById('quantity_total_price_html').innerHTML = '$' + quantity_total_price;
    }

    function QuantityPerEmailWidget() {
      var quantity_per_email = document.getElementById('quantity_per_email_widget').value;

      if (quantity_per_email == '') {
        quantity_per_email = 0;
      }

      document.getElementById('quantity_per_email_html_widget').innerHTML = quantity_per_email;

      var quantity_price = document.getElementById('quantity_price_widget').value;

      if (quantity_price == '') {
        quantity_price = 0;
      }

      document.getElementById('quantity_price_html_widget').innerHTML = quantity_price;

      var quantity_total_price = quantity_per_email * quantity_price;

      if (quantity_total_price == '') {
        quantity_total_price = 0;
      }

      document.getElementById('quantity_total_price_html_widget').innerHTML = '$' + quantity_total_price;
    }

    function QuantityPriceWidget() {
      var quantity_per_email = document.getElementById('quantity_per_email_widget').value;

      if (quantity_per_email == '') {
        quantity_per_email = 0;
      }

      document.getElementById('quantity_per_email_html_widget').innerHTML = quantity_per_email;

      var quantity_price = document.getElementById('quantity_price_widget').value;

      if (quantity_price == '') {
        quantity_price = 0;
      }

      document.getElementById('quantity_price_html_widget').innerHTML = quantity_price;

      var quantity_total_price = quantity_per_email * quantity_price;

      if (quantity_total_price == '') {
        quantity_total_price = 0;
      }

      document.getElementById('quantity_total_price_html_widget').innerHTML = '$' + quantity_total_price;
    }
  </script>
@endsection
