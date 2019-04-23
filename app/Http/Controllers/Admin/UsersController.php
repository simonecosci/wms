<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Common\CrudController;
use Illuminate\Http\Request;
use App\User;

class UsersController extends CrudController {

    public function __construct(User $model) {
        $this->model = $model;
    }

    public function prefs(Request $request) {
        if ($request->isMethod('post')) {
            $request->user()->prefs = $request->prefs;
            $request->user()->save();
        }
        if ($request->user()->prefs === null) {
            return "{}";
        }
        return $request->user()->prefs;
    }
}
