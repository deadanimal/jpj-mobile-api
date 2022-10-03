<?php

namespace App\Http\Controllers;

use App\Mail\CheckEmail;
use App\Mail\TestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SecCheckController extends Controller
{
    public static function strip_alphanum($string)
    {
        $value = "/[^a-zA-Z0-9\s\@\.]/";
        return  preg_replace($value, "", $string);
    }

    public function checkemail()
    {
        // try {
        //     Mail::to('najhan.mnajib@gmail.com')->send(new TestMail());
        //     dd('check email'); 
        // } catch (\Throwable $th) {
        //     dd('error');
        // }
        Mail::to('najhan.mnajib@gmail.com')->send(new TestMail());
            dd('check email'); 
    }
}
