<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Task;
use App\Models\Teacher;

class AdminCtl extends Controller
{
	// 获取所有的用户，包括所有教师和学生
    public function users()
    {
        $teachers = Teacher::select(['id','name','username','comment'])->get();
        $students = Student::select(['id','name','username','comment'])->get();

        $data = ['teachers'=>$teachers,'students'=>$students];

        return response()->json($data);
    }

    // 添加一个用户，教师或是学员
    public function addUser(Request $request)
    {
        $role = $request->input('role');
        $name = $request->input('name');
        $username = $request->input('username');
        $comment = $request->input('comment');

        if($role=="teacher")
        {
            $arr=['name'=>$name,'username'=>$username,'comment'=>$comment];
            Teacher::create($arr);
            return response()->json(['type'=>'ok','content'=>'添加导师成功！']);
        }
        if($role=="student")
        {
            $arr=['name'=>$name,'username'=>$username,'comment'=>$comment];
            Student::create($arr);
            return response()->json(['type'=>'ok','content'=>'添加学员成功！']);
        }

        return response()->json(['type'=>'err','content'=>'添加用户失败！']);
    }

    // 删除一个用户，教师或是学员
    public function removeUser(Request $request)
    {
        $role = $request->input('role');
        $id = $request->input('id');

        if($role=="teacher")
        {
            Teacher::destroy($id);
            return response()->json(['type'=>'ok','content'=>'删除导师成功！']);
        }
        if($role=="student")
        {
            Student::destroy($id);
            return response()->json(['type'=>'ok','content'=>'删除学员成功！']);
        }

        return response()->json(['type'=>'err','content'=>'删除用户失败！']);
    }


    // 重置密码
    public function resetPassword(Request $request)
    {
        $role = $request->input('role');
        $id = $request->input('id');
        $pass = '7654321';

        if($role=="teacher")
        {
            $user = Teacher::find($id);
            $user->password = $pass;
            $user->save();
            return response()->json(['type'=>'ok','content'=>'重置导师密码成功！']);
        }
        if($role=="student")
        {
            $user = Student::find($id);
            $user->password = $pass;
            $user->save();
            return response()->json(['type'=>'ok','content'=>'重置学员密码成功！']);
        }

        return response()->json(['type'=>'err','content'=>'重置密码失败！']);
    }

    // 修改备注
    public function modifyCmt(Request $request)
    {
        $role = $request->input('role');
        $id = $request->input('id');
        $content = $request->input('content');

        if($role=="teacher")
        {
            $user = Teacher::find($id);
            $user->comment = $content;
            $user->save();
            return response()->json(['type'=>'ok','content'=>'修改导师备注成功！']);
        }
        if($role=="student")
        {
            $user = Student::find($id);
            $user->comment = $content;
            $user->save();
            return response()->json(['type'=>'ok','content'=>'修改学员备注成功！']);
        }
        
        return response()->json(['type'=>'err','content'=>'修改备注失败！']);
    }
}
