<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Task;
use App\Models\Teacher;
use App\Models\M2m;
use App\Models\Subject1;

use Validator;

class TestCtl extends Controller
{
    public function index(Request $request)
    {
        $user = Admin::destroy(5);

        dd($user);

    	// dd('My name is Jason Shang');
    }
    public function up(Request $request)
    {
    	$file = $request->file('img');

    	$data = [
    		'originMime'=>$file->getClientMimeType(),
    		'originName'=>$file->getClientOriginalName(),
    		'originType'=>$file->getClientOriginalExtension(),
    		'size'=>$file->getClientSize(),
    		'input'=>$request->all()
    	];
        $filedata = [
            0=>$file->guessExtension(),
            1=>$file->getMimeType(),
            2=>$file->getExtension(),
            3=>$file->getClientOriginalName(),  // 原始文件的全名
            4=>$file->getClientOriginalExtension(),   // 原始文件的扩展名
            5=>$file->getClientMimeType(),
            6=>$file->guessClientExtension(),
            7=>$file->getClientSize(),   // 原始文件大小(b)
            8=>$file->getMaxFilesize(),
            9=>$file->getErrorMessage(),
        ];

    	$file->move(public_path('upload/'),str_random(20));

    	// dd($data);

        return response()->json($data);
    }
}
