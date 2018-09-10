<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;

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

Auth::routes();
Route::any('/logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);
Route::middleware(['web', 'admin'])->group(function() {
    Route::get('admin', 'AdminController@index');
    Route::group([
        'namespace' => 'Admin',
        'as' => 'admin::',
        'prefix' => 'admin'
            ], function() {

        Route::any('/{controller}/{action?}', function ($_controllerName, $_actionName = 'index', Request $request) {
            $app = app();
            $cameledController = ucfirst(camel_case($_controllerName));
            $cameledAction = camel_case($_actionName);
            try {
                $controller = $app->make(sprintf('\App\Http\Controllers\Admin\%sController', $cameledController));
                return $controller->callAction($cameledAction, array($request));
            } catch (Exception $e) {
                if ($e instanceof ReflectionException || $e instanceof BadMethodCallException) {
                    return abort(500, $e->getMessage());
                } else {
                    throw $e;
                }
            }
        });
    });
});