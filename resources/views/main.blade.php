<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js" type="text/javascript"></script>
    </head>
    <body>
        <nav>
            <div class="nav-wrapper">
                <a href="/data/" class="brand-logo">AlgoTester</a>
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a href="/data/">Data</a></li>
                    <li><a href="/algorithm/">Upload solution</a></li>
                </ul>
            </div>
        </nav>
        @yield("content")
    </body>
</html>
