<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <title></title>
    </head>
    <body>

      <div id="app">

        <smallwin :myinfo="{{ $user }}"></smallwin>

      </div>

      <script src="/js/app.js"></script>

    </body>
</html>
