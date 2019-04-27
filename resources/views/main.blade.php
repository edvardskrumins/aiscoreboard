<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css"> -->
        <!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js" type="text/javascript"></script> -->
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js" type="text/javascript"></script> -->
        <script src="/js/app.js"></script>
        <link rel="stylesheet" href="/css/app.css">
        <style>
        #myBtn {
  display: none;
  position: fixed;
  bottom: 20px;
  right: 30px;
  z-index: 99;
  font-size: 18px;
  border: none;
  outline: none;
  background-color: red;
  color: white;
  cursor: pointer;
  padding: 15px;
  border-radius: 4px;
}

#myBtn:hover {
  background-color: #555;
}
#appName{
  text-decoration: none;
  color: #0080F0;
}
#navbar_logo{
  height: auto;
  display: inline-block;
  width: 150px; 
  position: absolute;
}
#nav-link {
    display:inline-block;
    position: absolute;
    text-decoration: none;
    color: #0080F0;
    left: 60px;
    top: 12px;
}
        </style>
    
    </head>
    <body>


    <nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
    
    <ul class="nav navbar-nav">
    <img src="/logo.png" id="navbar_logo">

      <h3><a id="nav-link"href="/">AIscoreboard</a></h3>

    </ul>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    
      
      <ul class="nav navbar-nav navbar-right">
      @guest   
                            <li><a href="/data/">Data</a></li>
                            <li><a href="/submissions/">Algorithm submissions</a></li>
                            <html lang="en">
                            <li><img src="/google.jpg" height="40" width="40"></li> 
                            <li><a href="{{ route('login.provider', 'google') }}" 
            class="btn btn-secondary">{{ __('Sign in with Google') }}</a></li>
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
    
            </a>
  
   
            



            
        @else
            <li><a href="/data/">Data</a></li>
            <li><a href="/submissions/">Algorithm submissions</a></li>
            <li><a href="/newtest/">Add new Test Data</a></li>
            <li><a href="/algorithm/">Upload solution</a></li>
            

        <li class="dropdown">
            
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    {{ Auth::user()->name }} <span class="caret"></span>
                </a>

                <ul class="dropdown-menu">
                    <li><a href="/myalgorithms"> My submissions </a></li>
                    <li><a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a></li>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </ul>
            </li>
            
                       
        @endguest
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<button onclick="topFunction()" id="myBtn" title="Go to top">Back to the top</button>





        @yield("content")
        <script>
// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    document.getElementById("myBtn").style.display = "block";
  } else {
    document.getElementById("myBtn").style.display = "none";
  }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}
</script>
    </body>
</html>
