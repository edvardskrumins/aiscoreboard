
@extends('main')
@section('content')
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>

<style type="text/css">

    textarea{
        resize: none;
        width: 100%;
        margin-top: 10px;
    }

    .ajax-load{

        background: #e1e1e1;

        padding: 10px 0px;

        width: 100%;

    }

    .postsContainer {

        width: 50%;
        margin: auto;
    }



</style>


    <div class="container">
        <div class="row">
            <h2 class="text-center">Ziņojuma dēlis</h2>
            <hr>
        </div>

        <div class="col-md-5 col-md-offset-4">
            <form  id="dataForm" action="/posts/create/" method="post">
                {{ csrf_field() }}



                <textarea type="text" placeholder="Ievadiet savu ziņojumu..." class="form-control"  name="body" required="required"
                          class="validate"
                           maxlength="2000" aria-describedby="sizing-addon1"
                          oninvalid="this.setCustomValidity('Lūdzu, aizpildiet ziņojuma teksta lauku!')"
                          oninput="this.setCustomValidity('')"
                ></textarea>





                <hr>
                <div class="row">
                    <input type="submit" class="col-md-4 col-md-offset-3 text-center" value="Post">
                </div>

            </form>
        </div>

    </div>

<!-- <div class="form-group"> -->










            <div class="container" id="post-data">
                @include('postData')
            </div>



        <div class="ajax-load text-left" style="display:none">
            <p><img src="http://demo.itsolutionstuff.com/plugin/loader.gif">Loading More posts</p>
        </div>


        <script type="text/javascript">
            var page = 1;
            $(window).scroll(function() {
                if($(window).scrollTop() + $(window).height() >= $(document).height()) {
                    page++;
                    loadMoreData(page);
                }
            });


            function loadMoreData(page){
                $.ajax(
                    {
                        url: '?page=' + page,
                        type: "get",
                        beforeSend: function()
                        {
                            $('.ajax-load').show();
                        }
                    })

                    .done(function(data)
                    {
                        if(data.html == " "){
                            $('.ajax-load').html("No more records found");
                            return;
                        }

                        $('.ajax-load').hide();
                        $("#post-data").append(data.html);
                    })

                    .fail(function(jqXHR, ajaxOptions, thrownError)
                    {
                        alert('server not responding...');
                    });
            }

        </script>















        {{--<div>--}}

            {{--@if(sizeof($posts)>0)--}}
                {{--@foreach($posts as $post)--}}

                    {{--<li class="list-group-item">--}}
                        {{--<h3 class="text-center">{{ $post->title}}</h3>--}}
                        {{--<div class="text-center">--}}
                           {{--<p>{{ $post->body }}</p>--}}
                        {{--</div>--}}
                    {{--</li>--}}
                    {{--<hr>--}}
                {{--@endforeach--}}

            {{--@else--}}
                {{--<h3>No algorithms submitted yet!</h3>--}}
            {{--@endif--}}
                {{--{{ $posts->links() }}--}}
        {{--</div>--}}





@endsection
