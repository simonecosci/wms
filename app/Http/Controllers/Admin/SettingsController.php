<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Common\Controller;

class SettingsController extends Controller {

    protected $view = 'admin.settings';
    
    public function upload(Request $request) {
        if (!$request->hasFile('file')) {
            return abort(400, "Missing file");
        }
        if (!$request->file('file')->isValid()) {
            return abort(400, "File not valid");
        }
        $file = $request->file('file');
        $file->storeAs('', $file->getClientOriginalName(), 'wallpapers');
        return [];
    }

    public function remove(Request $request) {
        $name = $request->input('fileNames');
        if (empty($name)) {
            return abort(400, "Empty fileNames");
        }
        if (!Storage::disk('wallpapers')->exists(basename($name))) {
            return abort(404, "File not found");
        }
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
            return abort(400, "Password not valid");
        }
        $check = Hash::check($password, Auth::user()->password);
        return ['check' => $check];
    }

    public function changePassword(Request $request) {
        if (($password = $request->input("password")) === null) {
            return abort(400, "Password not valid");
        }
        Auth::user()->password = Hash::make($password);
        Auth::user()->save();
        return ['result' => true];
    }

}
