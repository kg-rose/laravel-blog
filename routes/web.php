<?php

/**
 * 这个文件是路由配置文件
 */

// Route::动作('url', '控制器@方法');

Route::get('/', 'StaticPagesController@home')->name('home'); //主页
Route::get('/help', 'StaticPagesController@help')->name('help'); //帮助
Route::get('/about', 'StaticPagesController@about')->name('about'); //关于

Route::get('/signup', 'UsersController@create')->name('signup'); //用户注册

// 配置资源路由
Route::resource('users', 'UsersController');

// 登陆、登出
Route::get('login', 'SessionsController@create')->name('login');
Route::post('login', 'SessionsController@store')->name('login');
Route::delete('logout', 'SessionsController@destroy')->name('logout');

Route::get('/signup/user/{id}/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email'); //账户激活

Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request'); //密码重置页面
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email'); //邮件发送链接
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset'); //密码更新页
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update'); //密码更新功能

Route::resource('statuses', 'StatusesController', ['only' => ['store', 'destroy']]); //创建和删除微博

Route::get('/users/{user}/followings', 'UsersController@followings')->name('users.followings'); //关注的人
Route::get('/users/{user}/followers', 'UsersController@followers')->name('users.followers'); //我的粉丝

Route::post('/follow/{user}', 'FollowersController@store')->name('followers.store'); //关注
Route::delete('/follow/{user}', 'FollowersController@destroy')->name('followers.destroy'); //取消关注