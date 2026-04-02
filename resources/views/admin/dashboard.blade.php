@extends('admin.layout.layout')
@section('page_title', 'Dashboard')
@section('dashboard_select', 'active')
@section('container')
    <div class="container-fluid">
        @include('admin.component.overview')
        @include('admin.component.calender')
    </div>
@endsection
