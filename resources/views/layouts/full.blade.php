@extends('layouts.basic')

@section('title', 'Layout Completo')

@section('nav') 
    @include('partials.nav')
@endsection

@section('content') 
    @yield('extra-content') 
@endsection