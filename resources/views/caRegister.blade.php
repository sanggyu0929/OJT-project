@extends('layouts.master')

@section('title', $title)

@section('content')
    <section class="caRegister-wrap">
        <h2>카테고리 등록</h2>
        <span>카테고리명</span> 
        <input type="text" placeholder="카테고리명" id="ca-name">
        <span>사용 여부</span>
        <label>
            <span>사용</span>
            <input type="radio" class="use-btns" name="use-chk" checked="checked" value="1">
        </label>
        <label>
            <span>미사용</span>
            <input type="radio" class="use-btns" name="use-chk" value="0">
        </label>
        <button type="button" id="register-btn">등록</button>
    </section>
@endsection

@push('scripts')
    <script>
        let registerBtn = document.getElementById('register-btn');
        let token;
        let metaName = 'csrf-token';
        let useChk;
        console.log(registerBtn);
        registerBtn.onclick = function() {
            let caName = document.getElementById('ca-name').value;
            let useBtns = document.getElementsByClassName('use-btns');
            console.log(caName);
            if (useBtns[0].checked === true) {
                useChk = '1';
            } else {
                useChk = '0';
            }
            let blank_pattern = /^\s+|\s+$/g;
            if( caName.replace( blank_pattern, '' ) == "" ){
                alert(' 공백만 입력되었습니다 ');
            } else {
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

                fetch("/category/Register", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Content-Type': 'application/json',
                        'Accept' : 'application/json',
                    },
                    body: JSON.stringify({'caName':caName,'useChk' : useChk})
                }).then(
                    (res) => res.json()
                ).then(function(response) {
                    let res = JSON.stringify(response);
                    console.log(res);
                    console.log(response);
                    if(res === '["success"]') {
                        location.href='/category';
                    } else if(res === '["exists"]') {
                        alert("중복된 카테고리명입니다.");
                    } else {
                        return false;
                    }
                }).catch(err => console.log(err));
            }
          
        }
    </script>
@endpush