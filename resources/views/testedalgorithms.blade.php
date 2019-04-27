@extends("main")
@section("content")

    <div class="container">
    <div class="row">
        <h2 class="text-center">Available test data entries</h2>
    </div>
                 @foreach ($data_entries as $data_entry)
               <li class="list-group-item">
                <h3>{{ $data_entry->name }}</h3>
                <a href="/data/{{ $data_entry->id }}/download/slots" class="btn btn-success">Download Slots</a>
                <a href="/data/{{ $data_entry->id }}/download/ads" class="btn btn-success">Download Ads</a>
                <a href="/data/{{ $data_entry->id }}/delete" class="btn btn-danger">Delete</a>
                @if($test_data_id == $data_entry->id)


                <table class="table">
            <thead>
            <tr>
            <th scope ="col">Name</th>
            <th scope ="col">Score</th>
            <th scope ="col">Created at</th>
            <th scope ="col">Updated at</th>
            </tr>
            </thead>
            <tbody>
            @for($i = 0; $i < sizeof($tests); $i++)
            <tr>
                <td>{{ $algorithm_names_by_id[$i][0] }}</td>
                <td>{{ $tests[$i][1] }}</td>
                <td>{{ $tests[$i][2] }}</td>
                <td>{{ $tests[$i][3] }}</td>
            </tr>
           @endfor
            </tbody>
            </table>

                    <h2> Not tested yet: </h2>
                    @foreach($not_tested as $no_test)
                        <h3> {{ $not_run_algorithm_names_by_id[$no_test->id] }}</h3>
                        <a href="/data/{{ $data_entry->id }}/showalgorithms/{{ $no_test->id }}" class="btn btn-primary"> Test Now!</a>
                    @endforeach
        @else
                <a href="/data/{{ $data_entry->id }}/showalgorithms" class="btn btn-primary">Already tested algorithms</a>
                @endif
                @endforeach

               
          
     </li>
  
            
      
    
      </div> 
    
@endsection