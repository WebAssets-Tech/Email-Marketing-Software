
  <div class="rounded-md flex items-center px-5 py-4 mb-2 bg-theme-17 text-theme-11 border-warning">
    <a href="javascript:;" data-toggle="modal" data-target="#superlarge-limit-modal-size-preview"
                 title="@translate(Please set the time zone for Proper functionality)">
                @translate(Please set the time zone for Proper functionality)
                <span class="font-bold mx-4" role="button"> Click here to Update Timezone</span>
      </a>

  </div>
@php
$timeZonesPath = public_path('timezone.json');
$jsonContents = file_get_contents($timeZonesPath);
$timeZonesArray = json_decode($jsonContents, true);
@endphp

<div class="modal" id="superlarge-limit-modal-size-preview">
  <div class="modal__content modal__content--xl p-10">
    <div class="intro-y box p-5 mt-12 sm:mt-5">
      <form action="{{route('updateTimeZone')}}" method="Post">
        @csrf
        <div class="input-form" id="timezone">             
          <label class="flex flex-col sm:flex-row"> @translate(Select Time Zone) <span
                  class="sm:ml-auto mt-1 sm:mt-0 text-xs text-gray-600">Ex: America/Chicago</span>
          </label>               
          <select style="z-index: 100!important;" data-placeholder="Select your favorite actors" name="timezone" data-search="true" class="tail-select w-full" single data-parsley-required> 
              <option selected>Select Zone</option> 
              @foreach ($timeZonesArray as $key=>$timeZone)   
                <option value="{{$key}}">{{$timeZone}}</option> 
              @endforeach                
          </select> 

        </div>
        <button type="submit" class="button bg-theme-1 text-white mt-4">@translate(Save Time Zone)</button>
      </form>
  </div>
      
  </div>
</div>

