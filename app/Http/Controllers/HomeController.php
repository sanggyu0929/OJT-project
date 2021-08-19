<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\MMonDB;
use App\Models\categories;
use App\Models\brands;

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
            $categoryList = categories::paginate(2);
            $categoryList->appends($request->all());
            $data = [
                'title' => 'Category',
                'LoggedUserInfo'=>MMonDB::where('email','=',session('LoggedUser'))->first(),
                // 'categoryList' => categories::all()->sortByDesc("Cidx"),
                'categoryList' => $categoryList,
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
        if ($table) {
            return response()->json(['exists']);
        } else {
            $inputs = $request->all();

            $table = categories::where('Cidx', $request->Cidx)
                               ->update([
                                   'name' => $request->caName,
                                   'used' => $request->useChk,
                                ]);
           
            return response()->json(['success']);
        }
    }

    // 브랜드 페이지
    function goBrand(Request $request) {
        if($request->session()->has('LoggedUser')) {
            $brandList = brands::all()->sortByDesc("Bidx");
            $data = [
                'title' => '브랜드 관리',
                'LoggedUserInfo'=>MMonDB::where('email','=',session('LoggedUser'))->first(),
                'brandList' => $brandList,
            ];
            return view('brand', $data);
        } else {
            return redirect('login');
        }
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
            if ($Kname) {
                return response()->json(['Kname exists']);
            } else if ($Ename) {
                return response()->json(['Ename exists']);
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
        $table = brands::where('Kname', $request->Kname)->first();
        if ($table) {
            $table->delete();
            return response()->json(['success']);
        } else {
            return false;
        }
    }

    // 로그아웃 요청
    function logout() {
        if(session()->has('LoggedUser')) {
            session()->pull('LoggedUser');
            return redirect('login');
        }
    }
}
