@extends('layouts.master')

@section('title')

@section('content')
    <h2>브랜드 관리</h2>
    <section class="brand-wrap">
        <a href="{{ route('brand.register') }}">브랜드 등록</a>
        {{-- {{ dd($brandList->appends(request()->input())->links()) }} --}}
        {{-- {{ dd($brandList->page()) }} --}}
        {{-- {{ $page }} --}}
        {{-- {{ dd($brandList->nextPageUrl()) }} --}}
       
        <div class="brand-box">
            <div class="brand-box-top">
                <span>브랜드</span>
                <span>한글명</span>
                <span>영문명</span>
                <span>등록</span>
                <span>관리</span>
            </div>
            <ul class="brand-list">
                @php
                    $i = $brandList->count();
                @endphp
                @foreach ($brandList as $list)
                <li>{{ $i }}</li>
                <li id="brand-name">{{ $list['Kname'] }}</li>
                <li>{{ $list['Ename'] }}</li>
                <li>{{ $list['total'] }}</li>
                <div class="manage">
                    <a href="/brand/Edit/{{ $list->Bidx }}"><li>수정</li></a>
                    <button type="button" class="delete-btn">삭제</button>
                </div>
                @php
                    $i--;
                @endphp
                @endforeach
                <span>
                    {{-- {{ $brandList->appends(request()->input())->links('layouts.pagination') }} --}}
                    {{-- {{ $brandList->appends(request()->all())->links() }} --}}
                    {{-- {!! $brandList->appends(Request::all())->links() !!} --}}
                    {{ $brandList->links() }}
                </span>
            </ul>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        let deleteBtn = document.getElementsByClassName('delete-btn');
        let token;
        let metaName = 'csrf-token';
        for (let i = 0; i < deleteBtn.length; i++) {
            deleteBtn[i].onclick = function() {
                let brandName = this.parentNode.previousElementSibling.previousElementSibling.previousElementSibling.innerText;
                if (confirm(`${brandName} 브랜드를 삭제하시겠습니까?`) == true) {
                    function getToken(){
                        const metas = document.getElementsByTagName('meta');
                    
                        for (let i = 0; i < metas.length; i++) {
                            if (metas[i].getAttribute('name') === metaName) {
                                token = metas[i].getAttribute('content');
                            }
                        }
                    } 

                    getToken();
                  
                    fetch("/brand/Delete", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json',
                            'Accept' : 'application/json',
                        },
                        body: JSON.stringify({'Kname':brandName})
                    }).then(
                        (res) => res.json()
                    ).then(function(response) {
                        let res = JSON.stringify(response);
                        console.log(res);
                        console.log(response);
                        if(res === '["success"]') {
                            location.href='/brand';
                        } else {
                            alert(response.error);
                        }
                    }).catch(err => console.log(err));
                } else {
                    return false;
                }
            }
        }
    </script>
@endpush