@extends('main')
@section('content')

<div class="container">
<div class="col-md-4 col-md-offset-4">
<div class="list-group">
@foreach($algorithms as $algorithm)
<div class="list-group-item">
<h2 class="text-center">{{$algorithm->name}}</h2>
<a href="/algorithm/{{$algorithm->id}}/delete"><button class="btn btn-danger col-md-offset-4">Delete</button></a>
</div>
@endforeach
</div>
</div>
</div>
@endsection