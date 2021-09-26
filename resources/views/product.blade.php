@extends('layouts.master')

@section('title', $title)

@section('content')
    <section class="product-wrap">
        <h2>상품관리 페이지</h2>
        <span>검색어</span>
        <select name="search-select" id="search-select">
            <option value="상품명">상품명</option>
            <option value="카테고리">카테고리</option>
            <option value="브랜드">브랜드</option>
        </select>
        <input type="text" id="search-data">
        <span>상태</span>
        <label>
            <input type="checkbox" class="state" id="selling">
            판매중
        </label>
        <label>
            <input type="checkbox" class="state" id="out-of-stock">
            일시품절
        </label>
        <label>
            <input type="checkbox" class="state" id="sold-out">
            품절
        </label>
        <label>
            <input type="checkbox" class="state" id="stop-selling">
            판매중지
        </label>
        <span>가격</span>
        <input type="number" name="price" id="min-price">
        <input type="number" name="price" id="max-price">
        <span>등록일</span>
        <input type="date" name="date" id="min-date">
        <input type="date" name="date" id="max-date">
        <button type="button" id="search-btn">검색</button>
        <a href="{{ route('product.register') }}">상품 등록</a>
        <div id="searched-box">
            @php
                $i = $productList->count();
            @endphp
            <span>총 상품 {{ $i }}개</span>
            <div class="product-box">
                <div class="product-box-top">
                    <span>상품 번호</span>
                    <span>브랜드</span>
                    <span>카테고리</span>
                    <span>상품명</span>
                    <span>상태</span>
                    <span>정가</span>
                    <span>판매가</span>
                    <span>할인율</span>
                    <span>관리</span>
                </div>
                <ul class="product-list">
                    
                    @foreach ($productList as $list)
                    <li>{{ $i }}</li>
                    <li>{{ $list['selectedBrand'] }}</li>
                    <li>{{ $list['selectedCategories'] }}</li>
                    <li>{{ $list['name'] }}</li>
                    <li>{{ $list['state'] }}</li>
                    <li>{{ $list['price'] }}</li>
                    <li>{{ $list['sales'] }}</li>
                    <?php
                        $discount = 100 - ($list['sales']/$list['price'] * 100);
                        $discount = round($discount).'%';
                    ?>
                    <li>{{ $discount }}</li>
                    <div class="manage">
                        <a href="/product/Edit/{{ $list->Pidx }}"><li>수정</li></a>
                        <button type="button" class="del-btn">삭제</button>
                    </div>
                    @php
                        $i--;
                    @endphp
                    @endforeach
                </ul>
            </div>
        </div>
    
    </section>
@endsection

@push('scripts')
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        // 상품 삭제
        let delBtn = document.getElementsByClassName('del-btn');
        for (let i = 0; i < delBtn.length; i++) {
            delBtn[i].onclick = function() {
                let productIdx = this.parentNode.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.innerText;
                let brand = this.parentNode.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.innerText;
                productIdx = Number(productIdx);
                console.log(productIdx);
                if (confirm(`${productIdx}번 상품을 삭제하시겠습니까?`) == true) {
                    let formData = new FormData();
                    formData.append('productIdx', productIdx);
                    formData.append('brand', brand);

                    axios({
                        method:'POST',
                        url:'/product/Delete',
                        data : formData,
                    })
                    .then(function(response) {
                        let res = JSON.stringify(response.data);
                        console.log(res);
                        if (res === '["success"]') {
                            location.href = '/product';
                        }
                    }).catch(err => console.log(err));
                }
            }
        }

        // 상품 검색
        let searchBtn = document.getElementById('search-btn');

        searchBtn.onclick = function() {
            let searchSelect = document.getElementById('search-select').value;
            let searchData = document.getElementById('search-data').value;
            let state =document.getElementsByClassName('state');
            let minPrice = document.getElementById('min-price').value;
            let maxPrice = document.getElementById('max-price').value;
            let minDate = document.getElementById('min-date').value;
            let maxDate = document.getElementById('max-date').value;

            let formData = new FormData();
            formData.append('searchData', searchData);

            axios({
                        method:'POST',
                        url:'/product/search',
                        data : formData,
                    })
                    .then(function(response) {
                        let res = JSON.stringify(response.data);
                        console.log(res);
                        if (res === '["success"]') {
                            let ul = document.getElementsByClassName('product-list');
                            ul.innerHTML = response.data.productList;
                        } else {
                            alert(response.data.error);
                        }
                    }).catch(err => console.log(err));
        }

    </script>
@endpush