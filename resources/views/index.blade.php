@extends('layouts.master')

@section('title')

@section('content')
    <section class="main-wrap">
        <h2>메인 페이지</h2>
        @if ($LoggedUserInfo)
            {{ $LoggedUserInfo['email'] }}
            {{ $LoggedUserInfo['name'] }}
            <a href="{{ route('logout') }}">logout</a>
        @else
            <a href="{{ route('logout') }}">logout</a>
        @endif
    </section>
    
    

@endsection