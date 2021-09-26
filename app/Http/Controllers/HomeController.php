<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\Paginator;
use App\Models\MMonDB;
use App\Models\categories;
use App\Models\brands;
use App\Models\products;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class HomeController extends Controller
{
    //메인페이지
    public function index() {
        $data = ['LoggedUserInfo'=>MMonDB::where('email','=',session('LoggedUser'))->first()];
        return view('index', $data);
    }

    public function postAPI(Request $request) {
        return MMonDB::all();
    }

    //카테고리 페이지
    function goCategory(Request $request) {
        if($request->session()->has('LoggedUser')) {
            $data = [
                'title' => 'Category',
                'LoggedUserInfo'=>MMonDB::where('email','=',session('LoggedUser'))->first(),
                'categoryList' => categories::all()->sortByDesc("Cidx"),
            ];
            return view('category', $data);
        } else {
            return redirect('login');
        }
    }

    // 카테고리 등록 페이지
    function goCaRegister(Request $request) {
        if($request->session()->has('LoggedUser')) {
            $data = [
                'title' => '카테고리 등록',
                'LoggedUserInfo'=>MMonDB::where('email','=',session('LoggedUser'))->first(),
            ];
            return view('caRegister', $data);
        } else {
            return redirect('login');
        }
    }

    // 카테고리 수정 페이지
    function goCaEdit(Request $request, $Cidx) {
        if($request->session()->has('LoggedUser')) {
            $table = categories::where('Cidx', $Cidx)->first();
            $data = [
                'title' => '카테고리 수정',
                'LoggedUserInfo'=>MMonDB::where('email','=',session('LoggedUser'))->first(),
                'selectedList' => $table,
            ];
            return view('caEdit', $data);
        } else {
            return redirect('login');
        }
        // $table = categories::where('Cidx', $Cidx)->first();
        // $data = [
        //     'title' => '카테고리 수정',
        //     'selectedList' => $table,
        // ];
        // return view('caEdit', $data);
    }

    // post 카테고리 등록
    function caRegister(Request $request) {
        $table = categories::where('name', $request->caName)->first();
        if ($table) {
            return response()->json(['exists']);
        } else {
            $inputs = $request->all();

            $table = new categories;
            $table->Cidx = '0';
            $table->name = $request->caName;
            $table->used = $request->useChk;
            
            if($table->save()) {
                return response()->json(['success']);
            }
        }
    }

    // post 카테고리 수정 
    function caEdit(Request $request) {
        $table = categories::where('name', $request->caName)->first();

        // return response()->json([$getIdx=>$request->Cidx]);
        if ($table && $table->Cidx != $request->Cidx) {
            return response()->json(['exists']);
        } else {
            if ($request->useChk !='1') {
                $usedCaList = products::where('selectedCategories','LIKE','%'.$request->caName.'%')->get();
                $usedCaList = $usedCaList->count();
                if ($usedCaList > 0) {
                    return response()->json(['error'=>'상품에 등록된 카테고리입니다.']);
                } else {
                    $table = categories::where('Cidx', $request->Cidx)
                               ->update([
                                   'name' => $request->caName,
                                   'used' => $request->useChk,
                                ]);
                    return response()->json(['success']);
                }
                
            } else {
                $table = categories::where('Cidx', $request->Cidx)
                               ->update([
                                   'name' => $request->caName,
                                   'used' => $request->useChk,
                                ]);
                return response()->json(['success']);
            }
        }
    }

    // 브랜드 페이지
    function goBrand(Request $request) {
        if($request->session()->has('LoggedUser')) {
            // $brandList = brands::paginate(2)->sortByDesc("Bidx");
            // $brandList = brands::orderBy('Bidx', 'asc')->simplePaginate(2);
            $brandList = brands::orderByDesc('Bidx')->paginate(3);
            // $categoryList = categories::paginate(2);
            $page = $request->page;
            
            $data = [
                'title' => '브랜드 관리',
                'LoggedUserInfo'=>MMonDB::where('email','=',session('LoggedUser'))->first(),
                'brandList' => $brandList,
                'page' => $page
            ];
            return view('brand', $data);
            // return $page;
        } else {
            return redirect('login');
        }
        // $brandList = brands::orderByDesc('Bidx')->paginate(3);
        // return view('brand', compact('brandList'));
    }

    // 브랜드 등록 페이지
    function goBrandRegister() {
        $data = [
            'title' => '브랜드 등록',
        ];
        return view('brandRegister', $data);
    }

    // 브랜드 등록 post
    function brandRegister(Request $request) {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'Kname'=>'required',
            'Ename'=>'required',
            'phrase'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }
        $Kname = brands::where('Kname', $request->Kname)->first();
        $Ename = brands::where('Ename', $request->Ename)->first();

        if ($Kname) {
            return response()->json(['Kname exists']);
        } else if ($Ename) {
            return response()->json(['Ename exists']);
        } else {
            $table = new brands;
            $table->Bidx = '0';
            $table->Kname = $request->Kname;
            $table->Ename = $request->Ename;
            $table->phrase = $request->phrase;
            $table->total = 0;
            
            if($table->save()) {
                return response()->json(['success']);
            }
        }
    }

    // 브랜드 수정 페이지
    function goBrandEdit(Request $request, $Bidx) {
        if($request->session()->has('LoggedUser')) {
            $table = brands::where('Bidx', $Bidx)->first();
            $data = [
                'title' => '브랜드 수정',
                'LoggedUserInfo'=>MMonDB::where('email','=',session('LoggedUser'))->first(),
                'selectedList' => $table,
            ];
            return view('brandEdit', $data);
        } else {
            return redirect('login');
        }
    }

    // 브랜드 수정 post
    function brandEdit(Request $request) {
            $inputs = $request->all();
            
            $validator = Validator::make($inputs, [
                'Kname'=>'required',
                'Ename'=>'required',
                'phrase'=>'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ]);
            }
            
            $Kname = brands::where('Kname', $request->Kname)->first();
            $Ename = brands::where('Ename', $request->Ename)->first();
            if ($Kname && $Kname->Bidx == $request->Bidx || $Ename && $Ename->Bidx == $request->Bidx) {
                $table = brands::where('Bidx', $request->Bidx)
                                   ->update([
                                       'Kname' => $request->Kname,
                                       'Ename' => $request->Ename,
                                       'phrase' => $request->phrase,
                                    ]);
                return response()->json(['success']);
            } else if ($Kname) {
                return response()->json(['error'=>'한글명이 중복되었습니다.']);
            } else if ($Ename) {
                return response()->json(['error'=>'영문명이 중복되었습니다.']);
            } else {
                $table = brands::where('Bidx', $request->Bidx)
                                   ->update([
                                       'Kname' => $request->Kname,
                                       'Ename' => $request->Ename,
                                       'phrase' => $request->phrase,
                                    ]);
                return response()->json(['success']);
            }
    }

    // 브랜드 삭제
    function brandDelete(Request $request) {
        $table = brands::where('Kname','=',$request->Kname)->first();
        if ($table && $table->total == 0) {
            $table->delete();
            return response()->json(['success']);
        } else {
            return response()->json(['error'=>'브랜드 삭제 실패']);
        }


        if ($table && $table->Cidx != $request->Cidx) {
            return response()->json(['exists']);
        } else {
            if ($request->useChk !='1') {
                $usedCaList = products::where('selectedCategories','LIKE','%'.$request->caName.'%')->get();
                $usedCaList = $usedCaList->count();
                if ($usedCaList > 0) {
                    return response()->json(['error'=>'상품에 등록된 카테고리입니다.']);
                } else {
                    $table = categories::where('Cidx', $request->Cidx)
                               ->update([
                                   'name' => $request->caName,
                                   'used' => $request->useChk,
                                ]);
                    return response()->json(['success']);
                }
                
            } else {
                $table = categories::where('Cidx', $request->Cidx)
                               ->update([
                                   'name' => $request->caName,
                                   'used' => $request->useChk,
                                ]);
                return response()->json(['success']);
            }
        }
    }

    // 상품관리 페이지
    function goProduct() {
        $productList = products::all()->sortByDesc('Pidx');
        $data = [
            'title' => '상품관리 페이지',
            'productList' => $productList,
        ];
        return view('product', $data);
    }

    // 상품등록 페이지
    function goProductRegister() {
        $usedCategories = categories::where('used', '1')->get();
        $brandList = brands::all();
        $data = [
            'title' => '상품등록 페이지',
            'usedCategories' => $usedCategories,
            'brandList' => $brandList
        ];
        return view('productRegister', $data);
        // return $table;
    }

    

    
    // 상품등록 post
    function productRegister(Request $request) {
        $inputs = $request->all();
        

        if ($request->state == 3) {
            $validator = Validator::make($inputs, [
                'productName'=>'required',
                'categories'=>'required',
                'brand'=>'required',
                'state'=>'required',
                'price'=>'required',
                'sales'=>'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ]);
            } 
            $table = new products([
                'Pidx' => 0,
                'name' => $request->productName,
                'selectedBrand' => $request->brand,
                'selectedCategories' => $request->categories,
                'state' => $request->state,
                'price' => $request->price,
                'sales' => $request->sales,
                'extension' => '',
            ]);
            $brand = brands::where('Kname',$request->brand)->increment('total',1);
            $table->save();
            return response()->json(['success']);
        } else {
            // $validator = Validator::make($inputs, [
            //     'productName'=>'required',
            //     'categories'=>'required',
            //     'brand'=>'required',
            //     'state'=>'required',
            //     'price'=>'required',
            //     'sales'=>'required',
            // ]);
            // if ($validator->fails()) {
            //     return $validator->errors();
            // }

            $validator = Validator::make($request->all(),[
                'productName' => 'required',
                'categories'=>'required',
                'brand'=>'required',
                'state'=>'required',
                'price'=>'required',
                'sales'=>'required',
                'productImg'=>'required',
            ]);

            if (!$validator->passes()) {
                return response()->json(['error'=> $validator->errors()]);
            } else {
                if ($request->hasFile('productImg')) {
                    $doc_root = $_SERVER["DOCUMENT_ROOT"]; // Web서버 root directory
                    $dir=$doc_root.'\image';
                    $count = products::withTrashed()->get();
                    $count = $count->count() + 1;
                    $file = $request->file('productImg');
                    $extension = $request->file('productImg')->extension();
                    $imgName = $count.'.'.$extension;
                    $file->move(\public_path('image'), $imgName);

                    $table = new products([
                        'Pidx' => 0,
                        'name' => $request->productName,
                        'selectedBrand' => $request->brand,
                        'selectedCategories' => $request->categories,
                        'state' => $request->state,
                        'price' => $request->price,
                        'sales' => $request->sales,
                        'extension' => $extension,
                    ]);
                    $brand = brands::where('Kname',$request->brand)->increment('total',1);
                    $table->save();
                    return response()->json(['success']);
                }
            }
        }
    }

    // 상품 수정 페이지
    function goProductEdit(Request $request, $Pidx) {
        $usedCategories = categories::where('used', '1')->get();
        $brandList = brands::all();
        $selectedCategories = products::where('Pidx',$Pidx)->get();
        if($request->session()->has('LoggedUser')) {
            $table = products::where('Pidx', $Pidx)->first();
            $data = [
                'title' => '상품 수정',
                'LoggedUserInfo'=>MMonDB::where('email','=',session('LoggedUser'))->first(),
                'selectedList' => $table,
                'brandList' => $brandList,
                'usedCategories' => $usedCategories,
                'selectedCategories' => $selectedCategories,
            ];
            return view('productEdit', $data);
        } else {
            return redirect('login');
        }
    }

    // 상품 수정 post
    function productEdit(Request $request) {
        $inputs = $request->all();
        $productIdx = $request->productIdx;
        $getExtension = products::where('Pidx','=',$productIdx)->value('extension');
        if ($request->state == 3) {
            $validator = Validator::make($inputs, [
                'productName'=>'required',
                'categories'=>'required',
                'brand'=>'required',
                'state'=>'required',
                'price'=>'required',
                'sales'=>'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ]);
            } 
            if ($request->hasFile('productImg')) {
                $doc_root = $_SERVER["DOCUMENT_ROOT"]; // Web서버 root directory
                $dir=$doc_root.'\image';
                $file_chk = $dir.'\\'.$productIdx.'.'.$getExtension;
                if(file_exists($file_chk)){
                    File::delete($file_chk);
                    $file = $request->file('productImg');
                    $extension = $request->file('productImg')->extension();
                    $imgName = $productIdx.'.'.$extension;
                    $file->move(\public_path('image'), $imgName);
                } else {
                    $file = $request->file('productImg');
                    $extension = $request->file('productImg')->extension();
                    $imgName = $productIdx.'.'.$extension;
                    $file->move(\public_path('image'), $imgName);
                }
                $table = products::where('Pidx', $productIdx)
                                    ->update([
                                        'name' => $request->productName,
                                        'selectedBrand' => $request->brand,
                                        'selectedCategories' => $request->categories,
                                        'state' => $request->state,
                                        'price' => $request->price,
                                        'sales' => $request->sales,
                                        'extension' => $extension,
                                    ]);
                return response()->json(['success']);
            } else {
                $table = products::where('Pidx', $productIdx)
                                    ->update([
                                        'name' => $request->productName,
                                        'selectedBrand' => $request->brand,
                                        'selectedCategories' => $request->categories,
                                        'state' => $request->state,
                                        'price' => $request->price,
                                        'sales' => $request->sales,
                                    ]);
                $table->save();
                return response()->json(['success']);
            }
        } else {
            $validator = Validator::make($inputs, [
                'productName'=>'required',
                'categories'=>'required',
                'brand'=>'required',
                'state'=>'required',
                'price'=>'required',
                'sales'=>'required',
                'productImg'=>'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ]);
            } 


            if ($request->hasFile('productImg')) {
                $doc_root = $_SERVER["DOCUMENT_ROOT"]; // Web서버 root directory
                $dir=$doc_root.'\image';
                $file_chk = $dir.'\\'.$productIdx.'.'.$getExtension;

                if(file_exists($file_chk)){
                    File::delete($file_chk);
                    $file = $request->file('productImg');
                    $extension = $request->file('productImg')->extension();
                    $imgName = $productIdx.'.'.$extension;
                    $file->move(\public_path('image'), $imgName);
                } else {
                    $file = $request->file('productImg');
                    $extension = $request->file('productImg')->extension();
                    $imgName = $productIdx.'.'.$extension;
                    $file->move(\public_path('image'), $imgName);
                }
                
                $table = products::where('Pidx', $productIdx)
                                    ->update([
                                        'name' => $request->productName,
                                        'selectedBrand' => $request->brand,
                                        'selectedCategories' => $request->categories,
                                        'state' => $request->state,
                                        'price' => $request->price,
                                        'sales' => $request->sales,
                                        'extension' => $extension,
                                    ]);
                return response()->json(['success']);
            } else {
                return response()->json(['No file']);
            }


            // if ($request->hasFile('productImg')) {
            //     $doc_root = $_SERVER["DOCUMENT_ROOT"]; // Web서버 root directory
            //     $dir=$doc_root.'\image';
            //     $file_chk = $dir.'\\'.$productIdx.'.'.$getExtension;

            //     if(file_exists($file_chk)){
            //         File::delete($file_chk);
            //         $file = $request->file('productImg');
            //         return dd($file);
            //         $extension = $request->file('productImg')->extension();
            //         // $productIdx = $file_count + 1;
            //         $imgName = $productIdx.'.'.$extension;
            //         $file->move(\public_path('image'), $imgName);
            //         return '파일 존재';
            //     } else {
            //         $count = products::all();
            //         $count = $count->count() + 1;
            //         $file = $request->file('productImg');
            //         return dd($file);
            //         $extension = $request->file('productImg')->extension();
            //         $productIdx = $file_count + 1;
            //         $imgName = $count.'.'.$extension;
            //         $file->move(\public_path('image'), $imgName);
            //     }
            // } else {
            //     return 'hasfile No';
            // }    
        }
    }

     // 상품 삭제
     function productDelete(Request $request) {
        $table = products::where('Pidx', $request->productIdx)->first();
        if ($table) {
            $table->delete();
            $brand = brands::where('Kname',$request->brand)->decrement('total',1);
            return response()->json(['success']);
        } else {
            return response()->json(['error'=>'상품 삭제 실패']);
        }
    }

    // 상품 검색
    function productSearch(Request $request) {
        $multiSearch = explode( ',', $request->searchData );
        $products = [];
        foreach ($multiSearch as $item) {
            array_push($products,products::where('name','LIKE','%'.$item.'%')->get());
        }
        // $i = $products->count();
        $output = "";
        $i = count($products);
        $output .=
        '<span>총 상품 '.$i.'개</span>
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
                <ul class="product-list">';    
                    foreach ($products as $list) {
                        $output .='
                        <li>'.$i.'</li>
                        <li>'.$list->selectedBrand.'</li>';
                    }
                    return response()->json([$output]);
                    //     <li>'.$list['selectedBrand'].'</li>
                    //     <li>'.$list['selectedCategories'].'</li>
                    //     <li>'.$list['name'].'</li>
                    //     <li>'.$list['state'].'</li>
                    //     <li>'.$list['price'].'</li>
                    //     <li>'.$list['sales'].'</li>';
                        
                    //     $discount = 100 - ($list['sales']/$list['price'] * 100);
                    //     $discount = round($discount).'%';
                        
                    //     $output .='
                    //     <li>'.$discount.'</li>
                    //     <div class="manage">
                    //         <a href="/product/Edit/'.$list->Pidx.'"><li>수정</li></a>
                    //         <button type="button" class="del-btn">삭제</button>
                    //     </div>';
                    //     $i--;
                    // }
            //         $output .='
            //     </ul>
            // </div>';
        
        return response()->json([$output]);
    }

    // 로그아웃 요청
    function logout() {
        if(session()->has('LoggedUser')) {
            session()->pull('LoggedUser');
            return redirect('login');
        }
    }
}
