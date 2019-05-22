@extends("main")
@section("content")
<div class="container">
    <div class="row">
        <h2 class="text-center">Pieejamie testa scenāriji</h2>
    </div>

    <div class="text-center">
    
<div class="list-group">
<div class="row">
    <div class="col-md-6 col-md-offset-3">
            @if(sizeof($data_entries) > 0)
                 @foreach ($data_entries as $data_entry)
                    <div class="list-group-item">
                        <h3>{{ $data_entry->name }}</h3>
                        <a href="/data/{{ $data_entry->id }}/download/slots" class="waves-effect waves-light btn blue">
                            <button type="button" class="btn btn-success"> Lejupielādēt reklāmas pauzes</button>
                        </a>
                        <a href="/data/{{ $data_entry->id }}/download/ads" class="waves-effect waves-light btn blue">
                            <button type="button" class="btn btn-success"> Lejupielādēt reklāmas</button>
                        </a>
                        @if(Auth::User()->role == '2')
                            <a href="/data/{{ $data_entry->id }}/delete" class="waves-effect waves-light btn red">
    {{--                            <button type="button" class="btn btn-danger"> Delete </button>--}}
                                <img src="/trash.png"/>
                            </a>
                        @endif
                        <a href="/data/{{ $data_entry->id }}/showalgorithms" class="waves-effect waves-light btn green">
                            <button type="button" class="btn btn-primary"> Testētie algoritmi</button>
                        </a>
                    </div>
                @endforeach
                @else
                <h3>No entries available</h3>
            
            @endif    
        </div>
  
</div>
</div>
    </div>
    </div>
       
    
    
@endsection
