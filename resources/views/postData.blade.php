<style>
    img {
        border-radius: 50%;
    }
    p {
        word-wrap: break-word;
    }
    .post-info {
        margin: 0 -30px;
    }
    .avatar-box {
        /*margin: 0 -60px;*/
    }
    #pic {
        height: 30px;
        margin-left: 30px;
        margin-bottom: 3px;
    }


</style>
@if(sizeof($posts) == 0)
        <h1 class="text-center">Nav nevian ziņojuma!</h1>
@endif
    @foreach($posts as $post)
        <div class="row" style=height:20px >
            <div class="col-md-1">
                <img id="pic" src={{ $userNamesWithAvatar[$post->user_id][0] }}>
            </div>
                <div class="col-md-2 post-info">
                    <p >{{ $userNamesWithAvatar[$post->user_id][1] }}</p>
                </div>
                <div class="col-md-2 post-info">
                    <p>{{ $post->created_at }}</p>
                </div>
                <div class="col-md-2">
                    @if(Auth::user()->id === $post->user_id)
                        <img> <a href="/posts/delete/{{$post->id}}"><img src="/trash.png"/> </a>
                    @elseif(Auth::User()->role == '2')
                            <img> <a href="/posts/delete/{{$post->id}}"><img src="/trash.png"/> </a>
                    @endif
                </div>
        </div>
        <div class="row">
            <div class="col-md-1">
            </div>
                <div class="col-md-11">
                <p>{{ str_limit($post->body, 5000) }}</p>
                </div>
        </div>

            <hr style="margin-top:5px;">


    @endforeach

