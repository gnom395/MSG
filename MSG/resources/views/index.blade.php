<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <title>Сообщения</title>
    </head>
    <body>

      <div id="app">

        <v-header></v-header>
        <br>

        <div class="container">
          <router-view :user="{{ $user ?? '' }}"></router-view>
        </div>
      </div>

      <script src="/js/app.js"></script>

    </body>
</html>
