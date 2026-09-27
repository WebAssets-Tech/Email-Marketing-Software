@props([
    'type' => 'img',
    'key' => '',
    'iconCenter' => false,
    'iconSize' => 24,
    'iconColor' => 'var(--moniz-primary)',
])

@php
    $id = $attributes->has('id') ? $attributes->get('id') : str($key)->replace('->', '-');
    $style = $attributes->has('style') ? $attributes->get('style') : '';
    if ($iconCenter) {
        $style = $style . 'position:absolute;top:50%;left:50%;transform:translate(-50%, -50%);margin:0;';
    }

@endphp

@pushOnce('styles')
    <style>
        .filepond--root {
            max-height: 600px;
            min-height: 350px;
        }
    </style>
@endPushOnce

<div class="d-inline-flex gap-2 align-items-start">
    {{ $slot }}

    @can('Admin')
        <!-- Button trigger modal -->
        <span style="margin-top: -4px;z-index:100!important;{{ $style }}" role="button" data-bs-toggle="modal"
            data-bs-target="#{{ $id }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="{{ $iconSize }}" height="{{ $iconSize }}"
                viewBox="0 0 {{ $iconSize }} {{ $iconSize }}">
                <path fill="{{ $iconColor }}"
                    d="M15.275 12.475L11.525 8.7L14.3 5.95l-.725-.725L8.1 10.7L6.7 9.3l5.45-5.475q.6-.6 1.413-.6t1.412.6l.725.725l1.25-1.25q.3-.3.713-.3t.712.3L20.7 5.625q.3.3.3.713t-.3.712zM6.75 21H3v-3.75l7.1-7.125l3.775 3.75z" />
            </svg>
        </span>
    @endcan
</div>

@can('Admin')
    @push('modals')
        <!-- Modal -->
        <div class="modal fade" id="{{ $id }}" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="{{ $id }}Label" aria-hidden="true" style="z-index: 200!important;">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('moniz.submit.image') }}" method="post" class="modal-content">
                    @csrf
                    @method('post')
                    <div class="modal-header">
                        <h5 class="modal-title" id="{{ $id }}Label">Upload an image</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" class="filepond-input">
                        <input type="hidden" name="key" value="{{ $key }}">
                    </div>
                    <div class="modal-footer">
                        {{-- <button type="button" class="" data-bs-dismiss="modal">Close</button> --}}
                        <button class="thm-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    @endpush
@endcan
