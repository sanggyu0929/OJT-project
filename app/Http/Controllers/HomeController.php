<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\MMonDB;
use App\Models\categories;

class HomeController extends Controller
{
    public function index() {
        $data = ['LoggedUserInfo'=>MMonDB::where('email','=',session('LoggedUser'))->first()];
        return view('index', $data);
    }

    public function postAPI(Request $request) {
        return MMonDB::all();
    }

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

    function logout() {
        if(session()->has('LoggedUser')) {
            session()->pull('LoggedUser');
            return redirect('login');
        }
    }
}
