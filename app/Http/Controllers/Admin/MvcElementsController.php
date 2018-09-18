<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Common\CrudController;
use App\Models\MvcElement;

class MvcElementsController extends CrudController {

    protected $view = 'admin.mvc-elements';

    public function __construct(MvcElement $model) {
        $this->model = $model;
    }

    public function createMigration(Request $request) {
        if (!$request->has('model'))
            return abort("Missing aparameters");
        $model = json_decode($request->model);
        $code = view('admin.templates.migration', ['element' => $model])->render();
        $path = base_path('database/migrations') . DIRECTORY_SEPARATOR .
                'create_' . $model->model->table . '_table.php';
        File::put($path, '<?php' . PHP_EOL . $code);
        return $code;
    }

    public function createModel(Request $request) {
        if (!$request->has('model'))
            return abort("Missing aparameters");
        $model = json_decode($request->model);
        $code = view('admin.templates.model', ['element' => $model])->render();
        $path = app_path('Models') . DIRECTORY_SEPARATOR .
                $model->model->name . '.php';
        File::put($path, '<?php' . PHP_EOL . $code);
        return $code;
    }

    public function createController(Request $request) {
        if (!$request->has('model'))
            return abort("Missing aparameters");
        $model = json_decode($request->model);
        $code = view('admin.templates.controller', ['element' => $model])->render();
        $path = app_path('Http/Controllers/Admin') . DIRECTORY_SEPARATOR .
                $model->controller->name . '.php';
        File::put($path, '<?php' . PHP_EOL . $code);
        return $code;
    }

    public function createView(Request $request) {
        if (!$request->has('model'))
            return abort("Missing aparameters");
        $model = json_decode($request->model);
        $code = view('admin.templates.view', ['element' => $model])->render();
        $path = resource_path('views/admin') . DIRECTORY_SEPARATOR .
                $model->view->name . '.blade.php';
        File::put($path, $code);
        return $code;
    }

}
