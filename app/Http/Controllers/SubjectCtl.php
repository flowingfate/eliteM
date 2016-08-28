<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Subject1;
use App\Models\Subject2;

class SubjectCtl extends Controller
{
    // 获取所有一二级学科信息
    public function subjects()
    {
    	$subject1s = Subject1::select(['id','number','title'])->get();
    	$data = [];
    	foreach ($subject1s as $subject1) 
    	{
    		$arr = [
    			'id'=>$subject1->id,
    			'number'=>$subject1->number,
    			'title'=>$subject1->title,
    			'subject2s'=>$subject1->subject2s()->select(['id','number','title','img_url','profile'])->get()->toArray(),
    		];
    		array_push($data, $arr);
    	}

    	// dd($data);
    	return response()->json($data);
    }
}
