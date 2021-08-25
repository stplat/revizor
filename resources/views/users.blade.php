@extends('layouts.app')
@section('content')
  <view-user
    :initial-data="{{ $data }}"
    route-name="{{ $routeName }}"
  ></view-user>
@endsection
