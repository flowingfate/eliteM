<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Subject1;
use App\Models\Subject2;
use App\Models\Libfile;
use App\Models\Libclass;

use DB;
use Storage;
use Validator;

class SubjectCtl extends Controller
{
    /**----------------------------------------------
     * 这一部分全都是一级学科的操作
     *----------------------------------------------*/

    // 获取所有一级学科信息
    public function subject1s()
    {
    	$subject1s = Subject1::select(['id','number','title'])->get();
    	$data = [];
    	foreach ($subject1s as $subject1) 
    	{
    		$arr = [
    			'id'=>$subject1->id,
    			'number'=>$subject1->number,
    			'title'=>$subject1->title,
    			'subject2s'=>$subject1->subject2s()->select(['id','number','title','img_url','keywords','profile'])->get()->toArray(),
    		];
    		array_push($data, $arr);
    	}

    	// dd($data);
    	return response()->json($data);
    }

    /**----------------------------------------------
     * 自定义私有方法用于检验学科标号和标题
     *----------------------------------------------*/
    private function subCheck($arr,$table,$required=true)
    {
        $str = $required?'required|unique:':'unique:';
        $str .= $table;

        $rules = [ 'number'=>$str, 'title'=>$str ];
        $msg = [
            'number.unique'=>'已经使用了这个编号，请更换！！',
            'number.required'=>'编号不能为空',
            'title.unique'=>'已经使用了相同的标题，请更换！',
            'title.required'=>'标题不能为空',
        ];

        $validator = Validator::make($arr,$rules,$msg);
        return $validator->errors()->all();
    }

    // 添加一级学科
    public function addSub1(Request $request)
    {
        $arr = [
            'number' => $request->input('number'),
            'title' => $request->input('title'),
        ];
        $errs = $this->subCheck($arr,'subject1');
        if(count($errs)!=0) return response()->json(['type'=>'err','content'=>$errs[0]]);

        Subject1::create($arr);
        return response()->json(['type'=>'ok','content'=>'添加一级学科成功！']);
    }
    // 修改一级学科
    public function chSub1(Request $request)
    {
        // 任务ID
        $id = $request->input('id');
        $arr = $request->except(['id']);
        $errs = $this->subCheck($arr,'subject1',false);
        if(count($errs)!=0) return response()->json(['type'=>'err','content'=>$errs[0]]);

        $subject = Subject1::find($id);
        foreach ($arr as $k => $val) { $subject[$k] = $val; }
        $subject->save();

        return response()->json(['type'=>'ok','content'=>'修一级学科成功！']);
    }

    /**
     * 自定义的文件删除方法，只用于删除业务上传的文件
     * 先提供文件夹名，再提供文件名
     */
    private function delFile($p,$n)
    {
        if($n&&Storage::disk('upload')->has($p.'/'.$n))
        {
            Storage::disk('upload')->delete($p.'/'.$n);
        }
    }
    // 删除一级学科
    public function rmSub1(Request $request)
    {
        // 任务ID
        $id = $request->input('id');
        $sub1 = Subject1::find($id);

        /**
         * 需要注意的是，删除一级学科需要连带清除所有下属内容
         * 包括所有二级学科
         * 也包括所有的二级学科下属的文件和课程
         * 这种操作理应采用数据库事务来处理
        **/
        DB::transaction(function()use($sub1)
        {
            // 自动处理事务的闭包函数
            $sub2s = $sub1->subject2s;
            foreach ($sub2s as $sub2) 
            {
                $files = $sub2->libfiles;
                $classes = $sub2->libclasses;

                // 删除二级学科封面图像
                $this->delFile('profileImg',$sub2->saved_name);

                foreach ($files as $file)
                {
                    // 删除存储的文件
                    $this->delFile('files',$file->saved_name);
                }
                foreach ($classes as $class)
                {
                    // 删除课程封面图像
                    $this->delFile('classImg',$class->saved_name);
                }


                // 删除文件和课程
                $sub2->libfiles()->delete();
                $sub2->libclasses()->delete();
            }
            // 删除所有二级学科和一级学科
            $sub1->subject2s()->delete();
            $sub1->delete();
        });

        return response()->json(['type'=>'ok','content'=>'成功删除一级学科及其下属内容！']);
    }



