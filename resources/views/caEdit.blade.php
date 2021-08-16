@extends('layouts.master')

@section('title', $title)

@section('content')
    <section class="caRegister-wrap">
        <h2>카테고리 수정</h2>
        <span>카테고리</span> 
        <input type="text" value="{{ $selectedList->Cidx }}" readonly id="ca-idx">
        <span>카테고리명</span> 
        <input type="text" value="{{ $selectedList->name }}" placeholder="카테고리명" id="ca-name">
        <span>사용 여부</span>
        @if ($selectedList->used === '사용') 
            <label>
                <span>사용</span>
                <input type="radio" class="use-btns" name="use-chk" checked="checked" value="1">
            </label>
            <label>
                <span>미사용</span>
                <input type="radio" class="use-btns" name="use-chk" value="0">
            </label>
        @else 
            <label>
                <span>사용</span>
                <input type="radio" class="use-btns" name="use-chk" value="1">
            </label>
            <label>
                <span>미사용</span>
                <input type="radio" class="use-btns" name="use-chk" value="0" checked="checked">
            </label>
        @endif
        
        <button type="button" id="Edit-btn">수정</button>
    </section>
@endsection

@push('scripts')

<script>
        let EditBtn = document.getElementById('Edit-btn');
        let token;
        let metaName = 'csrf-token';
        let useChk;
       
        EditBtn.onclick = function() {
            let caIdx = document.getElementById('ca-idx').value;
            caIdx = parseInt(caIdx);
            console.log(caIdx);
            let caName = document.getElementById('ca-name').value;
            let useBtns = document.getElementsByClassName('use-btns');
            console.log(caName);
            console.log(useBtns);
            if (useBtns[0].checked === true) {
                useChk = '1';
            } else {
                useChk = '0';
                console.log(useChk);
            }
            var blank_pattern = /^\s+|\s+$/g;
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

                fetch("/category/Edit", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Content-Type': 'application/json',
                        'Accept' : 'application/json',
                    },
                    body: JSON.stringify({'Cidx' : caIdx,'caName' : caName,'useChk' : useChk})
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