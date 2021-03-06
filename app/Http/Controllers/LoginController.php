<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\MMonDB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;



class LoginController extends Controller
{
    public function index(Request $request) {
        // 세션 확인
        if($request->session()->has('LoggedUser')) {
            $data = ['LoggedUserInfo'=>MMonDB::where('email','=',session('LoggedUser'))->first()];
            return redirect()->route('home')->with($data);
        } else {
            $data = ['title' => 'Login'];
            
            return view('login', $data);
        } 
    }

    public function loginChk(Request $request) {
        $inputs = $request->all();

        //유효성 검사
        $validator = Validator::make($inputs, [
            'email'=>'required|email',
            'pw'=>'required',
        ]);
    
        //유효성 검사 실패 시 에러 메세지
        if ($validator->fails()) {
            return response()->json([
                'auth' => false,
                'errors' => $validator->errors()
            ]);
        }
        

        // 아이디 비번 검사
        $table = MMonDB::where('email', $request->email)->first();
        if(!$table) {
            return response()->json(["error" => "이메일이 존재하지 않습니다."]);
        } else if(empty($request->email) || empty($request->pw)) {
            return response()->json(['error'=> '이메일 혹은 비밀번호가 빈 값입니다.']);
        } else {
            if (\Hash::check($request->pw, $table->pw)) {
                $request->session()->put('LoggedUser', $table->email);
                $request->session()->put('LoggedName', $table->name);
                $response = new Response('Hello World');
                $response->withCookie(cookie('email', $request->email, 5));
                return response()->json(["success"]);
            } else {
                return response()->json(["error" => "이메일 혹은 비밀번호가 다릅니다."]);
            }
        }

        //  else if (Auth::attempt($request->only('email','pw'))) {
        //     return response()->json(['success']);
        // }
        
        // else if (\Auth::attempt($validator)) {
        //    $request->session()->put('email',$request->email);
        //    return response()->json(['success']);
        // } 
    
        //$table = MMonDB::where('email', $request->get('email'))->first();
    
        // if (!$table) {
        //     $errors[] = "이메일이 존재하지 않습니다.";
        // } else if (empty($request->email) || empty($request->pw)) {
        //     $errors[] = "이메일 혹은 비밀번호가 빈 값입니다.";
        // } else if (!\Hash::check($request->pw, $table->pw)) {
        //     $errors[] = "이메일 혹은 비밀번호가 다릅니다.";
        // } else {
    
        //     $credentials = ['email' => $request->get('email'), 'pw' => $request->get('pw')];
    
        //     if (Auth::attempt($credentials)) {
        //         $auth = true;
        //     } else {
        //         $errors[] = "Email/Password combination not correct";
        //     }
    
        // }
    
        // if ($request->ajax()) {          
        //     return response()->json([
        //         'auth' => $auth,
        //         'errors' => $errors
        //     ]);
        // }
    } 
}
