@extends('install.app')
@section('css')
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <style>
        .filepond--root.filepond--hopper {
            width: 100% !important;
            height: 100% !important;
        }

        .filepond--drop-label {
            height: 100%;
        }
    </style>
@endsection
@section('content')
    <div class="flex justify-end p-8 pb-0">
        <a class="text-blue-500 font-bold flex gap-1 items-center"
        title="{{__('Update Guide')}}"
        target="_blank"
            href="https://mojahid.notion.site/Update-Process-Documentation-c83ea824f0824fa8b38f4a25a69b32ba?pvs=4">
            <img width="20px" src="https://api.iconify.design/material-symbols:shield-question-rounded.svg?color=%233b82fa" alt="Update Guide Icon">
            {{__('Update Guide')}}</a>
    </div>
    <div class="grid grid-cols-6 gap-8 items-center justify-center min-h-screen px-8">

        <div class="card shadow-lg compact bg-base-100 hidden md:block md:col-span-3 lg:col-span-2 bg-white">

            @if (session()->has('app-updated'))
                <img class="h-full w-full" src="{{ filePath('gifs/kawaii-love.gif') }}" />
            @else
                <img class="h-full w-full" src="{{ filePath('gifs/capoo-bugcat.gif') }}" />
            @endif
        </div>


        <div class="card shadow-lg bg-base-100 col-span-6 md:col-span-3 lg:col-span-4">
            <div class="card-body {{ session()->has('app-updated') ? 'bg-soft-success' : '' }}">
                @if (session()->has('app-updated'))
                    <div class="text-4xl font-bold card-title flex h-full items-center justify-center">
                        <div>
                            <h3 class="text-success">{{ session()->get('app-updated') }}</h3>
                            <div class="text-center text-sm text-success"> Version {{ env('VERSION') }}</div>
                        </div>

                    </div>
                @else
                    <h2 class="text-lg lg:text-3xl font-bold card-title text-center">
                        <a href="{{ route('frontend.index') }}">
                            {{ __('Maildoll - Email & SMS Marketing SaaS Application') }}
                        </a>

                        <small> -v{{ env('VERSION') }}</small>
                    </h2>
                    <div class="h-64">

                        <input type="file" id="file" />

                    </div>
                @endif


                <form action="{{ route('auto.update.store') }}" class="flex justify-center" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="file">
                    <button id="click_update" class="btn hidden mt-2">{{ __('Click-to-Upgrade') }}</button>
                </form>

                @if (!session()->has('app-updated'))
                    <div class="-m-2 mt-5">
                        <div class="flex justify-center">
                            <ul class="alert alert-warning  rounded-lg shadow text-md font-medium p-2 mx-2 text-center">
                                {{ __('Tip: Before application update please make sure you have backed up your database and files. Upgrade may take time to finish. Do not close this window or disconnect your internet connection.') }}
                            </ul>
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </div>
@endsection
@section('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.27.3/dist/apexcharts.min.js"></script> --}}
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    {{-- <script>
                let options = {
                    series: [0],
                    colors: ["#22C55E"],
                    chart: {
                        height: 220,
                        type: 'radialBar',
                    },
                    plotOptions: {
                        radialBar: {
                            hollow: {
                                margin: 0,
                                size: "65%",
                            },
                            startAngle: -120,
                            endAngle: 120,
                            dataLabels: {
                                name: {
                                    fontSize: '16px',
                                    color: '#22C55E',
                                    offsetY: 20
                                },
                                value: {
                                    fontSize: '14px',
                                    fontWeight: 600,
                                    color: '#22C55E',
                                    offsetY: -20,
                                    formatter: function(val) {
                                        return val + "%";
                                    }
                                },
                                button: {
                                    fontSize: '16px',
                                    color: '#f1556c',
                                }
                            }
                        }
                    },
                    stroke: {
                        dashArray: 3
                    },
                    labels: ['Completed'],
                };
    
                let progress = $('#apex-progress');
    
                let chart = new ApexCharts(document.querySelector('#apex-progress'), options);
                chart.render()
                progress.hide()
    
    
    
                function updateProgress(progress) {
                    chart.updateOptions({
                        series: [progress]
                    })
                }
    
    
                let browseFile = $('#browseFile');
                let resumable = new Resumable({
                    target: "{{ route('zip.uploader') }}",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    fileType: ['zip'],
                    testChunks: false,
                    throttleProgressCallbacks: 1,
                    chunksize:1024*1024*1024,
                    forceChunkSize:true
                });
    
    
                resumable.assignBrowse(browseFile[0]);
                resumable.assignDrop(browseFile[0]);
    
                function file(action) {
                    progress.toggle()
                    $("#cancelUpload").toggleClass('hidden')
                    browseFile.toggle()
    
                    if (action === 'upload') {
                        $("#browseFile").css('opacity', 0.4)
                        resumable.upload()
                    } else {
                        $("#browseFile").css('opacity', 1)
                        resumable.cancel()
                        browseFile.show().removeClass('hidden')
                    }
                }
    
                resumable.on('fileAdded', e => file('upload'));
                $("#cancelUpload").on("click", e => file('cancel'))
    
                resumable.on('fileProgress', function(file) { // trigger when file progress update
                    updateProgress(Math.floor(file.progress() * 100));
                });
    
                resumable.on('fileSuccess', function(file, response) { 
                    console.log(response);// trigger when file upload complete
                    response = JSON.parse(response)
                    $('input[name="file"]').val(response.path);
                    $('#cancelUpload').addClass('hidden')
                    $('#success').removeClass('hidden')
                    $('#click_update').removeClass('hidden')
                });
    
                resumable.on('fileError', function(file, response) { // trigger when there is any error
                    updateProgress(0);
                    $("#cancelUpload").hide()
                    $('#success').removeClass('hidden')
                    $('#click_update').removeClass('hidden')
                    browseFile.toggle()
                });
    </script> --}}




    {{-- file pond chunk upload --}}
    <script>
        const inputElement = document.querySelector('input[id="file"]');
        const pond = FilePond.create(inputElement);
        FilePond.setOptions({
            chunkUploads: true,
            server: {
                url: '/filepond/upload',
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }
        });
        pond.on('processfile', (e, file) => {
            console.log(file);
            $('#click_update').removeClass('hidden')
        });
    </script>
@endsection
