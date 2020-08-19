<?php
use SwoStar\Route\Route;
Route::get('index', "IndexController@index");
Route::get('index/dd', function(){
	return "hello";
});
Route::wsController('demo', 'DemoController');
?>