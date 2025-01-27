@extends('layouts.full')

@section('title', 'Invento')

@section('content')

    <h2 class="text-center mb-4">Listado de todos los inventos:</h2>
    <ul>
        @foreach($inventions as $invention)
            <li><a href=" {{ route('inventions.show' , $invention->id) }}">{{$invention->name}}</a></li>
        @endforeach
    </ul>

@endsection