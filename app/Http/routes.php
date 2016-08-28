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
	Route::get('/',function(){return view('welcome');});
	Route::get('addDatas','addDataCtl@index');

	Route::any('login/{role}','LoginCtl@index');
	Route::get('quit/{role}','LoginCtl@quit');
	Route::get('code','LoginCtl@code');

	Route::group(['middleware' => ['login']], function () 
	{
		Route::get('manage/{role}/{id}','LoginCtl@manage');
		// 管理员相关路由
		Route::group(['prefix'=>'admin'],function()
		{
			Route::get('progress', 'StudentCtl@progress');
			Route::get('finished', 'StudentCtl@finished');
			Route::any('studentInfo','StudentCtl@studentInfo');
			Route::any('setTeacher','StudentCtl@setTeacher');

			Route::get('teacherlist', 'TeacherCtl@teacherlist');
			Route::any('teacherInfo','TeacherCtl@teacherInfo');

			Route::any('addUser','AdminCtl@addUser');
			Route::any('removeUser','AdminCtl@removeUser');
			Route::any('resetPassword','AdminCtl@resetPassword');
			Route::any('modifyCmt','AdminCtl@modifyCmt');

			Route::get('users', 'AdminCtl@users');
		});

		// 导师相关路由
		Route::group(['prefix'=>'teacher'],function()
		{
			Route::get('relatedStudents', 'TeacherCtl@relatedStudents');

			Route::any('studentInfo','StudentCtl@studentInfo');

			Route::any('addTask','TaskCtl@add');
			Route::any('removeTask','TaskCtl@remove');
			Route::any('modifyTask','TaskCtl@modify');

			Route::any('personalInfo','TeacherCtl@personalInfo');
			Route::any('modifyPersonalInfo','TeacherCtl@modifyPersonalInfo');
			Route::any('modifyPassword','TeacherCtl@modifyPassword');
		});


		// 学员相关路由
		Route::group(['prefix'=>'student'],function()
		{
			// 学生可以通过关联关系来找到老师和任务信息
			Route::any('relatedInfo','StudentCtl@relatedInfo');
			
			Route::any('personalInfo','StudentCtl@personalInfo');
			Route::any('modifyPersonalInfo','StudentCtl@modifyPersonalInfo');
			Route::any('modifyPassword','StudentCtl@modifyPassword');

			Route::get('subjects', 'SubjectCtl@subjects');
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
