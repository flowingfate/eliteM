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

class TestCtl extends Controller
{
    public function index(Request $request)
    {
    	$arr1 = [1,2,3,4,5,6,7];
        $arr2 = [1,2,3,6];

        $arr = array_diff($arr1, $arr2);

        $tasks = Student::find(1)->tasks()->whereIn('teacher_id',$arr)->orderBy('student_id')->get()->toArray();

        dd($tasks);
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
