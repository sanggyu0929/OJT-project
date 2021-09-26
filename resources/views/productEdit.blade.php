@extends('layouts.master')

@section('title', $title)

@section('content')
    <section class="product-edit-wrap">
        <h2>상품 수정</h2>
        <form action="{{ route('product.edit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <span>상품번호</span> 
            <input type="text" value="{{ $selectedList->Pidx }}" readonly id="product-idx" name="productIdx">
            <span>상품명</span> 
            <input type="text" value="{{ $selectedList->name }}" placeholder="상품명" id="product-name" name="productName">
            <span>카테고리</span>
            <select id="select-categories" name="categories">
                @foreach ($usedCategories as $list)
                    <option value="{{ $list->name }}"{{ ($selectedList->selectedCategories == $list->name) ? 'selected' : ''}}>{{ $list->name }}</option>
                @endforeach
            </select>
            {{-- @php
                $category = explode(',', $selectedCategories->selectedCategories);
            @endphp --}}
            @foreach ($selectedCategories as $item)
                @php
                    $category = $item->selectedCategories;
                @endphp
            @endforeach
            @php
                $explodedCategory = explode(',',$category);
            @endphp
            <button type="button" id="add-btn">추가</button>
            <div id="categories-box">
                @foreach ($explodedCategory as $item)
                    <div>
                        <span>{{ $item }}</span>
                        <button type="button" class="del-btn">X</button>
                    </div>
                @endforeach
            </div>
            <div id="product-box"></div>
                <span>브랜드</span>
                <select id="brand" name="brand">
                    @foreach ($brandList as $list)
                        <option value="{{ $list->Kname }}"{{ ($selectedList->selectedBrand == $list->Kname) ? 'selected' : ''}}>{{ $list->Kname }}({{ $list->Ename }})</option>
                    @endforeach
                </select>
                <span>상태</span>
                @php
                    $stateList = ['판매중','일시품절','품절','판매중지'];
                @endphp
                @for ($i = 0; $i < 4; $i++)
                    <label>
                        <input type="radio" name="state" value="{{$i}}" {{ ($selectedList->state == $stateList[$i]) ? 'checked' : '' }}>
                        {{ $stateList[$i] }} 
                    </label>
                @endfor
                    
    
                {{-- <label>
                    <input type="radio" name="state" id="selling" 
                    @if ($selectedList->state == '판매중') 
                        checked 
                    @endif />
                    판매중
                </label>
                <label>
                    <input type="radio" name="state" id="out-of-stock"
                    @if ($selectedList->state == '일시품절')
                        checked
                    @endif />
                    일시품절
                </label>
                <label>
                    <input type="radio" name="state" id="sold-out"
                    @if ($selectedList->state == '품절')
                        checked
                    @endif />
                    품절
                </label>
                <label>
                    <input type="radio" name="state" id="stop-selling"
                    @if ($selectedList->state == '판매중지')
                        checked
                    @endif />
                    판매중지
                </label> --}}
                <span>정가</span>
                <input type="number" id="price" placeholder="정가" name="price" value="{{ $selectedList->price }}">
                <span>판매가</span>
                <input type="number" id="sales" placeholder="판매가" name="sales" value="{{ $selectedList->sales }}">
                <img src="../../image/{{ $selectedList->Pidx }}.{{ $selectedList->extension }}" alt="상품이미지" id="preview">
                <button>
                    <input type="file" id="img-register" name="productImg">
                </button>
            
            <button type="button" id="edit-btn">수정</button>
            {{-- <button type="submit" id="edit-btn">수정</button> --}}
        </form>
    </section>
@endsection

@push('scripts')
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        let editBtn = document.getElementById('edit-btn');
        let imgRegister = document.getElementById('img-register');
        let token;
        let metaName = 'csrf-token';
        let link;
        let img = document.getElementById('img');
        let addBtn = document.getElementById('add-btn');
        let productIdx = document.getElementById('product-idx').value;
        let categoriesBox = document.getElementById('categories-box');
        let selectedCategory = [];
        let delBtn = document.getElementsByClassName('del-btn');
        categoriesBox.children

        for(let i = 0; i < categoriesBox.children.length; i++) {
            selectedCategory.push(categoriesBox.children[i].children[0].innerText);
        }
        console.log(selectedCategory);
        for(let j = 0; j < delBtn.length; j++) {
            delBtn[j].onclick = function() {
                console.log(this.parentElement);
                let delItem = this.previousElementSibling.innerText;
                console.log(delItem);
                console.log(selectedCategory);
                let delIdx = selectedCategory.indexOf(delItem);
                console.log(delIdx);
                selectedCategory.splice(delIdx,1);
                console.log(selectedCategory);
                this.parentElement.remove();
            }
        }
        
        // 카테고리 등록 버튼
        addBtn.onclick = function() {
            console.log(categoriesBox.children.length);
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

        editBtn.onclick = function(e) {
            e.preventDefault();
            console.log('click');
            let price =document.getElementById('price').value;
            let sales = document.getElementById('sales').value;
            let productName = document.getElementById('product-name').value;
            let categoryLength = categoriesBox.children.length;
            let categories = "";
            let brand = document.getElementById('brand').value;
            let stateLength = document.getElementsByName('state').length;
            let stateBox = document.getElementsByName('state');
            let state;
            let file = document.querySelector('#img-register').files[0];

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
                console.log(file);
                // console.log(link);
                console.log(img);
                // axios({
                // method: 'POST',
                // url: '/product/Edit',
                // data: {
                //     productImg : file
                // }
                // })
                // .then(function (response) {
                //     console.log(response);
                // });

                let formData = new FormData();
                formData.append('productIdx', productIdx);
                formData.append('productName', productName);
                formData.append('categories', categories);
                formData.append('brand', brand);
                formData.append('state', state);
                formData.append('price', price);
                formData.append('sales', sales);
                formData.append('productImg', file);

                let config = {headers: {'Content-Type':'multipart/form-data'}}
                axios.post('/product/Edit', formData, config)
                    .then(function(response) {
                        let res = JSON.stringify(response.data);
                        console.log(res);
                        if (res === '["success"]') {
                            location.href = '/product';
                        } 
                    }).catch(err => console.log(err));

                // fetch("/product/Edit", {
                // method: 'POST',
                // headers: {
                //     'X-CSRF-TOKEN': token,
                //     'Content-Type': 'application/json',
                //     'Accept' : 'application/json',
                // },
                //     body: JSON.stringify({'productName':productName,'categories' : categories,'brand' : brand,'state' : 0,'price' : price,'sales' : sales,'productImg' : file})
                // }).then(
                //     (res) => res.json()
                // ).then(function(response) {
                //     let res = JSON.stringify(response);
                //     console.log(res);
                //     console.log(response);
                // }).catch(err => console.log(err));
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