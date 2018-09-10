<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Common\Controller;

class SettingsController extends Controller {

    protected $view = 'admin.settings';
    
    public function upload(Request $request) {
        if (!$request->hasFile('file')) {
            return abort(500);
        }
        if (!$request->file('file')->isValid()) {
            return abort(500);
        }
        $file = $request->file('file');
        $file->storeAs('', $file->getClientOriginalName(), 'wallpapers');
        return [];
    }

    public function remove(Request $request) {
        $name = $request->input('fileNames');
        if (empty($name))
            return abort(500);
        if (!Storage::disk('wallpapers')->exists(basename($name)))
            return abort(404);
        Storage::disk('wallpapers')->delete(basename($name));
        return [];
    }

    public function wallpapers() {
        $files = glob(public_path() . 
                DIRECTORY_SEPARATOR . 'images' . 
                DIRECTORY_SEPARATOR . 'wallpapers' . 
                DIRECTORY_SEPARATOR . '*.jpg');
        $wallpapers = array_map(function($item) {
            return str_replace(DIRECTORY_SEPARATOR, '/', 
                    substr($item, strlen(public_path())));
        }, $files);
        array_unshift($wallpapers, '');
        return $wallpapers;
    }

    public function checkPassword(Request $request) {
        if (($password = $request->input("password")) === null) {
            throw new BadRequestHttpException();
        }
        $check = Hash::check($password, Auth::user()->password);
        return ['check' => $check];
    }

    public function changePassword(Request $request) {
        if (($password = $request->input("password")) === null) {
            throw new BadRequestHttpException();
        }
        Auth::user()->password = Hash::make($password);
        Auth::user()->save();
        return ['result' => true];
    }

}