    /**----------------------------------------------
     * 这一部分全都是二级学科的操作
     *----------------------------------------------*/

    // 获取二级级学科信息
    public function subject2(Request $request)
    {
        $id = $request->input('id');
        $sub2 = Subject2::select(['id','number','title','img_url','keywords','profile'])->find($id); 
        $files = $sub2->libfiles()->select(['id','origin_name','author','description','type','size'])->get()->toArray();
        $classes = $sub2->libclasses()->select(['id','title','url','time','img_url'])->get()->toArray();

        $data = [

            'info'=>$sub2->toArray(),
            'files'=>$files,
            'classes'=>$classes
        ];

        // dd($data);
        
        return response()->json($data);
    }

    // 添加二级学科
    public function addSub2(Request $request)
    {
        $arr = [
            'number' => $request->input('number'),
            'subject1_id' => $request->input('subject1_id'),
            'title' => $request->input('title'),
        ];
        $errs = $this->subCheck($arr,'subject2');
        if(count($errs)!=0) return response()->json(['msg'=>['type'=>'err','content'=>$errs[0]]]);

        $sub = Subject2::create($arr);
        $id = $sub->id;

        $data = [
            'subject'=>Subject2::select(['id','number','title','img_url','keywords','profile'])->find($id)->toArray(),
            'msg'=>['type'=>'ok','content'=>'添加二级学科成功！'],
        ];

        return response()->json($data);
    }

    // 删除二级学科
    public function rmSub2(Request $request)
    {
        // 任务ID
        $id = $request->input('id');
        $sub2 = Subject2::find($id);

        /**
         * 需要注意的是，删除二级学科需要连带清除所有下属内容
         * 包括所有的下属的文件和课程
         * 这种操作理应采用数据库事务来处理
        **/
        DB::transaction(function()use($sub2)
        {
            // 自动处理事务的闭包函数
            $files = $sub2->libfiles;
            $classes = $sub2->libclasses;

            // 删除二级学科封面图像
            $this->delFile('profileImg',$sub2->saved_name);

            foreach ($files as $file)
            {
                // 删除存储的文件
                $this->delFile('files',$file->saved_name);
            }
            foreach ($classes as $class)
            {
                // 删除课程封面图像
                $this->delFile('classImg',$class->saved_name);
            }

            // 删除文件和课程
            $sub2->libfiles()->delete();
            $sub2->libclasses()->delete();
            $sub2->delete();
        });

        return response()->json(['type'=>'ok','content'=>'删除二级学科成功！']);
    }

    // 修改二级学科
    public function chSub2(Request $request)
    {
        // 任务ID
        $id = $request->input('id');
        $inputs = $request->except(['file','id']);

        $errs = $this->subCheck($inputs,'subject2',false);
        if(count($errs)!=0) return response()->json(['msg'=>['type'=>'err','content'=>$errs[0]]]);
        
        $sub2 = Subject2::find($id);
        foreach ($inputs as $key => $value) { $sub2[$key] = $value; }

        if ($request->hasFile('file')) 
        {
            $file = $request->file('file');
            $type = $file->getClientOriginalExtension();
            $lastFileName = $sub2->saved_name;

            $saved_name = date('YmdHis').str_random(4).'.'.$type;
            $img_url = asset('upload/profileImg').'/'.$saved_name;

            $file->move(public_path('upload/profileImg'),$saved_name);

            $sub2->saved_name = $saved_name;
            $sub2->img_url = $img_url;

            if($lastFileName&&Storage::disk('upload')->has('profileImg/'.$lastFileName))
            {
                Storage::disk('upload')->delete('profileImg/'.$lastFileName);
            }
        }
        $sub2->save();
        
        $data = [
            'info'=>Subject2::select(['id','number','title','img_url','keywords','profile'])->find($id)->toArray(),
            'msg'=>['type'=>'ok','content'=>'编辑二级学科成功！'],
        ];

        return response()->json($data);
    }

}
