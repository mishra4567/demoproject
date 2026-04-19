@extends('admin.layout.layout')
@section('page_title', $title ?? 'Not Found')
@section('container')
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            @include('admin.partials.not_found', [
                'type' => $type ?? '404',
                'title' => $title ?? null,
                'message' => $message ?? null,
                'btnText' => $btnText ?? null,
                'btnUrl' => $btnUrl ?? null,
            ])
        </div>
    </div>
@endsection
