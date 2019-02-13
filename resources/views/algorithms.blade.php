@extends("main")
@section("content")
    <div class="row">
        <h2 class="col s6 offset-s3">Algorithm upload</h2>
    </div>
    <div class="row">
        <h5 class="col s6 offset-s3">Upload your solution</h5>
    </div>
    <div class="row">
        <form class="col s6 offset-s3" method="post" id="uploadForm" action="/algorithm/upload" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="input-field">
                <label for="algorithmName">Name</label>
                <input type="text" id="algorithmName" name="algorithmName" class="validate" required maxlength="255">
            </div>
            <div class="file-field input-field">
                <div class="btn">
                    <span>.zip file</span>
                    <input id="algoFile" name="algoFile" type="file">
                </div>
                <div class="file-path-wrapper">
                    <input id="algorithmFile" name="algorithmFile" class="file-path validate" type="text" required pattern=".+\.zip">
                </div>
            </div>
            <input type="submit" class="btn col s12" value="Upload">
        </form>
    </div>
    <div class="row">
        <h2 class="col s6 offset-s3">Algorithm submissions</h2>
    </div>
    <div class="row">
        <ul class="col s4 offset-s4">
            @if(sizeof($algorithms))
                @foreach($algorithms as $algorithm)
                    <li>
                        <h3>{{ $algorithm->name }}</h3>
                        <a class="waves-effect waves-light btn" href="/algorithm/{{ $algorithm->id }}/">Tests</a>
                        <a class="waves-effect waves-light btn red" href="/algorithm/{{ $algorithm->id }}/delete">Delete</a>
                    </li>
                    <hr>
                @endforeach
            @else
                <h3>No algorithms submitted yet!</h3>
            @endif
        </ul>
    </div>
@endsection
