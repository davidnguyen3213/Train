<?php
use App\Http\Middleware\CheckAge;
use Symfony\Component\HttpFoundation\Response;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('user/{id}', function ($id) {
    return 'User '.$id;
});

Route::get('hello/{age}',function(){
    return 'hello';
})->middleware(CheckAge::class);

Route::resource('test','TestController');

Route::get('home', function () {
    // return response('Hello World', 200)
    //               ->header('Content-Type', 'text/plain');
    $content = 'abc';
    $type = 'def';
    return response($content)
        ->header('Content-Type', $type)
        ->header('X-Header-One', 'Header Value')
        ->header('X-Header-Two', 'Header Value');
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
