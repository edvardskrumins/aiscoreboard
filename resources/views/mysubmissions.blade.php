@extends('main')
@section('content')
<style>
   .center {
       display: block;
       margin-left: auto;
       margin-right: auto;
   }
</style>

<div class="container">
<div class="col-md-4 col-md-offset-4">
<div class="list-group">
@foreach($algorithms as $algorithm)
<div class="list-group-item">
<h2 class="text-center">{{$algorithm->name}}</h2>
<a  href="/algorithm/{{$algorithm->id}}/deleteMyAlgo"><img  class="center" src="/trash.png" /></a>
</div>
@endforeach
</div>
</div>
</div>
@endsection