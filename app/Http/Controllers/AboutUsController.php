<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AboutUsController extends Controller
{
    public function index() {
        $data = [
            'title' => 'About Us',
            'content' => 'About Us페이지 입니다.',
        ];
        
        return view('about-us', $data);
    }
}
