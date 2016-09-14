<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => ['web','cors']], function () 
{
	// Route::get('/',function(){return view('test');});
	// Route::get('test','TestCtl@index');

	Route::any('login/{role}','LoginCtl@index');
	Route::get('quit/{role}','LoginCtl@quit');
	Route::get('code','LoginCtl@code');

	Route::group(['middleware' => ['login']], function () 
	{
		Route::get('manage/{role}/{id}','LoginCtl@manage');
		// 管理员相关路由
		Route::group(['prefix'=>'admin'],function()
		{
			Route::get('progress', 'AdminCtl@progress');
			Route::get('finished', 'AdminCtl@finished');
			Route::get('setFinish', 'AdminCtl@setFinish');

			Route::get('studentInfo','StudentCtl@studentInfo');
			Route::get('setTeacher','StudentCtl@setTeacher');

			Route::get('teacherlist', 'TeacherCtl@teacherlist');
			Route::get('teacherInfo','TeacherCtl@teacherInfo');

			Route::get('users', 'AdminCtl@users');
			Route::get('addUser','AdminCtl@addUser');
			Route::get('modify','AdminCtl@modify');
			Route::get('removeUser','AdminCtl@removeUser');
			Route::get('resetPassword','AdminCtl@resetPassword');
		});

		// 导师相关路由
		Route::group(['prefix'=>'teacher'],function()
		{
			Route::get('relatedStudents', 'TeacherCtl@relatedStudents');

        	// 稍有安全隐患===>导师访问非自己的学生===>低发情况
			Route::get('studentInfo','StudentCtl@studentInfo');

			// 对任务的操作都有隐患===>老师之间互相访问操作===>低发情况
			Route::get('addTask','TaskCtl@add');
			Route::get('removeTask','TaskCtl@remove');
			Route::get('modifyTask','TaskCtl@modify');
			Route::get('taskFinish','TaskCtl@finish');

			Route::get('personalInfo','TeacherCtl@personalInfo');
			Route::get('modifyPersonalInfo','TeacherCtl@modifyPersonalInfo');
			Route::get('modifyPassword','TeacherCtl@modifyPassword');
		});


		// 学员相关路由
		Route::group(['prefix'=>'student'],function()
		{
			Route::get('relatedInfo','StudentCtl@relatedInfo');
			
			Route::get('personalInfo','StudentCtl@personalInfo');
			Route::get('modifyPersonalInfo','StudentCtl@modifyPersonalInfo');
			Route::get('modifyPassword','StudentCtl@modifyPassword');

			Route::get('subject1s', 'SubjectCtl@subject1s');
			Route::get('subject2', 'SubjectCtl@subject2');
			Route::get('getfile/{id}', 'LibfileCtl@download');
		});

		// 运营维护相关路由
		Route::group(['prefix'=>'vindicator'],function()
		{
			Route::get('subject1s', 'SubjectCtl@subject1s');
			Route::get('subject2', 'SubjectCtl@subject2');

			Route::get('addSub1', 'SubjectCtl@addSub1');
			Route::get('chSub1', 'SubjectCtl@chSub1');
			Route::get('rmSub1', 'SubjectCtl@rmSub1');

			Route::get('addSub2', 'SubjectCtl@addSub2');
			Route::get('rmSub2', 'SubjectCtl@rmSub2');
			Route::any('chSub2', 'SubjectCtl@chSub2');

			Route::any('addFile', 'LibfileCtl@addFile');
			Route::get('chFile', 'LibfileCtl@chFile');
			Route::get('rmFile', 'LibfileCtl@rmFile');

			Route::any('addClass', 'LibclassCtl@addClass');
			Route::any('chClass', 'LibclassCtl@chClass');
			Route::get('rmClass', 'LibclassCtl@rmClass');
		});
	});
});
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

