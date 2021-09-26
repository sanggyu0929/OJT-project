@extends('layouts.master')

@section('title', $title)

@section('content')
    <section class="category-wrap">
        <h2>카테고리 페이지</h2>
        <a href="{{ route('category.register') }}" class="register-btn">카테고리 등록</a>
        <div class="category-box">
            <div class="category-box-top">
                <span>카테고리</span>
                <span>카테고리 명</span>
                <span>사용여부</span>
                <span>관리</span>
            </div>
            <ul class="category-list">
                @php
                    $i = $categoryList->count();
                @endphp
                @foreach ($categoryList as $list)
                <li>{{ $i }}</li>
                <li>{{ $list['name'] }}</li>
                <li>{{ $list['used'] }}</li>
                <a href="/category/Edit/{{ $list->Cidx }}"><li>수정</li></a>
                @php
                    $i--;
                @endphp
                @endforeach
                {{-- <li>3</li>
                <li>여성 원피스</li>
                <li>사용</li>
                <a href=""><li>수정</li></a> --}}
            </ul>
        </div>
    
    </section>
@endsection