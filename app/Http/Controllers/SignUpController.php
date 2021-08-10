<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\MMonDB;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Validator;


class SignUpController extends Controller
{
    public function index() {
        $data = [
            'title' => 'Sign-up',
        ];
        
        return view('sign-up', $data);
    }

    public function signUp(Request $request) {
        // $request->validate([
        //     'email'=>'required|email|unique:MMonDB',
        //     'pw'=>'required',
        //     'name'=>'required'
        // ]);
        // $validator = Validator::make($request->all(), [
        //     'email'=>'required|email|unique:MMonDB',
        //     'pw'=>'required',
        //     'name'=>'required'
        // ])->validate();
        // if ($validator->fails())
        // {
        //     return redirect()->back()->withErrors($validator->errors());
        // }

        //유효성 검사
        $validator = Validator::make($request->all(), [
            'email'=>'required|email|unique:App\Models\MMonDB',
            'pw'=>'required',
            'name'=>'required'
        ])->validate();
        
        if($request->pw !== $request->pwChk) {
            return response()->json(['error'=>'비밀번호가 다릅니다.']);
        } else if(empty($request->email) || empty($request->name) || empty($request->pw) || empty($request->pwChk)) {
            return response()->json(['error'=>'공백을 확인해주세요.']);
        } else if(MMonDB::where('email', $request->email)->first()) {
            return response()->json(['error'=>'중복된 이메일입니다.']);
        }
        $table = new MMonDB;
        $table->idx = '0';
        $table->email = $request->email;
        $table->pw = \Hash::make($request->pw);
        $table->name = $request->name;
        if($table->save()) {
            return response()->json(['success']);
        }
    
        return response()->json(['success'=>'회원가입 완료!']);
        
    }

    public function emailChk(Request $request) {
        $table = MMonDB::where('email', $request->email)->first();
        if($table) {
            return response()->json(['exists']);
        } else {
            return response()->json(['success']);
        }
    }
}
