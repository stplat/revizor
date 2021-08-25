@extends('layouts.app')
@section('content')
    <view-recognition route-name="{{ $routeName }}" :initial-data="{{ $data }}">
    </view-recognition>
@endsection
