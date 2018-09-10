<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;

abstract class Controller extends BaseController {

    protected $view = 'home';

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        view()->share('currentUser', Auth::user());
    }

    public function index(Request $request) {
        return view($this->view)->with([
                    'controllerName' => $request->controllerName
        ]);
    }

}
