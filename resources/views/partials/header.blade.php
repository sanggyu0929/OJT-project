<header>
    <ul class="menu-box">
        <li><a href="{{ route('category') }}">카테고리 관리</a></li>
        <li><a href="{{ route('brand') }}">브랜드 관리</a></li>
        <li><a href="{{ route('product') }}">상품 관리</a></li>       
    </ul>
    <ul class="util-box">
        @if (session()->has('LoggedUser'))
            <li>{{ session()->get('LoggedName') }}님</li>
            <a href="{{ route('logout') }}">logout</a>
        @else
        <li><a href="{{ route('login') }}">로그인</a></li>
        <li><a href="{{ route('sign-up') }}">회원가입</a></li>
        @endif
    </ul>
</header>