@extends('layouts.master')

@section('title', $title)

@section('content')
    <section class="login-wrap">
        <div class="login-box">
            <div class="login-box-title">
                <h3>로그인</h3> 
            </div>
            <form action="" method="POST">
                @csrf
                <h6 class="login-form-text">이메일</h6>
                <label>
                    <div class="form-text">
                        <input type="text" 
                            id="login-email" 
                            name="login-email"
                            placeholder="이메일을 입력하세요"
                            value=""/>
                    </div>
                </label>
                <span class="text-danger"></span>
                <h6 class="login-form-text">비밀번호</h6>
                <label>
                    <div class="form-text">
                        <input type="password"
                            id="login-pw"
                            name="login-pw"
                            placeholder="비밀번호를 입력하세요"/>
                    </div>
                </label>
                <span class="text-danger"></span>
                <span class="text-danger"></span>
                <label id="form-chk">
                    <input type="checkbox" id="remember-email">
                    <span>이메일 기억하기</span>
                </label>
                <div class="form-bottom">
                    <button type="button" id="login-btn">로그인</button>
                    <a href="{{ route('sign-up') }}">관리자 등록하기</a>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        let formChk = document.getElementById('form-chk');
        let inputEmail = document.getElementById('login-email');
    
        let rememberEmail = document.getElementById('remember-email');
        let loginClick = document.getElementById('login-btn');
        let token;
        let metaName = 'csrf-token';
        let textDanger = document.getElementsByClassName('text-danger');
        let emailValue;

        // 쿠키에 있는 데이터 값 넣기
        inputEmail.value = getCookie('email');

        var decodedCookie1 = decodeURIComponent(document.cookie).split(';');
        
        console.log(decodedCookie1);
        // 쿠키 가져오기
        function getCookie(cname) {

        var name = cname + "=";

        var decodedCookie = decodeURIComponent(document.cookie);

        var ca = decodedCookie.split(';');

        for(var i = 0; i < ca.length; i++) {

        var c = ca[i];
        console.log(c);

        while (c.charAt(0) == ' ') {

            c = c.substring(1);
            console.log(c);
        }

        if (c.indexOf(name) == 0) {
            console.log(c.indexOf(name));
            return c.substring(name.length, c.length);

        }

        }

        return "";

        }
        var username = getCookie('email');
        console.log(username);


        //쿠키 체크
        console.log(formChk);
      

        loginClick.onclick = function(e) {
            e.preventDefault();
            let loginEmail = document.getElementById('login-email').value;
            let loginPw = document.getElementById('login-pw').value;




            // let rememberEmailChk = rememberEmail.getAttribute("checked");
            // console.log(rememberEmailChk);
            // 이메일 저장하기 체크
            if (rememberEmail.checked === true) {
                console.log('checked');
                console.log(emailValue);
                emailValue = inputEmail.value;
                setCookie('email',emailValue,{secure: true, 'max-age': 3600});
            } else {
                console.log('false');
            }

            
            // 쿠키 세팅
            function setCookie(name, value, options = {}) {
                options = {
                    path: '/',
                    ...options
                };

                if (options.expires instanceof Date) {
                    options.expires = options.expires.toUTCString();
                }

                let updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);

                for (let optionKey in options) {
                    updatedCookie += "; " + optionKey;
                    let optionValue = options[optionKey];
                    if (optionValue !== true) {
                        updatedCookie += "=" + optionValue;
                    }
                }
                document.cookie = updatedCookie;
            }


            // meta에 있는 csrf토큰 값 가져오기
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

            // fetch('/login/post', {
            //     method: 'POST',
            //     credentials: 'include',
            //     headers : {
            //         'X-CSRF-TOKEN': token,
            //         'Content-Type': 'application/json',
            //         'Accept': 'application/json'
            //     },
            //     body: JSON.stringify({'email':loginEmail,'pw' : loginPw})
            // }).then(response => console.log(response))
            // .then((result) => {
            //     console.log(result);
            // });

            //'Content-Type': 'application/json',
            // Ajax 요청
            fetch("/login/post", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json',
                    'Accept' : 'application/json',
                },
                body: JSON.stringify({'email':loginEmail,'pw' : loginPw})
            }).then(
                (res) => res.json()
            ).then(function(response) {
                let res = JSON.stringify(response);
                console.log(res);
                console.log(response);
                // 에러 출력 (@error를 사용하여 blade에서 에러 출력 예정)
                if (!(response.errors)) {             
                    if (res === '["success"]') {
                        location.href = '/';
                    } else {
                        console.log('no error');
                        textDanger.innerText = "";
                        textDanger[0].style.display = "none";
                        textDanger[1].style.display = "none";
                        textDanger[2].innerText = response.error;
                        textDanger[2].style.display = "block";
                    } 
                } else if (response.errors.email && response.errors.pw) {
                    textDanger[0].style.display = "block";
                    textDanger[1].style.display = "block";
                    textDanger[0].innerText = response.errors.email[0];
                    textDanger[1].innerText = response.errors.pw[0];
                } else if (response.errors.email) {
                    textDanger[0].innerText = response.errors.email[0];
                    textDanger[0].style.display = "block";
                    textDanger[1].style.display = "none";
                } else if (response.errors.pw) {
                    textDanger[1].innerText = response.errors.pw[0];
                    textDanger[0].style.display = "none";
                    textDanger[1].style.display = "block";
                } else {
                    textDanger.innerText = "";
                    textDanger[0].style.display = "none";
                    textDanger[1].style.display = "none";
                    textDanger[2].style.display = "none";
                }
            }).catch(err => {
                console.log(err);
            });
           
        }
    </script>
@endpush