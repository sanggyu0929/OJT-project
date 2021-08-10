<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class ContactUsController extends Controller
{
    public function index() {
        $data = [
            'title' => 'Contact Us',
            'content' => 'Contact Us페이지 입니다',
        ];
        
        return view('contact-us', $data);
    }
}
