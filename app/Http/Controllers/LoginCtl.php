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
    public function domain(Request $request)
    {
        $site = explode('//',url('/'))[1];
        $domain = explode('.',$site)[0];
        
        if($domain=='g') return redirect('login/admin');
        if($domain=='t') return redirect('login/teacher');
        if($domain=='y') return redirect('login/vindicator');

        return redirect('login/student');
    }

    public function index(Request $request,$role)
    {
        // 限制$role只能是某几种类型
        if(!in_array($role,['admin','vindicator','teacher','student'])) return "您输入的地址有误";

        // get请求直接访问的时候，返回页面，不使用验证码
        if($request->isMethod('get')) return view('login',['role'=>$role]);


    	$inputs = $request->all();

    
        // Validator表单数据校验-----有验证码时要验证其真伪
    	$rules = [

    		'username'=>'required',
    		'password'=>'required'
    	];
        if(session('captcha')) $rules['captcha']='required';
        $message = [
            'username.required'=>'用户名不能为空',
            'password.required'=>'密码不能为空'
        ];
        if(session('captcha')) $message['captcha.required']='验证码不能为空';

    	$validator = Validator::make($inputs,$rules,$message);
        if($validator->fails()) return back()->withErrors($validator)->withInput();
        if(session('captcha')&&(!Captcha::check($inputs['captcha'])))
        {
            return back()->withErrors(['验证码不正确请重新输入'])->withInput()->with('captcha',true);
        }

        // 表单格式及验证码正确之后进行用户名和密码的验证，首先查看用户名是否存在
		$user = null;
		if(in_array($role,['admin','vindicator']))
		{
			$user = Admin::select(['id','password','role'])->where('username',$inputs['username'])->first();
            // 判断所取到的用户是否符合身份
            if($user->role!=$role) $user = null;
		}
		if($role=='student')
		{
			$user = Student::select(['id','password'])->where('username',$inputs['username'])->first();
		}
		if($role=='teacher')
		{
			$user = Teacher::select(['id','password'])->where('username',$inputs['username'])->first();
		}
		if(!$user) return back()->withErrors(['用户不存在，请确认用户名是否正确！'])->withInput()->with('captcha',true);

        // 如果用户名存在，则进行密码的验证
		if($inputs['password']==$user->password)
		{
			session(['user.role'=>$role,'user.id'=>$user->id,'captcha'=>null]);

			return redirect('manage/'.$role.'/'.$user->id);
		}
		else return back()->withErrors(['密码错误，请重新输入！'])->withInput()->with('captcha',true);
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
        // 限制$role只能是某几种类型
        if(!in_array($role,['admin','vindicator','teacher','student'])) return response("您输入的地址有误");

        $user = session('user');
        $ID = $user['id'];

        if($id!=$ID) return response('无权限访问');

        return view('manage',['role'=>$role,'id'=>$id]);
    }
}
