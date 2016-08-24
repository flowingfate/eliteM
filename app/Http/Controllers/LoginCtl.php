<?php

namespace App\Http\Controllers;

use Validator;
use Captcha;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Task;
use App\Models\Teacher;

class LoginCtl extends Controller
{
    public function index(Request $request,$role)
    {
        if($request->isMethod('get'))
        {
            return view('login',['role'=>$role]);
        }

    	$inputs = $request->all();

        // dd($inputs);

        if(!Captcha::check($inputs['captcha']))
        {
            return back()->with(['errMsg'=>'验证码不正确！']);
        }

    	$rules = [

    		'username'=>'required',
    		'password'=>'required',
    		'captcha'=>'required'
    	];

    	$validator = Validator::make($inputs,$rules);
    	if($validator->passes())
    	{
    		$user = null;
    		if($role=='admin')
    		{
    			$user = Admin::select(['id','password'])->where('username',$inputs['username'])->first();
    		}
    		if($role=='student')
    		{
    			$user = Student::select(['id','password'])->where('username',$inputs['username'])->first();
    		}
    		if($role=='teacher')
    		{
    			$user = Teacher::select(['id','password'])->where('username',$inputs['username'])->first();
    		}

    		if(!$user) return back()->with(['errMsg'=>'用户不存在！']);
    		if($inputs['password']==$user->password)
    		{
    			session(['user'=>$inputs['username']]);
    			return redirect('manage/'.$role.'/'.$user->id);
    		}
    		else return back()->with(['errMsg'=>'密码错误！']);
    	}
    	else
    	{
    		return back()->with(['errMsg'=>'表单不能为空']);
    	}
    }

    public function quit($role)
    {
    	session(['user'=>null]);
    	return redirect('login/'.$role);
    }

    public function code()
    {
        $img = Captcha::create();
        return $img;
    }

    public function manage($role,$id)
    {
        return view('manage',['role'=>$role,'id'=>$id]);
    }
}
