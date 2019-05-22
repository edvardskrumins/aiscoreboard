@extends("main")
@section("content")
    <div class="container text-center">
    <div class="row">
{{--        <h2 class="col s6 offset-s3">Algorithm upload</h2>--}}
    </div>
    <div class="row">
        <h3 class="col s6 offset-s3">Augšupielādēt algoritmu</h3>
    </div>
    <hr>
    <div class="row">
        <form class="col-md-4 col-md-offset-4" method="post" id="uploadForm" action="/algorithm/upload" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="input-group input-group-lg">
                <span class="input-group-addon" id="sizing-addon1">Algoritma nosaukums</span>
                <input type="text" id="algorithmName" name="algorithmName" class="form-control validate" required maxlength="32" aria-describedby="sizing-addon1" required
                       oninvalid="this.setCustomValidity('Lūdzu, aizpildiet lauku, maksimālais simbolu garums - 32!')"
                       oninput="this.setCustomValidity('')">
            </div>
            <!-- <div class="input-field">
                <label for="algorithmName">Name</label>
                <input type="text" id="algorithmName" name="algorithmName" class="validate" required maxlength="255">
            </div> -->


            <div class="input-group input-group-lg">
                <span class="input-group-addon">ZIP fails</span>
                <input type="file" id="algoFile" name="algoFile" style="height:50px" accept=".zip" required>
            </div>

            <hr>
            <div class="row">
            <input type="submit" class="col-md-4 col-md-offset-4" value="Iesniegt">
            </div>
        </form>
    </div>
    </div>
    



    
@endsection
