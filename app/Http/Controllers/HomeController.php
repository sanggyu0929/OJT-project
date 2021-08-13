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
            $data = ['title' => 'Category'];
            return redirect('login');
        }
    }

    function goCaRegister() {
        $data = [
            'title' => '카테고리 등록',
        ];
        return view('caRegister', $data);
    }

    function caRegister(Request $request) {
        $table = categories::where('name', $request->caName)->first();
        if($table) {
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

    function logout() {
        if(session()->has('LoggedUser')) {
            session()->pull('LoggedUser');
            return redirect('login');
        }
    }
}
