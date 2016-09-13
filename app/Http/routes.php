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
	Route::get('/',function(){return view('test');});
	Route::get('test','TestCtl@index');

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

			Route::any('studentInfo','StudentCtl@studentInfo');
			Route::any('setTeacher','StudentCtl@setTeacher');

			Route::get('teacherlist', 'TeacherCtl@teacherlist');
			Route::any('teacherInfo','TeacherCtl@teacherInfo');

			Route::get('users', 'AdminCtl@users');
			Route::any('addUser','AdminCtl@addUser');
			Route::any('modify','AdminCtl@modify');
			Route::any('removeUser','AdminCtl@removeUser');
			Route::any('resetPassword','AdminCtl@resetPassword');
		});

		// 导师相关路由
		Route::group(['prefix'=>'teacher'],function()
		{
			Route::get('relatedStudents', 'TeacherCtl@relatedStudents');

			Route::any('studentInfo','StudentCtl@studentInfo');

			Route::any('addTask','TaskCtl@add');
			Route::any('removeTask','TaskCtl@remove');
			Route::any('modifyTask','TaskCtl@modify');
			Route::any('taskFinish','TaskCtl@finish');

			Route::any('personalInfo','TeacherCtl@personalInfo');
			Route::any('modifyPersonalInfo','TeacherCtl@modifyPersonalInfo');
			Route::any('modifyPassword','TeacherCtl@modifyPassword');
		});


		// 学员相关路由
		Route::group(['prefix'=>'student'],function()
		{
			Route::any('relatedInfo','StudentCtl@relatedInfo');
			
			Route::any('personalInfo','StudentCtl@personalInfo');
			Route::any('modifyPersonalInfo','StudentCtl@modifyPersonalInfo');
			Route::any('modifyPassword','StudentCtl@modifyPassword');

			Route::any('subject1s', 'SubjectCtl@subject1s');
			Route::any('subject2', 'SubjectCtl@subject2');
			Route::any('getfile/{id}', 'LibfileCtl@download');
		});

		// 运营维护相关路由
		Route::group(['prefix'=>'vindicator'],function()
		{
			Route::any('subject1s', 'SubjectCtl@subject1s');
			Route::any('subject2', 'SubjectCtl@subject2');

			Route::any('addSub1', 'SubjectCtl@addSub1');
			Route::any('chSub1', 'SubjectCtl@chSub1');
			Route::any('rmSub1', 'SubjectCtl@rmSub1');

			Route::any('addSub2', 'SubjectCtl@addSub2');
			Route::any('rmSub2', 'SubjectCtl@rmSub2');
			Route::any('chSub2', 'SubjectCtl@chSub2');

			Route::any('addFile', 'LibfileCtl@addFile');
			Route::any('chFile', 'LibfileCtl@chFile');
			Route::any('rmFile', 'LibfileCtl@rmFile');

			Route::any('addClass', 'LibclassCtl@addClass');
			Route::any('chClass', 'LibclassCtl@chClass');
			Route::any('rmClass', 'LibclassCtl@rmClass');
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

Route::group(['middleware' => ['web']], function () {
    //
});
