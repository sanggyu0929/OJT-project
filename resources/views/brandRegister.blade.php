@extends('layouts.master')

@section('title', $title)

@section('content')
    <section class="brand-register-wrap">
        <h2>브랜드 등록</h2>
        <span>한글명</span> 
        <input type="text" placeholder="한글명" id="brand-Kname">
        <span>영문명</span> 
        <input type="text" placeholder="영문명" id="brand-Ename">
        <span>소개 문구</span>
        <textarea id="phrase"></textarea>
        <button type="button" id="register-btn">등록</button>
        <a href="{{ route('brand') }}">취소</a>
    </section>
@endsection

@push('scripts')
    <script>
        let registerBtn = document.getElementById('register-btn');
        let token;
        let metaName = 'csrf-token';
        registerBtn.onclick = function() {
            let Kname = document.getElementById('brand-Kname').value;
            let Ename = document.getElementById('brand-Ename').value;
            let phrase = document.getElementById('phrase').value;
            const blank_pattern = /^\s+|\s+$/g;
            const korean = /[ㄱ-ㅎㅏ-ㅣ가-힣]/g;
            const english = /[a-zA-Z]/g;
            
            if (Kname.replace(blank_pattern, '') == ""){
                alert('한글명을 입력해주세요');
            } else if (Ename.replace(blank_pattern, '') == "") {
                alert('영문명을 입력해주세요');
            } else if (!korean.test(Kname)) {
                alert('한글명에 한글만 입력해주세요');
            } else if (!english.test(Ename)) {
                alert('영문명에 영어만 입력해주세요'); 
            } else {
                function getToken(){
                    const metas = document.getElementsByTagName('meta');
                
                    for (let i = 0; i < metas.length; i++) {
                        if (metas[i].getAttribute('name') === metaName) {
                            token = metas[i].getAttribute('content');
                        }
                    }
                } 

                getToken();

                fetch("/brand/Register", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Content-Type': 'application/json',
                        'Accept' : 'application/json',
                    },
                    body: JSON.stringify({'Kname':Kname,'Ename' : Ename,'phrase' : phrase})
                }).then(
                    (res) => res.json()
                ).then(function(response) {
                    let res = JSON.stringify(response);
                    console.log(res);
                    console.log(response);
                    if(res === '["success"]') {
                        location.href='/brand';
                    } else if(res === '["Kname exists"]') {
                        alert("중복된 한글명입니다.");
                    } else if(res === '["Ename exists"]'){
                        alert("중복된 영문명입니다.");
                    } else {
                        return false;
                    }
                }).catch(err => console.log(err));
            
            }
        }
        

    </script>
@endpush