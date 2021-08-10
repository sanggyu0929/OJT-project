@extends('layouts.master')

@section('title', $title)

@section('content')
    Sign up Page
    <form action="" method="POST">
        @csrf
        <h6 class="sign-form-text">이메일</h6>
        <label>
            <div class="form-text">
                <input type="text" 
                    id="sign-email" 
                    name="sign-email"
                    placeholder="이메일을 입력하세요"/>
            </div>
            <span class="error-text email">이메일 형식이 올바리지 않습니다.</span>
            <span class="error-text email">중복된 이메일 입니다.</span>
            <span class="text-danger">@error('email'){{ $message }} @enderror</span>
        </label>
        <h6 class="sign-form-text">이름</h6>
        <label>
            <div class="form-text">
                <input type="text"
                    id="sign-name"
                    name="sign-name"
                    placeholder="이름을 입력하세요"/>
            </div>
            <span class="error-text">이름을 입력해주세요.</span>
            @if ($errors->has("name"))
                    <span class="text-danger">{{ $errors->first("name") }}</span>
            @endif
        </label>
        <h6 class="sign-form-text">비밀번호</h6>
        <label>
            <div class="form-text">
                <input type="password"
                    id="sign-pw"
                    name="sign-pw"
                    placeholder="비밀번호를 입력하세요"/>
            </div>
            <span class="error-text">비밀번호를 입력해주세요.</span>
            @if ($errors->has("pw"))
                    <span class="text-danger">{{ $errors->first("pw") }}</span>
            @endif
        </label>
        <h6 class="sign-form-text">비밀번호 확인</h6>
        <label>
            <div class="form-text">
                <input type="password"
                    id="sign-pw-chk"
                    name="sign-pw-chk"
                    placeholder="비밀번호를 입력하세요"/>
            </div>
            <span id="nomatch" class="error-text">비밀번호가 다릅니다.</span>
        </label>
        <div class="form-bottom">
            <button type="button" id="sign-btn">회원가입</button>
            <a href="{{ route('login') }}">로그인 하기</a>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        let signUpClick = document.getElementById('sign-btn');
        let inputEmail = document.getElementById('sign-email');
        let token;
        let metaName = 'csrf-token';
        let emailChk = document.querySelectorAll('.error-text.email');
        console.log(emailChk);
        //이메일 폼 입력 시 이벤트
        inputEmail.onkeyup = function() {
            let email = document.getElementById('sign-email').value;
            console.log('push key');
            function getToken(){
                const metas = document.getElementsByTagName('meta');
            
                for (let i = 0; i < metas.length; i++) {
                    if (metas[i].getAttribute('name') === metaName) {
                        // return metas[i].getAttribute('content');
                        token = metas[i].getAttribute('content');
                    }
                }
            } 
            getToken();
            console.log(token);
            let ajaxSetTimeout;
            clearTimeout(ajaxSetTimeout);
            ajaxSetTimeout = setTimeout(function() {
                fetch("/sign-up/emailChk", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json',
                    "Accept" : "application/json"
                },
                body: JSON.stringify({'email': email})
                }).then(
                    res => res.json()
                ).then(function(response) {
                    let res = JSON.stringify(response)
                    console.log(res);
                    if(res === '["exists"]') {
                        emailChk[1].className += ' active';
                    } else {
                        emailChk[1].classList.remove('active');
                    }
                }).catch(err => console.log(err));
            },1000);
            
        }
        // 회원가입 버튼 클릭 이벤트
        signUpClick.onclick = function(e){
            e.preventDefault();
            let email = document.getElementById('sign-email').value;
            let name = document.getElementById('sign-name').value;
            let pw = document.getElementById('sign-pw').value;
            let pwChk = document.getElementById('sign-pw-chk').value;
            let nomatch = document.getElementById('nomatch');

            function getToken(){
                    const metas = document.getElementsByTagName('meta');
                
                    for (let i = 0; i < metas.length; i++) {
                    if (metas[i].getAttribute('name') === metaName) {
                        // return metas[i].getAttribute('content');
                        token = metas[i].getAttribute('content');
                    }
                }
            } 
            
            getToken();
            
            let postUrl = '/sign-up/post';
                // let opts = {
                //     method: 'POST',
                //     body: `{
                //         email: ${email},
                //         name: ${name},
                //         pw: ${pw}
                //     }`,
                //     headers: {
                //         "X-CSRF-TOKEN" : _token,
                //         "Content-Type": "application/json",
                //         "Accept" : "application/json"
                //     }
                // };
                // fetch('/sign-up/post',opts).then(function(response) {
                //     console.log(response.json());
                // });
                // Ajax 요청
                fetch("/sign-up/post", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Content-Type': 'application/json',
                        "Accept" : "application/json"
                    },
                    body: JSON.stringify({'email': email,'name': name,'pw' : pw,'pwChk' : pwChk})
                }).then(
                    res => res.json()
                ).then(function(response) {
                    let res = JSON.stringify(response)
                    console.log(res);
                    if(res === '["success"]') {
                        location.href='/login';
                    }
                }).catch(err => console.log(err));
            
            console.log(email, name, pw, pwChk);
        }
    </script>
@endpush