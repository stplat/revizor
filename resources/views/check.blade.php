@extends('layouts.app')
@section('content')
  <view-check
    :initial-data="{{ $data }}"
    route-name="{{ $routeName }}"
  ></view-check>
@endsection
