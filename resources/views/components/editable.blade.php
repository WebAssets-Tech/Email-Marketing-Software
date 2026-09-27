@props(['route' => route('moniz.update')])
@php
    $id = $attributes->has('id') ? $attributes->get('id') : str($key)->replace('->', '-');
    $keys = explode('->', $key);
@endphp
<span oncontextmenu="return false;">
    <span id="{{$id}}"
        @can('Admin')
    oncontextmenu="return false;" class="contentEditable"
    hx-trigger="blur" hx-include=".{{ $id }}" hx-swap="none"
    x-on:keyup="$refs['{{ $id }}'].value = document.querySelector('#{{$id}}').innerText;" 
    contenteditable="true"
    hx-post="{{$route}}"
    @endcan
        style="z-index: 10!important;">
        {{ $slot }}
    </span>
    @can('Admin')
        <input type="hidden" class="{{ $id }}" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" class="{{ $id }}" name="key" value="{{ $key }}">
        <input x-ref="{{ $id }}" type="hidden" class="{{ $id }}" name="value"
            value="{{ $slot }}">
    @endcan
</span>
