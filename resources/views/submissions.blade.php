@extends('main')
@section('content')
<div class="container-fluid">
    <div class="text-center">
        <h1>Iesūtītie algoritmi</h1>
    </div>
    
    <ul class="list-group col-md-3">
            @if(sizeof($algorithms)>0)
                @foreach($algorithms as $algorithm)
                
                    <li class="list-group-item">
                        <h3 class="text-center">{{ $algorithm->name }}</h3>
                            <div class="text-center">
                                <a class="waves-effect waves-light btn" href="/algorithm/{{ $algorithm->id }}/">
                                    <button type="button" class="btn btn-primary"> Testēt </button>
                                </a>
                                @if(Auth::User()->role == '2')
                                        <img> <a class="waves-effect waves-light btn" href="/algorithm/{{ $algorithm->id }}/delete"><img src="/trash.png"/> </a>
                                    </a>
                                @endif
                            </div>
                    </li>
                    <hr>
                @endforeach

            @else
                <h3>No algorithms submitted yet!</h3>
            @endif
    </ul>
</div>
@endsection