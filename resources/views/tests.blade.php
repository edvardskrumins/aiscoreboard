@extends("main")
@section("content")
<div class="container-fluid">
    <div class="text-center">

        <h1>Available tests</h1>
    </div>
   
    <hr>
    <ul class="list-group col-md-3">
            @if(sizeof($algorithms)>0)
                @foreach($algorithms as $algorithm)
                
                    <li class="list-group-item">
                        <h3 class="text-center">{{ $algorithm->name }}</h3>
                            <div class="text-center">
                                <a href="/algorithm/{{ $algorithm->id }}/">
                                    <button type="button" class="btn btn-primary"> Test </button>
                                </a>
                            </div>
                    </li>
                    <hr>
                @endforeach

            @else
                <h3>No algorithms submitted yet!</h3>
            @endif
    </ul>

<div class="row">
<div class="col-md-4 col-md-offset-2">
<h2 class="text-center"> {{ $chosen_algorithm->name }}</h2>

<a href="/algorithm/{{ $chosen_algorithm->id }}/testAll"> <button class="btn btn-warning text-center">TEST ALL </button> </a> 

<hr>

    <div class="list-group">
    
            @if(sizeof($data_entries) > 0)
                 @foreach ($data_entries as $entry)
                    <div class="list-group-item">
                        <h3>{{ $entry->name }}</h3>
                        <a href="/algorithm/{{ $algorithm_id }}/test/{{ $entry->id }}" class="btn">
                            <button type="button" class="btn btn-primary"> Test </button>
                        </a>
                        @if($run_stats[$entry->id]["id"] != 0)
                            <span>Status: {{ $run_stats[$entry->id]["status"] }}</span>
                            @if($run_stats[$entry->id]["status"] == "success")
                                <a href="/algorithm/{{ $run_stats[$entry->id]["id"] }}/output/">
                                <button type="button" class="btn btn-default"> Download output </button>
                                </a>
                                <div>
                                <span> <strong>Score:</strong> {{ $run_stats[$entry->id]["score"] }}</span>
                                </div>
                            @endif
                        @else
                            <span>Status: Test not run</span>
                        @endif
                    </div>
                    <hr>
                @endforeach
                @else
                <h3>No entries available</h3>
            
            @endif    
            </div>
    </div>
</div>


</div>
@endsection
