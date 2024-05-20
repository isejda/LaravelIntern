@extends('layouts.app')
@section('title', 'dashboard')
@section('content-header', 'Dashboard')
@section('content-action')
    <a href="{{route('purchases.create')}}" class="btn btn-primary mt-2 mt-sm-0 btn-icon-text">
        <i class="mdi mdi-plus-circle"></i> Add New
    </a>
@endsection
@section('content')
    <div class="row">
        Hellooo
    </div>
@endsection

