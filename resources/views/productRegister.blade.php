@extends('layouts.master')

@section('title', $title)

@section('content')
    <section class="brand-register-wrap">
        <h2>상품 등록 페이지</h2>
        <span>상품명</span> 
        <form>
            @csrf
            <input type="text" placeholder="상품명" id="product-name" name="productName">
            <span class="productName_error"></span>
            <span>카테고리</span> 
            <select id="select-categories" name="categories">
                @foreach ($usedCategories as $list)
                    <option value="{{ $list->name }}">{{ $list->name }}</option>
                @endforeach
            </select>
            <button type="button" id="add-btn">추가</button>
            <div id="categories-box"></div>
            <span>브랜드</span>
            <select id="brand" name="brand">
                @foreach ($brandList as $list)
                    <option value="{{ $list->Kname }}">{{ $list->Kname }}({{ $list->Ename }})</option>
                @endforeach
            </select>
            <span>상태</span>
            <label>
                <input type="radio" name="state" id="selling" value="0" checked="checked">
                판매중
            </label>
            <label>
                <input type="radio" name="state" id="out-of-stock" value="1">
                일시품절
            </label>
            <label>
                <input type="radio" name="state" id="sold-out" value="2">
                품절
            </label>
            <label>
                <input type="radio" name="state" id="stop-selling" value="3">
                판매중지
            </label>
            <span>정가</span>
            <input type="number" id="price" placeholder="정가" name="price">
            <span>판매가</span>
            <input type="number" id="sales" placeholder="판매가" name="sales">
            <img src="" alt="상품이미지" id="preview">
            <button>
                <input type="file" id="img-register" name="productImg">
            </button>
            <img src="../../image/1212.1212.jpg" id="img" name="image">
            <button type="button" id="register-btn">등록</button>
            <a href="{{ route('product') }}">취소</a>
        </form>
    </section>
@endsection

@push('scripts')
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        let addBtn = document.getElementById('add-btn');
        let categoriesBox = document.getElementById('categories-box');
        let selectedCategory = [];
        let registerBtn = document.getElementById('register-btn');
        var form = document.querySelector("form");
        let imgRegister = document.getElementById('img-register');
        let token;
        let metaName = 'csrf-token';
        let file;
        let link;
        let img = document.getElementById('img');
        console.log(categoriesBox.childNodes.length);
        console.log(selectedCategory.length);

        // 카테고리 등록 버튼
        addBtn.onclick = function() {
            if (categoriesBox.children.length < 3) {
                let selectCategories = document.getElementById('select-categories').value;
                if (selectedCategory.indexOf(selectCategories) != -1) {
                    alert('중복된 값이 있습니다.');
                } else {
                    selectedCategory.push(selectCategories);
                    console.log(categoriesBox);
                    let div = document.createElement('div');
                    let span = document.createElement('span');
                    let btn = document.createElement('button');
                    btn.innerText = 'X';
                    btn.onclick = function() {
                        console.log(this.previousElementSibling.innerText);
                        let delItem = this.previousElementSibling.innerText;
                        let delIdx = selectedCategory.indexOf(delItem);
                        selectedCategory.splice(delIdx,1);
                        this.parentElement.remove();
                    }
                    span.innerText = selectCategories;
                    div.appendChild(span);
                    div.appendChild(btn);
                    categoriesBox.appendChild(div);
                }
            } else {
                alert('카테고리 추가는 3개까지 가능합니다.');
            }
        }

        // 상품 등록 버튼 클릭
        registerBtn.onclick = function(e) {
            let price =document.getElementById('price').value;
            let sales = document.getElementById('sales').value;
            let productName = document.getElementById('product-name').value;
            let categoryLength = categoriesBox.children.length;
            let categories = "";
            let brand = document.getElementById('brand').value;
            let stateLength = document.getElementsByName('state').length;
            let stateBox = document.getElementsByName('state');
            let state;

            for (let i = 0; i < categoryLength; i++) {
                console.log(categoriesBox.children[i].children[0].innerText);
                if (i > 0) {
                    categories +=',' + categoriesBox.children[i].children[0].innerText;
                    console.log(categories);
                } else {
                    categories += categoriesBox.children[i].children[0].innerText;
                    console.log(categories);
                }
                
            }
            console.log(stateLength);

            for (let i = 0; i < stateLength; i++) {
                if (stateBox[i].checked === true) {
                    state = i;
                    console.log(state);
                    break;
                }
            }

            if (price <= 0 || sales <= 0) {
                alert('정가 혹은 판매가가 0 보다 작을 수 없습니다.');
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
                let formData = new FormData();
                formData.append('productName', productName);
                formData.append('categories', categories);
                formData.append('brand', brand);
                formData.append('state', state);
                formData.append('price', price);
                formData.append('sales', sales);
                formData.append('productImg', file);

                let config = {headers: {'Content-Type':'multipart/form-data'}}
                axios.post('/product/Register', formData, config)
                    .then(function(response) {
                        let res = JSON.stringify(response.data);
                        console.log(res);
                        if (res === '["success"]') {
                            location.href = '/product';
                        }
                    }).catch(err => console.log(err));
            }
        }

        // 상품 이미지 미리보기
        imgRegister.onchange = function() {
            let preview = document.querySelector('#preview');
            file = document.querySelector('#img-register').files[0];
            link = URL.createObjectURL(
                new Blob([file], {type:'text/html'})
            );
            preview.src = link;
        }
    </script>
@endpush