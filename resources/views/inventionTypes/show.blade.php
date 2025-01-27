@extends('layouts.full')

@section('title', "Inventos tipo $invention_type->name")

@section('content')

    <div class="text-center">
        <a href="{{route('inventionTypes.index')}}" class="btn btn-warning">Volver al listado de inventos</a>
    </div>

    <div class="container mt-5">

        <div class="row align-items-center">
            
            <div class="col-md-6">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th class="text-center">{{ $invention_type->name }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inventions as $invention)
                            <tr>
                                <td><a href="{{route('inventions.show' , $invention->id )}}">{{$invention->name}}</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

    </div>
@endsection
