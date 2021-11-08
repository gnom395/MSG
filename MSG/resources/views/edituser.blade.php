@extends('layouts.app')

@section('content')

  <div class="container">

<Chat :user="{{$user}}"></Chat>

{{ $user }}
  </div>

@endsection
