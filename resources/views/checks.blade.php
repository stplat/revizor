@extends('layouts.app')
@section('content')
  <view-check-list
    :initial-data="{{ $data }}"
    route-name="{{ $routeName }}"
  ></view-check-list>
@endsection
