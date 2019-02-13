@extends("main")
@section("content")
    <div class="row">
        <h2 class="col s6 offset-s3">Available tests</h2>
    </div>
    <div class="row">
        <h5 class="col s6 offset-s3">Test your solution</h5>
    </div>
    <div class="row">
        <ul class="col s4 offset-s4">
            @if(sizeof($data_entries) > 0)
                @foreach($data_entries as $entry)
                    <li>
                        <h3>{{ $entry->name }}</h3>
                        <a href="/algorithm/{{ $algorithm_id }}/test/{{ $entry->id }}" class="btn">Test</a>
                        @if($run_stats[$entry->id]["id"] != 0)
                            <span>Status: {{ $run_stats[$entry->id]["status"] }}</span>
                            @if($run_stats[$entry->id]["status"] == "success")
                                <a href="/algorithm/{{ $run_stats[$entry->id]["id"] }}/output/" class="btn">Download output</a>
                                <span>Score: {{ $run_stats[$entry->id]["score"] }}</span>
                            @endif
                        @else
                            <span>Status: Test not run</span>
                        @endif
                    </li>
                    <hr>
                @endforeach
            @else
                <h3>No tests available</h3>
            @endif
        </ul>
    </div>
@endsection