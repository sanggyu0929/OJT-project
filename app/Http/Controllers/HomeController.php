<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\MMonDB;

class HomeController extends Controller
{
    public function index() {
        $data = ['LoggedUserInfo'=>MMonDB::where('email','=',session('LoggedUser'))->first()];
        return view('index', $data);
    }

    public function postAPI(Request $request) {
        return MMonDB::all();
    }

    function goCategory() {
        return view('category');
    }

    function logout() {
        if(session()->has('LoggedUser')) {
            session()->pull('LoggedUser');
            return redirect('login');
        }
    }
}
