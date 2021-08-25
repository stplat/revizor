@extends('layouts.app', ['title' => 'Настройки', 'subtitle' => '', 'subtitle_link' => ''])
@section('content')
  <settings :initial-data="{{$data}}"></settings>
@endsection
