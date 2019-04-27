@extends("main")
@section("content")
    <div class="container text-center">
    <div class="row">
        <h2 class="col s6 offset-s3">Algorithm upload</h2>
    </div>
    <div class="row">
        <h3 class="col s6 offset-s3">Upload your solution</h3>
    </div>
    <hr>
    <div class="row">
        <form class="col-md-4 col-md-offset-4" method="post" id="uploadForm" action="/algorithm/upload" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="input-group input-group-lg">
                <span class="input-group-addon" id="sizing-addon1">Algorithm name</span>
                <input type="text" id="algorithmName" name="algorithmName" class="form-control validate" required maxlength="255" aria-describedby="sizing-addon1" required>
            </div>
            <!-- <div class="input-field">
                <label for="algorithmName">Name</label>
                <input type="text" id="algorithmName" name="algorithmName" class="validate" required maxlength="255">
            </div> -->


            <div class="input-group input-group-lg">
                <span class="input-group-addon">.zip file</span>
                <input type="file" id="algoFile" name="algoFile" style="height:50px" required>
            </div>

            <hr>
            <div class="row">
            <input type="submit" class="col-md-4 col-md-offset-4" value="Upload">
            </div>
        </form>
    </div>
    </div>
    



    
@endsection
