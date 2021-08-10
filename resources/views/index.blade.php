@extends('layouts.master')

@section('title')

@section('content')
    <h2>hello,{{session('email')}}</h2>
    @if ($LoggedUserInfo)
        {{ $LoggedUserInfo['email'] }}
        {{ $LoggedUserInfo['name'] }}
        <a href="{{ route('logout') }}">logout</a>
    @else
        <a href="{{ route('logout') }}">logout</a>
    @endif
    
    

@endsection